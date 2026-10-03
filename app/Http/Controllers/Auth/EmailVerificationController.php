<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PHPMailerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class EmailVerificationController extends Controller
{
    protected PHPMailerService $mailer;

    public function __construct(PHPMailerService $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Helper to resolve the user who needs verification.
     */
    protected function getPendingUser(Request $request): ?User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        $userId = $request->session()->get('verify_user_id');
        if ($userId) {
            return User::find($userId);
        }

        $email = $request->session()->get('verify_email');
        if ($email) {
            return User::where('email', $email)->first();
        }

        return null;
    }

    /**
     * Show the OTP email verification form.
     */
    public function showVerification(Request $request)
    {
        $user = $this->getPendingUser($request);

        if (!$user) {
            return redirect()->route('login')->with('info', 'يرجى تسجيل الدخول أو إنشاء حساب للتحقق من البريد.');
        }

        if ($user->isEmailVerified()) {
            return redirect()->route('home')->with('info', 'تم توثيق بريدك الإلكتروني بالفعل.');
        }

        $cooldown = $user->getOtpCooldownSeconds(60);
        $email = $user->email;

        return view('auth.verify-email', compact('email', 'cooldown'));
    }

    /**
     * Verify the submitted OTP code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'يرجى إدخال رمز التحقق المكون من 6 أرقام.',
            'otp.size'     => 'يجب أن يتكون رمز التحقق من 6 أرقام تماماً.',
        ]);

        $user = $this->getPendingUser($request);

        if (!$user) {
            return redirect()->route('login')->with('error', 'انتهت الجلسة. يرجى تسجيل الدخول مجدداً.');
        }

        if ($user->isEmailVerified()) {
            if (!Auth::check()) {
                Auth::login($user);
            }
            return redirect()->route('home')->with('info', 'حسابك موثق بالفعل.');
        }

        // Check if OTP has expired (30 seconds)
        if (is_null($user->otp_expires_at) || $user->otp_expires_at->isPast()) {
            return back()->withErrors([
                'otp' => 'انتهت صلاحية رمز التحقق (صالح لمدة 30 ثانية). يرجى الضغط على "إعادة إرسال رمز التحقق" للحصول على رمز جديد.',
            ]);
        }

        // Rate limit attempts on the current OTP (max 5 tries)
        if ($user->otp_attempts >= 5) {
            return back()->withErrors([
                'otp' => 'تم تجاوز الحد الأقصى للمحاولات الخاطئة (5 محاولات). يرجى طلب رمز تحقق جديد.',
            ]);
        }

        // Verify Hash of OTP
        if (!Hash::check($request->otp, $user->otp_hash)) {
            $user->increment('otp_attempts');
            $remaining = 5 - $user->otp_attempts;

            $errorMsg = 'رمز التحقق غير صحيح، يرجى التأكد من الرمز المدخل والمحاولة مجدداً.';
            if ($remaining > 0) {
                $errorMsg .= " (متبقي لديك {$remaining} محاولات)";
            }

            return back()->withErrors(['otp' => $errorMsg]);
        }

        // Verification SUCCESS: Invalidate OTP and mark email as verified
        $user->update([
            'email_verified_at' => now(),
            'otp_hash'          => null,
            'otp_expires_at'    => null,
            'otp_attempts'      => 0,
        ]);

        // Clean up session flags
        $request->session()->forget(['verify_user_id', 'verify_email']);

        // Log the user in
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', "تم توثيق بريدك الإلكتروني وتفعيل الحساب بنجاح! مرحباً بك في متجر خيوط دعجاء، {$user->name}.");
    }

    /**
     * Resend a fresh OTP code with rate-limiting.
     */
    public function resend(Request $request)
    {
        $user = $this->getPendingUser($request);

        if (!$user) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أو إنشاء حساب أولاً.');
        }

        if ($user->isEmailVerified()) {
            return redirect()->route('home')->with('info', 'حسابك موثق بالفعل.');
        }

        // Cooldown enforcement (60 seconds)
        if (!$user->canResendOtp(60)) {
            $seconds = $user->getOtpCooldownSeconds(60);
            return back()->with('error', "يرجى الانتظار {$seconds} ثانية قبل طلب رمز تحقق جديد.");
        }

        // System rate limit: max 4 resend requests per 15 minutes per IP/User
        $throttleKey = 'resend_otp|' . $user->id . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 4)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            return back()->with('error', "تم تجاوز عدد طلبات إرسال الرمز المسموح بها. يرجى الانتظار {$minutes} دقيقة.");
        }
        RateLimiter::hit($throttleKey, 900);

        // Generate new random 6-digit OTP
        $newOtp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Invalidate old OTP and store new hashed OTP
        $user->update([
            'otp_hash'         => Hash::make($newOtp),
            'otp_expires_at'   => now()->addSeconds(30),
            'otp_last_sent_at' => now(),
            'otp_attempts'     => 0,
        ]);

        // Send via PHPMailer
        $result = $this->mailer->sendOtpEmail($user->email, $user->name, $newOtp, 30);

        if (!$result['success']) {
            return back()->with('warning', 'تم إصدار رمز جديد ولكن تعذر تسليم البريد عبر الخادم حالياً. يرجى المحاولة بعد قليل.');
        }

        return back()->with('success', 'تم إرسال رمز تحقق جديد إلى بريدك الإلكتروني بنجاح.');
    }
}
