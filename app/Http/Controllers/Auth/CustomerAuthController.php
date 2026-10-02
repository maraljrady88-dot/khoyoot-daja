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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'تم إنشاء حسابكِ بنجاح! نورتِ متجر خيوط دعجاء.');
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
