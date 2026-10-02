@extends('layouts.admin')

@section('title', 'إدارة التصنيفات | خيوط دعجاء')
@section('page_title', 'إدارة التصنيفات والأقسام')

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--admin-text-main);">
            أقسام وتصنيفات العبايات ({{ $categories->count() }})
        </h2>

        <a href="{{ route('admin.categories.create') }}" class="btn btn-gold btn-sm">
            <i class="fa-solid fa-plus"></i> إضافة تصنيف جديد
        </a>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>اسم التصنيف</th>
                    <th>الاسم اللطيف (Slug)</th>
                    <th>عدد العبايات</th>
                    <th>الترتيب</th>
                    <th>الحالة</th>
                    <th style="text-align: center;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                            @if($category->description)
                                <div style="font-size: 0.78rem; color: var(--admin-text-muted);">{{ $category->description }}</div>
                            @endif
                        </td>
                        <td style="font-family: monospace; color: var(--admin-text-muted);">{{ $category->slug }}</td>
                        <td>
                            <span class="badge" style="background: #FAF8F5; border: 1px solid var(--admin-border); color: var(--admin-text-main);">
                                {{ $category->products_count }} عباية
                            </span>
                        </td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            <form action="{{ route('admin.categories.toggle', $category->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background: none; border: none; cursor: pointer;">
                                    @if($category->is_active)
                                        <span class="badge-status" style="background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0;">نشط</span>
                                    @else
                                        <span class="badge-status" style="background: #F3F4F6; color: #6B7280; border: 1px solid #E5E7EB;">مخفي</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-gold btn-sm" style="padding: 4px 10px; font-size: 0.8rem;" title="تعديل">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('هل أنتِ متأكدة من حذف هذا التصنيف؟');">
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
    </div>

@endsection
