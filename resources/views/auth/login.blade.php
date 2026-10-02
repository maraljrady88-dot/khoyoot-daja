@extends('layouts.app')

@section('title', 'تسجيل الدخول | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <section class="section" style="padding: 60px 0;">
        <div class="container" style="max-width: 480px;">
            <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 40px; box-shadow: var(--shadow-card);">
                
                <div style="text-align: center; margin-bottom: 28px;">
                    <img src="{{ asset('images/logo.webp') }}" alt="خيوط دعجاء" style="height: 54px; margin: 0 auto 12px; object-fit: contain;">
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                        تسجيل الدخول
                    </h1>
                    <p style="font-size: 0.88rem; color: var(--text-muted);">
                        أهلاً بكِ مجدداً في متجر خيوط دعجاء
                    </p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul style="list-style: none; margin: 0; padding: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">البريد الإلكتروني أو رقم الجوال *</label>
                        <input type="text" name="login" class="form-control" value="{{ old('login') }}" required autofocus placeholder="05XXXXXXXX أو البريد الإلكتروني" dir="ltr" style="text-align: right;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">كلمة المرور *</label>
                        <div class="password-input-wrap">
                            <input type="password" name="password" id="loginPassword" class="form-control" required placeholder="••••••••">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility(this)" title="إظهار / إخفاء كلمة المرور" aria-label="إظهار كلمة المرور">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; font-size: 0.85rem;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: var(--text-secondary);">
                            <input type="checkbox" name="remember" value="1">
                            <span>تذكرني على هذا الجهاز</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-gold btn-lg" style="width: 100%; margin-bottom: 18px;">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> دخول
                    </button>
                </form>

                <div style="text-align: center; font-size: 0.9rem; color: var(--text-muted); border-top: 1px solid var(--border-light); padding-top: 20px;">
                    ليس لديكِ حساب بعد؟ 
                    <a href="{{ route('register') }}" style="color: var(--brand-gold-dark); font-weight: 700;">
                        إنشاء حساب جديد &larr;
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection
