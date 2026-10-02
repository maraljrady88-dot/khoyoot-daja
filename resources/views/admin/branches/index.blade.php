@extends('layouts.admin')

@section('title', 'إدارة فروع المتجر | خيوط دعجاء')
@section('page_title', 'إدارة فروع خيوط دعجاء')

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--admin-text-main);">
            فروع خيوط دعجاء في المملكة ({{ $branches->count() }})
        </h2>

        <a href="{{ route('admin.branches.create') }}" class="btn btn-gold btn-sm">
            <i class="fa-solid fa-plus"></i> إضافة فرع جديد
        </a>
    </div>

    <div class="admin-card">
        @if($branches->count() > 0)
            <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>اسم الفرع</th>
                        <th>المدينة</th>
                        <th>العنوان</th>
                        <th>رقم الهاتف</th>
                        <th>ساعات العمل</th>
                        <th>الحالة</th>
                        <th style="text-align: center;">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                        <tr>
                            <td>
                                <strong>{{ $branch->name }}</strong>
                            </td>
                            <td>{{ $branch->city }}</td>
                            <td style="max-width: 250px; font-size: 0.85rem;">{{ $branch->address }}</td>
                            <td dir="ltr" style="text-align: right; font-size: 0.85rem;">{{ $branch->phone ?: '-' }}</td>
                            <td style="font-size: 0.85rem;">{{ $branch->working_hours ?: '-' }}</td>
                            <td>
                                <form action="{{ route('admin.branches.toggle', $branch->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                                        @if($branch->is_active)
                                            <span class="badge-status" style="background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0;">مفعل</span>
                                        @else
                                            <span class="badge-status" style="background: #F3F4F6; color: #6B7280; border: 1px solid #E5E7EB;">معطل</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('admin.branches.edit', $branch->id) }}" class="btn btn-outline-gold btn-sm" style="padding: 4px 10px; font-size: 0.8rem;" title="تعديل">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('حذف هذا الفرع؟');">
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
                لا توجد فروع مسجلة حتى الآن.
            </div>
        @endif
    </div>

@endsection
