@extends('layouts.admin')

@section('title', 'إضافة بنر جديد | خيوط دعجاء')
@section('page_title', 'إضافة بنر جديد')

@section('content')

    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.banners.index') }}" style="color: var(--admin-gold); font-size: 0.9rem; font-weight: 600;">
            &larr; العودة لقائمة البنرات
        </a>
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

    <div class="admin-card" style="max-width: 650px;">
        <div class="admin-card-header">
            <h3 class="admin-card-title">بيانات البنر</h3>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">صورة البنر *</label>
                    <input type="file" name="image" accept="image/*" required class="form-control">
                    <small style="color: var(--admin-text-muted); font-size: 0.8rem;">يفضل استخدام أبعاد عريضة 16:9 وبدقة عالية.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">الشارة أو النص الصغير العلوي</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text') }}" class="form-control" placeholder="مثال: تشكيلة الموسم الجديدة">
                </div>

                <div class="form-group">
                    <label class="form-label">العنوان الرئيسي للبنر</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="مثال: خيوط دعجاء للعبايات">
                </div>

                <div class="form-group">
                    <label class="form-label">النص الفرعي / الوصف</label>
                    <textarea name="subtitle" rows="2" class="form-control" placeholder="مثال: حيث تلتقي الأصالة بالفخامة...">{{ old('subtitle') }}</textarea>
                </div>

                <div class="admin-grid-2col">
                    <div class="form-group">
                        <label class="form-label">نص الزر</label>
                        <input type="text" name="button_text" value="{{ old('button_text', 'تسوقي الآن') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">رابط الزر</label>
                        <input type="text" name="button_url" value="{{ old('button_url', '/shop') }}" class="form-control" dir="ltr" style="text-align: right;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 1) }}" class="form-control" style="width: 120px;">
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>تفعيل البنر فوراً في الصفحة الرئيسية</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-gold btn-lg" style="margin-top: 10px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> رفع وحفظ البنر
                </button>
            </form>
        </div>
    </div>

@endsection
