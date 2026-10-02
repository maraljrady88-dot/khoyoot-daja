@extends('layouts.app')

@section('title', 'إنشاء حساب جديد | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <section class="section" style="padding: 60px 0;">
        <div class="container" style="max-width: 520px;">
            <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 40px; box-shadow: var(--shadow-card);">
                
                <div style="text-align: center; margin-bottom: 28px;">
                    <img src="{{ asset('images/logo.webp') }}" alt="خيوط دعجاء" style="height: 54px; margin: 0 auto 12px; object-fit: contain;">
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                        إنشاء حساب عميلة جديدة
                    </h1>
                    <p style="font-size: 0.88rem; color: var(--text-muted);">
                        انضمي لعائلة خيوط دعجاء واستمتعي بتجربة تسوق فريدة ومتابعة فورية لطلباتكِ
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

                <form action="{{ route('register.submit') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">الاسم الكامل *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus placeholder="مثال: نورة العتيبي">
                    </div>

                    <div class="form-group">
                        <label class="form-label">رقم الجوال السعودي *</label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="05XXXXXXXX" dir="ltr" style="text-align: right;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">البريد الإلكتروني *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="name@domain.com" dir="ltr" style="text-align: right;">
                    </div>

                    <div class="form-grid-2col">
                        <div class="form-group">
                            <label class="form-label">كلمة المرور *</label>
                            <div class="password-input-wrap">
                                <input type="password" name="password" id="regPassword" class="form-control" required placeholder="••••••••">
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility(this)" title="إظهار / إخفاء كلمة المرور" aria-label="إظهار كلمة المرور">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">تأكيد كلمة المرور *</label>
                            <div class="password-input-wrap">
                                <input type="password" name="password_confirmation" id="regPasswordConf" class="form-control" required placeholder="••••••••">
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility(this)" title="إظهار / إخفاء كلمة المرور" aria-label="إظهار كلمة المرور">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gold btn-lg" style="width: 100%; margin-top: 10px; margin-bottom: 18px;">
                        <i class="fa-solid fa-user-plus"></i> تأكيد إنشاء الحساب
                    </button>
                </form>

                <div style="text-align: center; font-size: 0.9rem; color: var(--text-muted); border-top: 1px solid var(--border-light); padding-top: 20px;">
                    لديكِ حساب بالفعل؟ 
                    <a href="{{ route('login') }}" style="color: var(--brand-gold-dark); font-weight: 700;">
                        تسجيل الدخول هنا &larr;
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection
