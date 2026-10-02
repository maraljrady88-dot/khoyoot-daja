@extends('layouts.admin')

@section('title', 'إضافة تصنيف جديد | خيوط دعجاء')
@section('page_title', 'إضافة تصنيف جديد')

@section('content')

    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.categories.index') }}" style="color: var(--admin-gold); font-size: 0.9rem; font-weight: 600;">
            &larr; العودة لقائمة التصنيفات
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

    <div class="admin-card" style="max-width: 600px;">
        <div class="admin-card-header">
            <h3 class="admin-card-title">بيانات التصنيف</h3>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">اسم التصنيف *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="مثال: عبايات فاخرة للمناسبات">
                </div>

                <div class="form-group">
                    <label class="form-label">وصف القسم (اختياري)</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="وصف موجز عن هذا القسم...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">ترتيب الظهور</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control" style="width: 120px;">
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>تفعيل وظهور التصنيف بالمتجر</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-gold btn-lg" style="margin-top: 10px;">
                    <i class="fa-solid fa-check"></i> حفظ التصنيف
                </button>
            </form>
        </div>
    </div>

@endsection
