@extends('layouts.admin')

@section('title', 'إعدادات المتجر العامة | خيوط دعجاء')
@section('page_title', 'إعدادات وهوية المتجر')

@section('content')

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="admin-grid-main-side">
            
            <!-- Left Column: Branding and Texts -->
            <div>
                <!-- General Identity Card -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">هوية المتجر والشعارات</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label class="form-label">اسم المتجر *</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'خيوط دعجاء') }}" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label">العبارة الرئيسية (Tagline) *</label>
                            <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? 'خيوط دعجاء.. حيث تلتقي الأصالة بالفخامة') }}" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label">النص الفرعي تحت الشعار *</label>
                            <input type="text" name="site_subtitle" value="{{ old('site_subtitle', $settings['site_subtitle'] ?? 'أرقى العبايات الخليجية التي تجمع بين الأصالة والحداثة.') }}" required class="form-control">
                        </div>

                    </div>
                </div>

                <!-- Announcement Bar Card -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">إدارة الشريط الإعلاني المتحرك في أعلى الموقع (Animated Announcement Bar)</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="announcement_enabled" value="1" {{ !empty($settings['announcement_enabled']) && $settings['announcement_enabled'] == '1' ? 'checked' : '' }}>
                                <strong>تفعيل ظهور الشريط الإعلاني المتحرك في أعلى الموقع</strong>
                            </label>
                            <small style="color: var(--admin-text-muted); font-size: 0.8rem; display: block; margin-top: 4px;">
                                يتحرك النص بانسيابية مستمرة من اليمين إلى اليسار، ويتوقف تلقائياً عند مرور مؤشر الفأرة عليه لتمكين الزائر من القراءة والضغط بسهولة.
                            </small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">نص الإعلان أو الخصم (مثال: خصم 20% بمناسبة إطلاق التشكيلة الجديدة)</label>
                            <input type="text" name="announcement_text" value="{{ old('announcement_text', $settings['announcement_text'] ?? '') }}" class="form-control" placeholder="اكتبي نص الإعلان هنا...">
                        </div>

                        <div class="admin-grid-2col" style="margin-bottom: 0;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">رابط عند الضغط على الإعلان (اختياري)</label>
                                <input type="text" name="announcement_link" value="{{ old('announcement_link', $settings['announcement_link'] ?? '') }}" class="form-control" placeholder="/shop?offers=1" dir="ltr" style="text-align: right;">
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">لون خلفية الشريط</label>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <input type="color" name="announcement_bg" value="{{ old('announcement_bg', $settings['announcement_bg'] ?? '#141312') }}" style="width: 50px; height: 40px; border: none; cursor: pointer;">
                                    <span style="font-family: monospace; font-size: 0.9rem;">{{ $settings['announcement_bg'] ?? '#141312' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About Us Text Card -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">محتوى صفحة "من نحن"</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">النص التعريفي لمتجر خيوط دعجاء</label>
                            <textarea name="about_text" rows="7" class="form-control">{{ old('about_text', $settings['about_text'] ?? '') }}</textarea>
                            <small style="color: var(--admin-text-muted); font-size: 0.8rem; margin-top: 6px; display: block;">
                                هذا النص يظهر في صفحة "من نحن" وفي قسم القصة في الصفحة الرئيسية.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Contact and Channels Card -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">بيانات التواصل وخدمة العملاء</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-grid-2col">
                            <div class="form-group">
                                <label class="form-label">رقم WhatsApp للتواصل المباشر</label>
                                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '966500000000') }}" class="form-control" dir="ltr" style="text-align: right;">
                            </div>

                            <div class="form-group">
                                <label class="form-label">رقم الهاتف الرسمي</label>
                                <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '+966 50 000 0000') }}" class="form-control" dir="ltr" style="text-align: right;">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">البريد الإلكتروني الرسمي</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? 'info@dajaabaya.sa') }}" class="form-control" dir="ltr" style="text-align: right;">
                        </div>
                    </div>
                </div>

                <!-- Social Media Links Card -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">حسابات التواصل الاجتماعي (تظهر فقط عند إدخال الرابط)</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label class="form-label"><i class="fa-brands fa-snapchat" style="color: #FFFC00; background: #000; padding: 2px 4px; border-radius: 4px;"></i> رابط حساب Snapchat:</label>
                            <input type="url" name="snapchat_url" value="{{ old('snapchat_url', $settings['snapchat_url'] ?? '') }}" class="form-control" placeholder="https://snapchat.com/add/..." dir="ltr" style="text-align: right;">
                        </div>

                        <div class="form-group">
                            <label class="form-label"><i class="fa-brands fa-tiktok"></i> رابط حساب TikTok:</label>
                            <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}" class="form-control" placeholder="https://tiktok.com/@..." dir="ltr" style="text-align: right;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label"><i class="fa-brands fa-instagram" style="color: #E1306C;"></i> رابط حساب Instagram:</label>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" class="form-control" placeholder="https://instagram.com/..." dir="ltr" style="text-align: right;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Shipping & Theme Colors -->
            <div>
                <!-- Save Button Card -->
                <div class="admin-card">
                    <div class="admin-card-body">
                        <button type="submit" class="btn btn-gold btn-lg" style="width: 100%;">
                            <i class="fa-solid fa-floppy-disk"></i> حفظ كافة الإعدادات
                        </button>
                    </div>
                </div>

                <!-- Shipping Fees Card -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">إعدادات الشحن والتوصيل</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label class="form-label">تكلفة الشحن الافتراضية (ر.س)</label>
                            <input type="number" step="0.01" name="shipping_cost" value="{{ old('shipping_cost', $settings['shipping_cost'] ?? 25) }}" class="form-control">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">الحد الأدنى للشحن المجاني (ر.س)</label>
                            <input type="number" step="0.01" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? 350) }}" class="form-control">
                            <small style="color: var(--admin-text-muted); font-size: 0.8rem; display: block; margin-top: 4px;">
                                إذا كان مجموع السلة يساوي أو يتجاوز هذا المبلغ، يصبح الشحن مجانياً تلقائياً.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Theme Colors Card -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">ألوان الهوية الرئيسية للمتجر</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label class="form-label">اللون الرئيسي الفاخر (Primary) - النصوص والأزرار الداكنة</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <input type="color" id="primaryColorPicker" value="{{ old('primary_color', $settings['primary_color'] ?? '#1A1817') }}" style="width: 50px; height: 42px; border: 1px solid var(--admin-border); border-radius: 6px; cursor: pointer; padding: 2px;">
                                <input type="text" name="primary_color" id="primaryColorInput" value="{{ old('primary_color', $settings['primary_color'] ?? '#1A1817') }}" class="form-control" style="font-family: monospace; max-width: 140px; text-transform: uppercase;" dir="ltr">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 12px;">
                            <label class="form-label">اللون الثانوي التميزي (Accent Gold) - أزرار الشراء والأسعار والترويسة</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <input type="color" id="accentColorPicker" value="{{ old('accent_color', $settings['accent_color'] ?? '#BFA175') }}" style="width: 50px; height: 42px; border: 1px solid var(--admin-border); border-radius: 6px; cursor: pointer; padding: 2px;">
                                <input type="text" name="accent_color" id="accentColorInput" value="{{ old('accent_color', $settings['accent_color'] ?? '#BFA175') }}" class="form-control" style="font-family: monospace; max-width: 140px; text-transform: uppercase;" dir="ltr">
                            </div>
                        </div>

                        <!-- Live Color Preview Box -->
                        <div style="background: #FAF8F5; border: 1px solid var(--admin-border); border-radius: 8px; padding: 14px;">
                            <div style="font-size: 0.8rem; color: var(--admin-text-muted); margin-bottom: 8px; font-weight: 600;">معاينة فورية لتطبيق الألوان:</div>
                            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                                <button type="button" id="previewAccentBtn" class="btn btn-sm" style="background-color: {{ $settings['accent_color'] ?? '#BFA175' }}; color: #fff; border: none; font-size: 0.82rem; padding: 6px 14px; border-radius: 4px; pointer-events: none;">زر الشراء الرئيسي</button>
                                <button type="button" id="previewPrimaryBtn" class="btn btn-sm" style="background-color: {{ $settings['primary_color'] ?? '#1A1817' }}; color: #fff; border: none; font-size: 0.82rem; padding: 6px 14px; border-radius: 4px; pointer-events: none;">زر داكن</button>
                                <span id="previewBadge" style="background-color: {{ $settings['accent_color'] ?? '#BFA175' }}; color: #fff; font-size: 0.75rem; padding: 3px 8px; border-radius: 12px; font-weight: 700;">خصم 20%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logo & Favicon Upload -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">تغيير الشعار والأيقونة</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label class="form-label">شعار المتجر (Logo)</label>
                            <input type="file" name="site_logo" accept="image/*" class="form-control">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">أيقونة المتصفح (Favicon)</label>
                            <input type="file" name="site_favicon" accept="image/*" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        const primaryPicker = document.getElementById('primaryColorPicker');
        const primaryInput = document.getElementById('primaryColorInput');
        const accentPicker = document.getElementById('accentColorPicker');
        const accentInput = document.getElementById('accentColorInput');
        const previewAccentBtn = document.getElementById('previewAccentBtn');
        const previewPrimaryBtn = document.getElementById('previewPrimaryBtn');
        const previewBadge = document.getElementById('previewBadge');

        function updatePrimary() {
            if (primaryInput && primaryPicker) {
                primaryInput.value = primaryPicker.value;
                if (previewPrimaryBtn) previewPrimaryBtn.style.backgroundColor = primaryPicker.value;
            }
        }

        function updateAccent() {
            if (accentInput && accentPicker) {
                accentInput.value = accentPicker.value;
                if (previewAccentBtn) previewAccentBtn.style.backgroundColor = accentPicker.value;
                if (previewBadge) previewBadge.style.backgroundColor = accentPicker.value;
            }
        }

        if (primaryPicker) primaryPicker.addEventListener('input', updatePrimary);
        if (primaryInput) primaryInput.addEventListener('input', () => {
            if (/^#[0-9A-Fa-f]{6}$/.test(primaryInput.value)) {
                primaryPicker.value = primaryInput.value;
                if (previewPrimaryBtn) previewPrimaryBtn.style.backgroundColor = primaryInput.value;
            }
        });

        if (accentPicker) accentPicker.addEventListener('input', updateAccent);
        if (accentInput) accentInput.addEventListener('input', () => {
            if (/^#[0-9A-Fa-f]{6}$/.test(accentInput.value)) {
                accentPicker.value = accentInput.value;
                if (previewAccentBtn) previewAccentBtn.style.backgroundColor = accentInput.value;
                if (previewBadge) previewBadge.style.backgroundColor = accentInput.value;
            }
        });
    </script>

@endsection
