@extends('layouts.admin')

@section('title', 'تفاصيل الطلب: ' . $order->order_number . ' | خيوط دعجاء')
@section('page_title', 'تفاصيل وإدارة الطلب')

@section('content')

    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('admin.orders.index') }}" style="color: var(--admin-gold); font-size: 0.9rem; font-weight: 600;">
            &larr; العودة لقائمة الطلبات
        </a>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('orders.show', $order->order_number) }}" target="_blank" class="btn btn-outline-dark btn-sm">
                <i class="fa-solid fa-print"></i> عرض الفاتورة وطباعتها
            </a>
            @if($order->customer_phone)
                @php $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone); @endphp
                <a href="https://wa.me/{{ str_starts_with($cleanPhone, '05') ? '966' . substr($cleanPhone, 1) : $cleanPhone }}?text={{ urlencode("مرحباً أختي الكريمة {$order->customer_name}، نتواصل معكِ من متجر خيوط دعجاء للعبايات الخليجية بخصوص طلبكِ رقم ({$order->order_number}).") }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="btn btn-outline-gold btn-sm">
                    <i class="fa-brands fa-whatsapp"></i> مراسلة العميل عبر واتساب
                </a>
            @endif
        </div>
    </div>

    <div class="admin-grid-main-side">
        
        <!-- Left Column: Items and Customer Info -->
        <div>
            <!-- Order Items -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">المنتجات المطلوبة ({{ $order->items->count() }})</h3>
                </div>
                <div class="admin-card-body" style="padding: 0;">
                    <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>العباية</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>المجموع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            @if($item->product)
                                                <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product_name }}" class="table-img-thumb" style="width: 40px; height: 52px;">
                                            @endif
                                            <div>
                                                <div style="font-weight: 600;">{{ $item->product_name }}</div>
                                                <div style="font-size: 0.78rem; color: var(--admin-text-muted);">
                                                    @if($item->size) مقاس: {{ $item->size }} @endif
                                                    @if($item->color) | لون: {{ $item->color }} @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->product_price, 0) }} ر.س</td>
                                    <td><strong>{{ $item->quantity }}</strong></td>
                                    <td style="font-weight: 700;">{{ number_format($item->subtotal, 0) }} ر.س</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: #FAF8F5; font-size: 0.92rem;">
                            <tr>
                                <td colspan="3" style="text-align: right; padding: 12px 18px;">المجموع الفرعي:</td>
                                <td style="padding: 12px 18px; font-weight: 600;">{{ number_format($order->subtotal, 0) }} ر.س</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right; padding: 12px 18px;">تكلفة الشحن:</td>
                                <td style="padding: 12px 18px; font-weight: 600;">{{ $order->shipping_cost == 0 ? 'شحن مجاني' : number_format($order->shipping_cost, 0) . ' ر.س' }}</td>
                            </tr>
                            <tr style="font-size: 1.1rem; font-weight: 700; color: var(--admin-text-main);">
                                <td colspan="3" style="text-align: right; padding: 14px 18px;">الإجمالي النهائي:</td>
                                <td style="padding: 14px 18px; color: var(--admin-gold-dark);">{{ number_format($order->total, 0) }} ر.س</td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>

            <!-- Customer and Delivery Details -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">بيانات العميل وعنوان الشحن والتوصيل</h3>
                </div>
                <div class="admin-card-body">
                    <div class="admin-grid-2col" style="gap: 20px; font-size: 0.92rem;">
                        <div>
                            <div style="color: var(--admin-text-muted); margin-bottom: 2px;">اسم العميل:</div>
                            <strong style="font-size: 1rem;">{{ $order->customer_name }}</strong>
                        </div>

                        <div>
                            <div style="color: var(--admin-text-muted); margin-bottom: 2px;">رقم الجوال:</div>
                            <strong dir="ltr" style="font-size: 1rem;">{{ $order->customer_phone }}</strong>
                        </div>

                        <div>
                            <div style="color: var(--admin-text-muted); margin-bottom: 2px;">البريد الإلكتروني:</div>
                            <div>{{ $order->customer_email ?: 'لم يُحدد' }}</div>
                        </div>

                        <div>
                            <div style="color: var(--admin-text-muted); margin-bottom: 2px;">طريقة الدفع:</div>
                            <strong>{{ $order->payment_method_name }}</strong>
                        </div>

                        <div>
                            <div style="color: var(--admin-text-muted); margin-bottom: 2px;">المدينة والحي:</div>
                            <strong>{{ $order->city }} - {{ $order->district }}</strong>
                        </div>

                        <div>
                            <div style="color: var(--admin-text-muted); margin-bottom: 2px;">العنوان بالتفصيل:</div>
                            <div>{{ $order->address }}</div>
                        </div>
                    </div>

                    @if($order->notes)
                        <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--admin-border);">
                            <div style="color: var(--admin-text-muted); margin-bottom: 2px; font-size: 0.85rem;">ملاحظات العميل:</div>
                            <div style="background: #FAF8F5; padding: 10px 14px; border-radius: 6px; font-size: 0.9rem;">
                                {{ $order->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Status Control -->
        <div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">تحديث حالة الطلب</h3>
                </div>
                <div class="admin-card-body">
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label">حالة الطلب الحالية:</label>
                            <select name="status" class="form-control" style="font-size: 1rem; font-weight: 600;">
                                @foreach($statuses as $stKey => $stData)
                                    <option value="{{ $stKey }}" {{ $order->status === $stKey ? 'selected' : '' }}>
                                        {{ $stData['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">رقم بوليصة الشحن والتتبع (SMSA / Aramex):</label>
                            <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="form-control" placeholder="مثال: SMSA-12345678">
                        </div>

                        <button type="submit" class="btn btn-gold btn-lg" style="width: 100%;">
                            <i class="fa-solid fa-arrows-rotate"></i> حفظ التحديث
                        </button>
                    </form>

                    <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--admin-border); font-size: 0.8rem; color: var(--admin-text-muted); line-height: 1.6;">
                        • عند تحويل الحالة إلى <strong>ملغي</strong>، سيتم تلقائياً إعادة كميات العبايات إلى المخزون.<br>
                        • عند إدخال رقم التتبع، سيظهر للعميلة في صفحة تتبع الطلب فوراً.
                    </div>
                </div>
            </div>

            <!-- Order Meta Box -->
            <div class="admin-card">
                <div class="admin-card-body" style="font-size: 0.85rem; color: var(--admin-text-muted);">
                    <div style="margin-bottom: 8px;">
                        <strong>رقم الطلب:</strong> {{ $order->order_number }}
                    </div>
                    <div style="margin-bottom: 8px;">
                        <strong>تاريخ الإنشاء:</strong> {{ $order->created_at->format('Y-m-d H:i') }}
                    </div>
                    <div>
                        <strong>آخر تحديث:</strong> {{ $order->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
