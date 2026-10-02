@extends('layouts.admin')

@section('title', 'تعديل البنر | خيوط دعجاء')
@section('page_title', 'تعديل البنر')

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
            <h3 class="admin-card-title">تعديل بيانات البنر</h3>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">الصورة الحالية:</label>
                    <div style="margin-bottom: 10px;">
                        <img src="{{ $banner->image_url }}" alt="بنر" style="max-width: 240px; border-radius: 6px; border: 1px solid var(--admin-border);">
                    </div>
                    <label class="form-label">تغيير صورة البنر (اختياري)</label>
                    <input type="file" name="image" accept="image/*" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">الشارة أو النص الصغير العلوي</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text', $banner->badge_text) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">العنوان الرئيسي للبنر</label>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">النص الفرعي / الوصف</label>
                    <textarea name="subtitle" rows="2" class="form-control">{{ old('subtitle', $banner->subtitle) }}</textarea>
                </div>

                <div class="admin-grid-2col">
                    <div class="form-group">
                        <label class="form-label">نص الزر</label>
                        <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">رابط الزر</label>
                        <input type="text" name="button_url" value="{{ old('button_url', $banner->button_url) }}" class="form-control" dir="ltr" style="text-align: right;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" class="form-control" style="width: 120px;">
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                        <span>تفعيل البنر فوراً في الصفحة الرئيسية</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-gold btn-lg" style="margin-top: 10px;">
                    <i class="fa-solid fa-check"></i> حفظ تعديلات البنر
                </button>
            </form>
        </div>
    </div>

@endsection
