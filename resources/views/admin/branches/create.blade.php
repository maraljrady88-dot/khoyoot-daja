@extends('layouts.admin')

@section('title', 'إضافة فرع جديد | خيوط دعجاء')
@section('page_title', 'إضافة فرع جديد')

@section('content')

    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.branches.index') }}" style="color: var(--admin-gold); font-size: 0.9rem; font-weight: 600;">
            &larr; العودة لقائمة الفروع
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
            <h3 class="admin-card-title">بيانات الفرع</h3>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.branches.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">اسم الفرع *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="مثال: الطائف الدولي / حفر الباطن – لوريت سنتر">
                </div>

                <div class="form-group">
                    <label class="form-label">المدينة *</label>
                    <input type="text" name="city" value="{{ old('city') }}" required class="form-control" placeholder="مثال: الطائف">
                </div>

                <div class="form-group">
                    <label class="form-label">العنوان التفصيلي *</label>
                    <textarea name="address" rows="2" required class="form-control" placeholder="مثال: مجمع الطائف الدولي - طريق الملك خالد...">{{ old('address') }}</textarea>
                </div>

                <div class="admin-grid-2col">
                    <div class="form-group">
                        <label class="form-label">رقم الهاتف للتواصل</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="05XXXXXXXX" dir="ltr" style="text-align: right;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">أوقات وساعات العمل</label>
                        <input type="text" name="working_hours" value="{{ old('working_hours', 'يومياً من 4:00 عصراً حتى 11:00 مساءً') }}" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">رابط الموقع على Google Maps</label>
                    <input type="url" name="google_maps_url" value="{{ old('google_maps_url') }}" class="form-control" placeholder="https://maps.google.com/..." dir="ltr" style="text-align: right;">
                </div>

                <div class="form-group">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 1) }}" class="form-control" style="width: 120px;">
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>تفعيل وظهور الفرع بالموقع</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-gold btn-lg" style="margin-top: 10px;">
                    <i class="fa-solid fa-check"></i> حفظ الفرع
                </button>
            </form>
        </div>
    </div>

@endsection
