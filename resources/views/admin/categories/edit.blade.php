@extends('layouts.admin')

@section('title', 'تعديل التصنيف: ' . $category->name . ' | خيوط دعجاء')
@section('page_title', 'تعديل بيانات التصنيف')

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
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-card" style="max-width: 600px;">
        <div class="admin-card-header">
            <h3 class="admin-card-title">تعديل التصنيف: {{ $category->name }}</h3>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">اسم التصنيف *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">وصف القسم</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">ترتيب الظهور</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="form-control" style="width: 120px;">
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <span>تفعيل وظهور التصنيف بالمتجر</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-gold btn-lg" style="margin-top: 10px;">
                    <i class="fa-solid fa-check"></i> حفظ التعديلات
                </button>
            </form>
        </div>
    </div>

@endsection
