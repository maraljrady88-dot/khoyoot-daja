<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('customer.profile');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'يرجى إدخال البريد الإلكتروني أو رقم الجوال',
            'password.required' => 'يرجى إدخال كلمة المرور',
        ]);

        $throttleKey = 'customer_login|' . strtolower($request->input('login')) . '|' . $request->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'login' => "تم تجاوز عدد محاولات الدخول المسموح بها. يرجى الانتظار {$seconds} ثانية ثم المحاولة مجدداً.",
            ])->onlyInput('login');
        }

        $login = $request->login;
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $field => $login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);
                return back()->with('error', 'هذا الحساب معطل حالياً، يرجى التواصل مع إدارة المتجر.');
            }

            // Verify email check for customers
            if (!$user->isAdmin() && !$user->isEmailVerified()) {
                Auth::logout();
                $request->session()->put('verify_user_id', $user->id);
                $request->session()->put('verify_email', $user->email);

                // If OTP expired, generate and send a fresh one
                if (is_null($user->otp_expires_at) || $user->otp_expires_at->isPast()) {
                    $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
                    $user->update([
                        'otp_hash'         => Hash::make($otp),
                        'otp_expires_at'   => now()->addMinutes(10),
                        'otp_last_sent_at' => now(),
                        'otp_attempts'     => 0,
                    ]);
                    app(\App\Services\PHPMailerService::class)->sendOtpEmail($user->email, $user->name, $otp, 10);
                }

                return redirect()->route('verification.notice')->with('info', 'يرجى توثيق بريدكِ الإلكتروني أولاً لتتمكني من الدخول إلى حسابكِ.');
            }

            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'))->with('success', "أهلاً بكِ مجدداً، {$user->name}!");
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'login' => 'بيانات الدخول غير صحيحة، يرجى التأكد من البريد أو الجوال وكلمة المرور.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('customer.profile');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:150|unique:users',
            'phone' => ['required', 'regex:/^(05|\+?9665)[0-9]{8}$/', 'unique:users,phone'],
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'يرجى كتابة الاسم الكامل',
            'email.required' => 'يرجى كتابة البريد الإلكتروني',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
            'phone.required' => 'يرجى كتابة رقم الجوال',
            'phone.regex' => 'رقم الجوال غير صحيح (مثال: 05XXXXXXXX)',
            'phone.unique' => 'رقم الجوال مسجل مسبقاً',
            'password.required' => 'يرجى كتابة كلمة المرور',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 6 خانات',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        // Generate 6-digit random secure OTP
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
            'role'              => 'customer',
            'is_active'         => true,
            'email_verified_at' => null,
            'otp_hash'          => Hash::make($otp),
            'otp_expires_at'    => now()->addMinutes(10),
            'otp_last_sent_at'  => now(),
            'otp_attempts'      => 0,
        ]);

        // Send OTP via PHPMailer
        $mailer = app(\App\Services\PHPMailerService::class);
        $result = $mailer->sendOtpEmail($user->email, $user->name, $otp, 10);

        // Store user in session for verification page
        $request->session()->put('verify_user_id', $user->id);
        $request->session()->put('verify_email', $user->email);

        if (!$result['success']) {
            return redirect()->route('verification.notice')->with('warning', 'تم إنشاء الحساب، ولكن تعذر تسليم البريد عبر الخادم فوراً. يمكنكِ طلب إعادة إرسال الرمز.');
        }

        return redirect()->route('verification.notice')->with('success', 'تم إنشاء حسابكِ بنجاح! تم إرسال رمز التحقق (OTP) إلى بريدكِ الإلكتروني.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'تم تسجيل الخروج بنجاح.');
    }

    public function profile()
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isEmailVerified()) {
            session(['verify_user_id' => $user->id, 'verify_email' => $user->email]);
            return redirect()->route('verification.notice')->with('info', 'يرجى توثيق بريدكِ الإلكتروني للمتابعة.');
        }

        $recentOrders = $user->orders()->with('items.product')->latest()->take(5)->get();

        return view('customer.profile', compact('user', 'recentOrders'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:150',
            'phone' => ['required', 'regex:/^(05|\+?9665)[0-9]{8}$/', 'unique:users,phone,' . $user->id],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'تم تحديث بياناتكِ بنجاح.');
    }
}
