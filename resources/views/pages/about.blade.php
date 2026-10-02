@extends('layouts.app')

@section('title', 'من نحن | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 36px 0;">
        <div class="container">
            <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                من نحن
            </h1>
            <p style="font-size: 0.9rem; color: var(--brand-gold-dark); font-weight: 600;">
                خيوط دعجاء.. حيث تلتقي الأصالة بالفخامة
            </p>
        </div>
    </div>

    <section class="section">
        <div class="container" style="max-width: 900px;">
            
            <!-- Story Card -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 48px; box-shadow: var(--shadow-card); margin-bottom: 40px;">
                
                <div style="text-align: center; margin-bottom: 32px;">
                    <img src="{{ asset('images/logo.webp') }}" alt="خيوط دعجاء" style="height: 64px; margin: 0 auto 16px; object-fit: contain;">
                    <h2 style="font-size: 1.6rem; font-weight: 700; color: var(--brand-primary); margin-bottom: 8px;">
                        خيوط دعجاء.. حيث تلتقي الأصالة بالفخامة
                    </h2>
                    <div style="font-size: 0.95rem; color: var(--brand-gold-dark);">
                        أرقى العبايات الخليجية التي تجمع بين الأصالة والحداثة
                    </div>
                </div>

                <div style="font-size: 1.12rem; line-height: 2.1; color: var(--text-secondary); text-align: justify; margin-bottom: 36px; white-space: pre-line;">
                    {!! nl2br(e($aboutText)) !!}
                </div>

                <div class="form-grid-3col" style="border-top: 1px solid var(--border-light); padding-top: 30px; text-align: center;">
                    <div style="padding: 16px; background: #FAF8F5; border-radius: 8px;">
                        <i class="fa-solid fa-gem" style="font-size: 1.8rem; color: var(--brand-gold); margin-bottom: 10px;"></i>
                        <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 4px;">جودة لا تُضاهى</h4>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">انتقاء أدق خيوط وأقمشة الكريب والصالونا الأصلي</p>
                    </div>

                    <div style="padding: 16px; background: #FAF8F5; border-radius: 8px;">
                        <i class="fa-solid fa-scissors" style="font-size: 1.8rem; color: var(--brand-gold); margin-bottom: 10px;"></i>
                        <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 4px;">حرفية في التصميم</h4>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">قصات خليجية مدروسة تمنحكِ الراحة والأناقة</p>
                    </div>

                    <div style="padding: 16px; background: #FAF8F5; border-radius: 8px;">
                        <i class="fa-solid fa-heart" style="font-size: 1.8rem; color: var(--brand-gold); margin-bottom: 10px;"></i>
                        <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 4px;">عناية بالعميلة</h4>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">اهتمام بأدق التفاصيل من التغليف وحتى التوصيل</p>
                    </div>
                </div>

            </div>

            <!-- Branches Preview inside About -->
            @if($branches->count() > 0)
                <div style="background: #FAF8F5; border: 1px solid var(--border-gold); border-radius: var(--radius-md); padding: 32px; text-align: center;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                        فروعنا في المملكة العربية السعودية
                    </h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">
                        يسعدنا استقبالكِ في فروعنا الرسمية بـ <strong>الطائف الدولي</strong> و <strong>حفر الباطن – لوريت سنتر</strong>
                    </p>
                    <a href="{{ route('pages.branches') }}" class="btn btn-gold btn-sm">
                        عرض مواقع الفروع وساعات العمل &larr;
                    </a>
                </div>
            @endif

        </div>
    </section>

@endsection
