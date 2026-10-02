@extends('layouts.admin')

@section('title', 'إدارة العملاء | خيوط دعجاء')
@section('page_title', 'إدارة العملاء والمسجلين')

@section('content')

    <div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 10px; padding: 18px 20px; margin-bottom: 24px;">
        <form action="{{ route('admin.customers.index') }}" method="GET" style="display: flex; gap: 12px; align-items: center;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث باسم العميل، البريد، أو رقم الجوال..." class="form-control" style="width: 320px; padding: 8px 14px; font-size: 0.88rem;">
            <button type="submit" class="btn btn-outline-dark btn-sm">بحث</button>
            @if(request()->filled('q'))
                <a href="{{ route('admin.customers.index') }}" style="font-size: 0.82rem; color: #DC2626;">إلغاء البحث</a>
            @endif
        </form>
    </div>

    <div class="admin-card">
        @if($customers->count() > 0)
            <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>رقم الجوال</th>
                        <th>البريد الإلكتروني</th>
                        <th>عدد الطلبات</th>
                        <th>تاريخ التسجيل</th>
                        <th>حالة الحساب</th>
                        <th style="text-align: center;">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>
                                <strong>{{ $customer->name }}</strong>
                            </td>
                            <td dir="ltr" style="text-align: right;">{{ $customer->phone }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                <span class="badge" style="background: #FAF8F5; border: 1px solid var(--admin-border);">
                                    {{ $customer->orders_count }} طلب
                                </span>
                            </td>
                            <td style="font-size: 0.8rem; color: var(--admin-text-muted);">
                                {{ $customer->created_at->format('Y/m/d') }}
                            </td>
                            <td>
                                <form action="{{ route('admin.customers.toggle', $customer->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                                        @if($customer->is_active)
                                            <span class="badge-status" style="background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0;">نشط</span>
                                        @else
                                            <span class="badge-status" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FECACA;">معطل</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-outline-gold btn-sm" style="padding: 4px 10px; font-size: 0.8rem;" title="ملف العميل">
                                        <i class="fa-solid fa-user"></i> الملف
                                    </a>

                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('هل أنتِ متأكدة من حذف حساب العميل ({{ $customer->name }}) نهائياً؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background: #FEE2E2; color: #DC2626; border: none; padding: 4px 10px; font-size: 0.8rem;" title="حذف الحساب نهائياً">
                                            <i class="fa-solid fa-trash-can"></i> حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <div style="padding: 16px 20px;">
                {{ $customers->links() }}
            </div>
        @else
            <div style="padding: 60px 20px; text-align: center; color: var(--admin-text-muted);">
                <i class="fa-solid fa-users" style="font-size: 3rem; color: #DFC8A8; margin-bottom: 12px;"></i>
                <p style="font-size: 1.1rem; color: var(--admin-text-main); font-weight: 600;">لا يوجد عملاء مطابقين للبحث</p>
            </div>
        @endif
    </div>

@endsection
