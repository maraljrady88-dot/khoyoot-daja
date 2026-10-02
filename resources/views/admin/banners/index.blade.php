@extends('layouts.admin')

@section('title', 'إدارة البنرات والواجهة | خيوط دعجاء')
@section('page_title', 'إدارة بنرات الصفحة الرئيسية')

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--admin-text-main);">
            بنرات الهيرو والواجهة ({{ $banners->count() }})
        </h2>

        <a href="{{ route('admin.banners.create') }}" class="btn btn-gold btn-sm">
            <i class="fa-solid fa-plus"></i> إضافة بنر جديد
        </a>
    </div>

    <div class="admin-card">
        @if($banners->count() > 0)
            <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">معاينة البنر</th>
                        <th>العنوان</th>
                        <th>النص والوصف</th>
                        <th>نص الزر والرابط</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th style="text-align: center;">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                        <tr>
                            <td>
                                <img src="{{ $banner->image_url }}" alt="بنر" style="width: 130px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid var(--admin-border);">
                            </td>
                            <td>
                                <strong>{{ $banner->title ?: 'بدون عنوان' }}</strong>
                                @if($banner->badge_text)
                                    <div style="font-size: 0.75rem; color: var(--admin-gold);">{{ $banner->badge_text }}</div>
                                @endif
                            </td>
                            <td style="font-size: 0.85rem; color: var(--admin-text-muted);">
                                {{ $banner->subtitle ?: '-' }}
                            </td>
                            <td style="font-size: 0.85rem;">
                                @if($banner->button_text)
                                    <span class="badge" style="background: #FAF8F5; border: 1px solid var(--admin-border);">{{ $banner->button_text }}</span>
                                    <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 2px;">{{ $banner->button_url }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $banner->sort_order }}</td>
                            <td>
                                <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                                        @if($banner->is_active)
                                            <span class="badge-status" style="background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0;">مفعل</span>
                                        @else
                                            <span class="badge-status" style="background: #F3F4F6; color: #6B7280; border: 1px solid #E5E7EB;">معطل</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-outline-gold btn-sm" style="padding: 4px 10px; font-size: 0.8rem;" title="تعديل">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('حذف هذا البنر نهائياً؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background: #FEE2E2; color: #DC2626; border: none; padding: 4px 10px; font-size: 0.8rem;" title="حذف">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @else
            <div style="padding: 50px 20px; text-align: center; color: var(--admin-text-muted);">
                لا توجد بنرات حالياً.
            </div>
        @endif
    </div>

@endsection
