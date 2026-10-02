@extends('layouts.admin')

@section('title', 'إدارة الطلبات والمبيعات | خيوط دعجاء')
@section('page_title', 'إدارة الطلبات والمبيعات')

@section('content')

    <!-- Search & Filter Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 10px; padding: 18px 20px; margin-bottom: 24px;">
        <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث برقم الطلب، اسم العميل، الجوال..." class="form-control" style="width: 280px; padding: 8px 14px; font-size: 0.88rem;">
            
            <select name="status" onchange="this.form.submit()" class="form-control" style="width: 180px; padding: 8px 14px; font-size: 0.88rem;">
                <option value="">جميع الحالات</option>
                @foreach($statuses as $key => $st)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $st['label'] }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-outline-dark btn-sm">تصفية</button>
            
            @if(request()->hasAny(['q', 'status']))
                <a href="{{ route('admin.orders.index') }}" style="font-size: 0.82rem; color: #DC2626;">إلغاء الفلتر</a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="admin-card">
        @if($orders->count() > 0)
            <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>رقم الجوال</th>
                        <th>المدينة</th>
                        <th>الإجمالي</th>
                        <th>طريقة الدفع</th>
                        <th>الحالة</th>
                        <th>تاريخ الطلب</th>
                        <th style="text-align: center;">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td style="font-weight: 700; color: var(--admin-text-main);">
                                <a href="{{ route('admin.orders.show', $order->id) }}" style="color: inherit;">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>{{ $order->customer_name }}</td>
                            <td dir="ltr" style="text-align: right; font-size: 0.85rem;">{{ $order->customer_phone }}</td>
                            <td>{{ $order->city }}</td>
                            <td style="font-weight: 700;">{{ number_format($order->total, 0) }} ر.س</td>
                            <td style="font-size: 0.82rem;">{{ $order->payment_method_name }}</td>
                            <td>
                                @php $st = $order->status_info; @endphp
                                <span class="badge-status {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }}" style="border: 1px solid; font-size: 0.78rem;">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                            <td style="font-size: 0.8rem; color: var(--admin-text-muted);">
                                {{ $order->created_at->format('Y/m/d') }}
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-gold btn-sm" style="padding: 4px 12px; font-size: 0.8rem;">
                                    معاينة وتغيير الحالة
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <div style="padding: 16px 20px;">
                {{ $orders->links() }}
            </div>
        @else
            <div style="padding: 60px 20px; text-align: center; color: var(--admin-text-muted);">
                <i class="fa-solid fa-box" style="font-size: 3rem; color: #DFC8A8; margin-bottom: 12px;"></i>
                <p style="font-size: 1.1rem; color: var(--admin-text-main); font-weight: 600;">لا توجد طلبات تطابق معايير البحث</p>
            </div>
        @endif
    </div>

@endsection
