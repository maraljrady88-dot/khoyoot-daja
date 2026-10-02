@extends('layouts.admin')

@section('title', 'ملف العميل: ' . $customer->name . ' | خيوط دعجاء')
@section('page_title', 'ملف وسجل العميل')

@section('content')

    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.customers.index') }}" style="color: var(--admin-gold); font-size: 0.9rem; font-weight: 600;">
            &larr; العودة لقائمة العملاء
        </a>
    </div>

    <!-- Customer Overview Card -->
    <div class="admin-grid-customer" style="margin-bottom: 28px;">
        <div class="admin-card">
            <div class="admin-card-body" style="text-align: center;">
                <div style="width: 70px; height: 70px; margin: 0 auto 14px; border-radius: 50%; background: #FAF8F5; border: 2px solid var(--admin-border); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 700; color: var(--admin-gold);">
                    {{ mb_substr($customer->name, 0, 1) }}
                </div>
                <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--admin-text-main);">{{ $customer->name }}</h3>
                <div style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 16px;">{{ $customer->email }}</div>

                <div style="text-align: right; border-top: 1px solid var(--admin-border); padding-top: 16px; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px;">
                    <div><strong>الجوال:</strong> <span dir="ltr">{{ $customer->phone }}</span></div>
                    <div><strong>تاريخ التسجيل:</strong> {{ $customer->created_at->format('Y-m-d') }}</div>
                    <div>
                        <strong>حالة الحساب:</strong>
                        @if($customer->is_active)
                            <span class="badge" style="background: #ECFDF5; color: #059669;">نشط</span>
                        @else
                            <span class="badge" style="background: #FEE2E2; color: #DC2626;">معطل</span>
                        @endif
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 20px;">
                    <form action="{{ route('admin.customers.toggle', $customer->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $customer->is_active ? 'btn-outline-dark' : 'btn-gold' }}" style="width: 100%;">
                            {{ $customer->is_active ? 'تعطيل حساب العميل' : 'تفعيل الحساب' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('هل أنتِ متأكدة من حذف حساب العميل ({{ $customer->name }}) نهائياً؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="width: 100%; background: #FEE2E2; color: #DC2626; border: 1px solid #FECACA;">
                            <i class="fa-solid fa-trash-can"></i> حذف الحساب نهائياً
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Customer Spending & Stats -->
        <div>
            <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 24px;">
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>إجمالي مشتريات العميل</h4>
                        <div class="stat-value" style="color: var(--admin-gold-dark);">
                            {{ number_format($totalSpent, 0) }} <small style="font-size: 0.85rem;">ر.س</small>
                        </div>
                    </div>
                    <div class="stat-icon gold">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h4>إجمالي عدد الطلبات</h4>
                        <div class="stat-value">{{ $customer->orders->count() }}</div>
                    </div>
                    <div class="stat-icon blue">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                </div>
            </div>

            <!-- Customer Orders List -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">طلبات العميل السابقة</h3>
                </div>
                @if($customer->orders->count() > 0)
                    <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>التاريخ</th>
                                <th>المجموع</th>
                                <th>الحالة</th>
                                <th>إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders as $order)
                                <tr>
                                    <td style="font-weight: 700;">{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('Y/m/d') }}</td>
                                    <td style="font-weight: 600;">{{ number_format($order->total, 0) }} ر.س</td>
                                    <td>
                                        @php $st = $order->status_info; @endphp
                                        <span class="badge-status {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }}" style="border: 1px solid;">
                                            {{ $st['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-gold btn-sm" style="padding: 4px 10px; font-size: 0.8rem;">
                                            التفاصيل
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                @else
                    <div style="padding: 30px; text-align: center; color: var(--admin-text-muted);">
                        لا توجد طلبات مسجلة لهذا العميل حتى الآن.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
