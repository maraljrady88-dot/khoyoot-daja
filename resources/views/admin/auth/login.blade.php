<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الإدارة | خيوط دعجاء</title>
    
    <link rel="icon" type="image/webp" href="/images/logo.webp">
    <link rel="stylesheet" href="/css/thmanyah-font.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body style="background: #141312; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">

    <div style="background: #FFFFFF; border-radius: 12px; width: 100%; max-width: 440px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
        
        <div style="text-align: center; margin-bottom: 28px;">
            <img src="/images/logo.webp" alt="خيوط دعجاء" style="height: 60px; margin: 0 auto 12px; object-fit: contain;">
            <h1 style="font-size: 1.45rem; font-weight: 700; color: #181615; margin-bottom: 6px;">
                لوحة تحكم خيوط دعجاء
            </h1>
            <p style="font-size: 0.85rem; color: #8E867F;">
                تسجيل الدخول للمسؤولين فقط
            </p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني الإداري *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', 'admin@dajaabaya.sa') }}" required autofocus placeholder="admin@dajaabaya.sa" dir="ltr" style="text-align: right;">
            </div>

            <div class="form-group">
                <label class="form-label">كلمة المرور *</label>
                <div class="password-input-wrap">
                    <input type="password" name="password" id="adminPassword" class="form-control" required placeholder="••••••••">
                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility(this)" title="إظهار / إخفاء كلمة المرور" aria-label="إظهار كلمة المرور">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; font-size: 0.85rem;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #5C5550;">
                    <input type="checkbox" name="remember" value="1">
                    <span>تذكرني</span>
                </label>
            </div>

            <button type="submit" class="btn btn-gold btn-lg" style="width: 100%;">
                <i class="fa-solid fa-lock"></i> الدخول إلى لوحة التحكم
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 0.82rem; color: #8E867F; border-top: 1px solid #EBE5DB; padding-top: 16px;">
            <a href="{{ route('home') }}" style="color: var(--brand-gold); font-weight: 600;">&larr; العودة إلى واجهة المتجر</a>
        </div>

    </div>

    <script>
        function togglePasswordVisibility(button) {
            const wrap = button.closest('.password-input-wrap');
            if (!wrap) return;
            const input = wrap.querySelector('input');
            const icon = button.querySelector('i');
            if (!input || !icon) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
