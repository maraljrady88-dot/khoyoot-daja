@extends('layouts.admin')

@section('title', 'إضافة عباية جديدة | خيوط دعجاء')
@section('page_title', 'إضافة عباية جديدة')

@section('content')

    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.products.index') }}" style="color: var(--admin-gold); font-size: 0.9rem; font-weight: 600;">
            &larr; العودة لقائمة العبايات
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="list-style: none; margin: 0; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="admin-grid-main-side">
            
            <!-- Main Details Column -->
            <div>
                <!-- Basic Info Card -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">معلومات العباية الأساسية</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label class="form-label">اسم العباية *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="مثال: عباية ملكية بتطريز قصب ذهبي">
                        </div>

                        <div class="admin-grid-2col">
                            <div class="form-group">
                                <label class="form-label">التصنيف *</label>
                                <select name="category_id" required class="form-control">
                                    <option value="">اختاري التصنيف...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">كود الموديل (SKU - اختياري، يُولد تلقائياً)</label>
                                <input type="text" name="sku" value="{{ old('sku') }}" class="form-control" placeholder="DJ-XXXXX">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">وصف مختصر (يظهر في البطاقات والمشاركات)</label>
                            <textarea name="short_description" rows="2" class="form-control" placeholder="وصف موجز من سطرين...">{{ old('short_description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">الوصف الكامل والمفصل</label>
                            <textarea name="description" rows="5" class="form-control" placeholder="وصف كامل ومفصل لقصة وتفاصيل العباية...">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">مواصفات القماش والقصة والطرحة</label>
                            <textarea name="details" rows="4" class="form-control" placeholder="• نوع القماش: كريب ملكي أصلي&#10;• القصة: كلوش انسيابي&#10;• القفلة: طقطق مخفي&#10;• الطرحة: شاملة طرحة سادة">{{ old('details') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Price and Inventory Card -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">الأسعار والمخزون</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-grid-2col">
                            <div class="form-group">
                                <label class="form-label">سعر البيع الحالي (ر.س) *</label>
                                <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="form-control" placeholder="380">
                            </div>

                            <div class="form-group">
                                <label class="form-label">السعر قبل الخصم إن وجد (ر.س)</label>
                                <input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price') }}" class="form-control" placeholder="480">
                            </div>
                        </div>

                        <div class="admin-grid-2col" style="margin-bottom: 0;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">الكمية المتوفرة في المخزون *</label>
                                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 10) }}" required min="0" class="form-control">
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">تنبيه انخفاض المخزون (عند وصولها لـ)</label>
                                <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 3) }}" min="1" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sizes and Colors Card -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">المقاسات والألوان المتاحة</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label class="form-label">المقاسات المتاحة للطلب:</label>
                            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                @foreach(['50', '52', '54', '56', '58', '60', '62'] as $sizeVal)
                                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 6px 12px; background: #FAF8F5; border: 1px solid var(--admin-border); border-radius: 6px;">
                                        <input type="checkbox" name="sizes[]" value="{{ $sizeVal }}" {{ in_array($sizeVal, old('sizes', ['52', '54', '56', '58', '60'])) ? 'checked' : '' }}>
                                        <span>مقاس {{ $sizeVal }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">الألوان المتاحة (مفصولة بفاصلة):</label>
                            <input type="text" name="colors" value="{{ old('colors', 'أسود ملكي') }}" class="form-control" placeholder="مثال: أسود ملكي, كحلي فاخر, بني كشميري">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Media & Publish Column -->
            <div>
                <!-- Publish Control -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">خيارات النشر والظهور</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <strong>نشر العباية في المتجر فوراً</strong>
                            </label>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <span>تمييز العباية (الأكثر طلباً بالرئيسية)</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-gold btn-lg" style="width: 100%;">
                            <i class="fa-solid fa-cloud-arrow-up"></i> حفظ ونشر العباية
                        </button>
                    </div>
                </div>

                <!-- Primary Image Upload -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">الصورة الرئيسية للعباية *</h3>
                    </div>
                    <div class="admin-card-body">
                        <p style="font-size: 0.8rem; color: var(--admin-text-muted); margin-bottom: 12px;">
                            نظام الصور الذكي يحافظ على أبعاد ونسب صورتكِ كاملة دون قص (Aspect Ratio Preserved).
                        </p>
                        <input type="file" name="primary_image" accept="image/*" class="form-control" onchange="previewImage(this, 'primaryPreview')">
                        
                        <div id="primaryPreview" style="margin-top: 14px; width: 100%; aspect-ratio: 3/4; background: #F6F3ED; border: 1px dashed var(--admin-border); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <span style="color: #9CA3AF; font-size: 0.85rem;">معاينة الصورة ستظهر هنا</span>
                        </div>
                    </div>
                </div>

                <!-- Additional Gallery Images Upload -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">معرض الصور الإضافية</h3>
                    </div>
                    <div class="admin-card-body">
                        <p style="font-size: 0.8rem; color: var(--admin-text-muted); margin-bottom: 12px;">
                            يمكنكِ رفع صور متعددة للعباية (تفاصيل الأكمام، الظهر، النقش).
                        </p>
                        <input type="file" name="images[]" multiple accept="image/*" class="form-control">
                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@endsection
