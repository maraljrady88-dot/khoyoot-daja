@extends('layouts.app')

@section('title', 'التحقق من البريد الإلكتروني | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <section class="section" style="padding: 60px 0;">
        <div class="container" style="max-width: 500px;">
            <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 40px 32px; box-shadow: var(--shadow-card);">
                
                <div style="text-align: center; margin-bottom: 24px;">
                    <img src="{{ asset('images/logo.webp') }}" alt="خيوط دعجاء" style="height: 52px; margin: 0 auto 14px; object-fit: contain;">
                    <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 50%; background: #FAF8F5; border: 1px solid var(--brand-gold); margin-bottom: 14px; color: var(--brand-gold-dark); font-size: 1.4rem;">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <h1 style="font-size: 1.45rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                        تأكيد البريد الإلكتروني
                    </h1>
                    <p style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.6; margin: 0;">
                        أرسلنا رمز تحقق (OTP) مكون من 6 أرقام إلى بريدك الإلكتروني:<br>
                        <strong dir="ltr" style="color: var(--brand-primary); font-size: 0.95rem; display: inline-block; margin-top: 4px;">{{ $email }}</strong>
                    </p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success" style="margin-bottom: 20px;">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert" style="background-color: #FFFBEB; color: #92400E; border: 1px solid #FCD34D; padding: 12px 16px; border-radius: 6px; font-size: 0.88rem; margin-bottom: 20px;">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ session('warning') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error" style="margin-bottom: 20px;">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error" style="margin-bottom: 20px;">
                        <ul style="list-style: none; margin: 0; padding: 0;">
                            @foreach ($errors->all() as $error)
                                <li><i class="fa-solid fa-circle-xmark"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- OTP Verification Form -->
                <form action="{{ route('verification.verify') }}" method="POST" id="verifyOtpForm">
                    @csrf
                    
                    <div class="form-group" style="margin-bottom: 22px; text-align: center;">
                        <label class="form-label" style="display: block; font-size: 0.92rem; font-weight: 600; margin-bottom: 10px;">
                            أدخل رمز التحقق (6 أرقام):
                        </label>
                        <input type="text" 
                               name="otp" 
                               id="otpInput" 
                               maxlength="6" 
                               required 
                               autofocus 
                               autocomplete="one-time-code" 
                               inputmode="numeric" 
                               pattern="[0-9]{6}"
                               placeholder="------" 
                               class="form-control" 
                               dir="ltr" 
                               style="font-family: 'Courier New', Courier, monospace; font-size: 1.75rem; font-weight: 800; letter-spacing: 12px; text-align: center; height: 56px; border: 2px solid var(--brand-gold); background: #FAF8F5; border-radius: 8px;">
                        <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 6px;">
                            ⏱️ صلاحية الرمز: 30 ثانية من وقت الإرسال
                        </small>
                    </div>

                    <button type="submit" class="btn btn-gold btn-lg" style="width: 100%;">
                        <i class="fa-solid fa-circle-check"></i> تأكيد وتفعيل الحساب
                    </button>
                </form>

                <!-- Resend OTP Section -->
                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-light); text-align: center;">
                    <div style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 10px;">
                        لم يصلك الرمز بعد؟ تفحّص صندوق الرسائل غير المرغوب فيها (Junk/Spam).
                    </div>
                    
                    <form action="{{ route('verification.resend') }}" method="POST" id="resendOtpForm">
                        @csrf
                        <button type="submit" 
                                id="resendBtn" 
                                class="btn btn-outline-dark btn-sm" 
                                style="font-size: 0.85rem; padding: 8px 18px;" 
                                @if($cooldown > 0) disabled @endif>
                            <i class="fa-solid fa-arrows-rotate"></i> 
                            <span id="resendText">
                                @if($cooldown > 0)
                                    إعادة الإرسال بعد ({{ $cooldown }} ثانية)
                                @else
                                    إعادة إرسال رمز التحقق
                                @endif
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Back / Logout -->
                <div style="margin-top: 20px; text-align: center; font-size: 0.85rem;">
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: var(--text-muted); text-decoration: underline; cursor: pointer; font-size: 0.82rem;">
                            العودة لتسجيل الدخول بحساب آخر &larr;
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <!-- Cooldown Countdown Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let cooldown = {{ (int) $cooldown }};
            const btn = document.getElementById('resendBtn');
            const btnText = document.getElementById('resendText');
            const otpInput = document.getElementById('otpInput');

            // Auto-submit when 6 digits are typed
            if (otpInput) {
                otpInput.addEventListener('input', function () {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length === 6) {
                        document.getElementById('verifyOtpForm').submit();
                    }
                });
            }

            // Countdown timer
            if (cooldown > 0 && btn && btnText) {
                const interval = setInterval(function () {
                    cooldown--;
                    if (cooldown <= 0) {
                        clearInterval(interval);
                        btn.disabled = false;
                        btnText.innerText = 'إعادة إرسال رمز التحقق';
                    } else {
                        btnText.innerText = 'إعادة الإرسال بعد (' + cooldown + ' ثانية)';
                    }
                }, 1000);
            }
        });
    </script>

@endsection
