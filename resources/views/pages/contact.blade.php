@extends('layouts.app')

@section('title', 'تواصل معنا | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 36px 0;">
        <div class="container">
            <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                تواصل معنا
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">
                نسعد بخدمتكِ والإجابة على كافة استفساراتكِ وملاحظاتكِ حول تصاميمنا وطلباتكِ
            </p>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="product-detail-grid">
                
                <!-- Contact Information Column -->
                <div>
                    <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 32px; box-shadow: var(--shadow-soft); margin-bottom: 24px;">
                        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light);">
                            قنوات التواصل الرسمية
                        </h2>

                        <div style="display: flex; flex-direction: column; gap: 18px;">
                            <!-- Phone -->
                            @if($phone = \App\Models\Setting::get('contact_phone', '+966 50 000 0000'))
                                <div style="display: flex; gap: 14px; align-items: center;">
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #FAF8F5; border: 1px solid var(--border-gold); display: flex; align-items: center; justify-content: center; color: var(--brand-gold-dark); font-size: 1.1rem; flex-shrink: 0;">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">الهاتف وخدمة العملاء:</div>
                                        <a href="tel:{{ $phone }}" dir="ltr" style="font-weight: 600; color: var(--text-primary); font-size: 1rem;">{{ $phone }}</a>
                                    </div>
                                </div>
                            @endif

                            <!-- WhatsApp -->
                            @if($wa = \App\Models\Setting::get('whatsapp_number', '966500000000'))
                                <div style="display: flex; gap: 14px; align-items: center;">
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #ECFDF5; border: 1px solid #A7F3D0; display: flex; align-items: center; justify-content: center; color: #059669; font-size: 1.3rem; flex-shrink: 0;">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">المحادثة المباشرة عبر واتساب:</div>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}" target="_blank" rel="noopener noreferrer" style="font-weight: 600; color: #059669; font-size: 0.95rem;">
                                            اضغطي هنا لبدء المحادثة &larr;
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Email -->
                            @if($email = \App\Models\Setting::get('contact_email', 'info@dajaabaya.sa'))
                                <div style="display: flex; gap: 14px; align-items: center;">
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #FAF8F5; border: 1px solid var(--border-gold); display: flex; align-items: center; justify-content: center; color: var(--brand-gold-dark); font-size: 1.1rem; flex-shrink: 0;">
                                        <i class="fa-regular fa-envelope"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">البريد الإلكتروني:</div>
                                        <a href="mailto:{{ $email }}" style="font-weight: 600; color: var(--text-primary); font-size: 0.95rem;">{{ $email }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Social Media -->
                        <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border-light);">
                            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-primary); margin-bottom: 12px;">تابعينا على وسائل التواصل الاجتماعي:</div>
                            <div style="display: flex; gap: 12px;">
                                @if($snap = \App\Models\Setting::get('snapchat_url'))
                                    <a href="{{ $snap }}" target="_blank" rel="noopener noreferrer" class="social-icon-btn" style="background: #FAF8F5; color: #181615; border-color: #EBE5DB;" title="Snapchat">
                                        <i class="fa-brands fa-snapchat"></i>
                                    </a>
                                @endif
                                @if($tiktok = \App\Models\Setting::get('tiktok_url'))
                                    <a href="{{ $tiktok }}" target="_blank" rel="noopener noreferrer" class="social-icon-btn" style="background: #FAF8F5; color: #181615; border-color: #EBE5DB;" title="TikTok">
                                        <i class="fa-brands fa-tiktok"></i>
                                    </a>
                                @endif
                                @if($insta = \App\Models\Setting::get('instagram_url'))
                                    <a href="{{ $insta }}" target="_blank" rel="noopener noreferrer" class="social-icon-btn" style="background: #FAF8F5; color: #181615; border-color: #EBE5DB;" title="Instagram">
                                        <i class="fa-brands fa-instagram"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Branches Quick Box -->
                    <div style="background: #FAF8F5; border: 1px solid var(--border-gold); border-radius: var(--radius-md); padding: 24px;">
                        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--brand-primary); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-store" style="color: var(--brand-gold-dark);"></i>
                            فروع خيوط دعجاء
                        </h3>
                        <div style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.8;">
                            • فرع الطائف الدولي - طريق الملك خالد<br>
                            • فرع حفر الباطن – لوريت سنتر - طريق الملك فيصل
                        </div>
                        <div style="margin-top: 14px;">
                            <a href="{{ route('pages.branches') }}" style="color: var(--brand-gold-dark); font-size: 0.85rem; font-weight: 600;">
                                مشاهدة تفاصيل وساعات عمل الفروع &larr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Column -->
                <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 36px; box-shadow: var(--shadow-soft);">
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                        أرسلي لنا رسالة
                    </h2>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 24px;">
                        يسرنا استقبال آرائكِ واستفساراتك وسيقوم فريقنا بالتواصل معكِ خلال 24 ساعة
                    </p>

                    <form action="{{ route('pages.contact.submit') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">الاسم الكريم *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="أدخلي اسمكِ الكامل">
                        </div>

                        <div class="form-grid-2col">
                            <div class="form-group">
                                <label class="form-label">رقم الجوال *</label>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="05XXXXXXXX" dir="ltr" style="text-align: right;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">البريد الإلكتروني (اختياري)</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@domain.com" dir="ltr" style="text-align: right;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">موضوع الرسالة (اختياري)</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" placeholder="مثال: استفسار عن مقاس / تفصيل خاص">
                        </div>

                        <div class="form-group">
                            <label class="form-label">نص الرسالة أو الاستفسار *</label>
                            <textarea name="message" rows="5" class="form-control" required placeholder="اكتبي استفساركِ بالتفصيل هنا...">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-gold btn-lg" style="width: 100%;">
                            <i class="fa-solid fa-paper-plane"></i> إرسال الرسالة
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

@endsection
