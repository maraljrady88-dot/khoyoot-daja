<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'يرجى إدخال البريد الإلكتروني للمسؤول',
            'password.required' => 'يرجى إدخال كلمة المرور',
        ]);

        $throttleKey = 'admin_login|' . strtolower($request->input('email')) . '|' . $request->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "تم تجاوز الحد المسموح لمحاولات تسجيل الدخول الإداري. يرجى الانتظار {$seconds} ثانية قبل المحاولة مجدداً.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->isAdmin()) {
                Auth::logout();
                \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);
                return back()->with('error', 'هذا الحساب ليس له صلاحيات الوصول إلى لوحة تحكم المتجر.');
            }

            if (!$user->is_active) {
                Auth::logout();
                \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);
                return back()->with('error', 'تم تعطيل هذا الحساب الإداري.');
            }

            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'تم تسجيل الخروج من لوحة التحكم بنجاح.');
    }
}
