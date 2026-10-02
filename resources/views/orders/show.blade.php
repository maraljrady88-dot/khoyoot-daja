@extends('layouts.app')

@section('title', 'تفاصيل الطلب ' . $order->order_number . ' | خيوط دعجاء')

@section('content')

    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 30px 0;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <h1 style="font-size: 1.65rem; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">
                        تفاصيل الطلب: {{ $order->order_number }}
                    </h1>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">
                        تاريخ الطلب: {{ $order->created_at->format('Y/m/d - h:i A') }}
                    </span>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('orders.track', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}" class="btn btn-outline-gold btn-sm">
                        <i class="fa-solid fa-truck-fast"></i> تتبع الشحنة
                    </a>
                    <button type="button" onclick="window.print()" class="btn btn-outline-dark btn-sm">
                        <i class="fa-solid fa-print"></i> طباعة الفاتورة
                    </button>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container" style="max-width: 900px;">
            <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 36px; box-shadow: var(--shadow-card);">
                
                <!-- Invoice Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 24px; border-bottom: 2px solid var(--border-light); flex-wrap: wrap; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <img src="{{ asset('images/logo.webp') }}" alt="خيوط دعجاء" style="height: 50px; width: auto; object-fit: contain;">
                        <div>
                            <div style="font-size: 1.3rem; font-weight: 700; color: var(--brand-primary);">خيوط دعجاء للعبايات الخليجية</div>
                            <div style="font-size: 0.8rem; color: var(--brand-gold-dark);">فاتورة شراء رسمية</div>
                        </div>
                    </div>
                    <div>
                        @php $st = $order->status_info; @endphp
                        <span class="badge-status {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }}" style="padding: 6px 16px; font-size: 0.9rem; border: 1px solid;">
                            {{ $st['label'] }}
                        </span>
                    </div>
                </div>

                <!-- Customer & Address Grid -->
                <div class="form-grid-2col" style="gap: 24px; margin-bottom: 30px; background: #FAF8F5; border-radius: 8px; padding: 20px; font-size: 0.9rem;">
                    <div>
                        <strong style="display: block; font-size: 0.95rem; color: var(--brand-primary); margin-bottom: 8px;">بيانات العميل:</strong>
                        <div style="margin-bottom: 4px;">الاسم: {{ $order->customer_name }}</div>
                        <div style="margin-bottom: 4px;">الجوال: <span dir="ltr">{{ $order->customer_phone }}</span></div>
                        @if($order->customer_email)
                            <div>البريد: {{ $order->customer_email }}</div>
                        @endif
                    </div>
                    <div>
                        <strong style="display: block; font-size: 0.95rem; color: var(--brand-primary); margin-bottom: 8px;">عنوان التوصيل:</strong>
                        <div style="margin-bottom: 4px;">المدينة: {{ $order->city }} - {{ $order->district }}</div>
                        <div style="margin-bottom: 4px;">العنوان: {{ $order->address }}</div>
                        <div>طريقة الدفع: {{ $order->payment_method_name }}</div>
                    </div>
                </div>

                <!-- Items Table -->
                <div style="border: 1px solid var(--border-light); border-radius: 8px; overflow: hidden; margin-bottom: 30px;">
                    <div style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.92rem; text-align: right; min-width: 480px;">
                        <thead style="background: #FAF8F5; border-bottom: 1px solid var(--border-light);">
                            <tr>
                                <th style="padding: 12px 18px;">العباية</th>
                                <th style="padding: 12px 18px; text-align: center;">سعر الوحدة</th>
                                <th style="padding: 12px 18px; text-align: center;">الكمية</th>
                                <th style="padding: 12px 18px; text-align: left;">المجموع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr style="border-bottom: 1px solid var(--border-light);">
                                    <td style="padding: 14px 18px;">
                                        <strong>{{ $item->product_name }}</strong>
                                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                                            @if($item->size) مقاس: {{ $item->size }} @endif
                                            @if($item->color) | لون: {{ $item->color }} @endif
                                        </div>
                                    </td>
                                    <td style="padding: 14px 18px; text-align: center;">{{ number_format($item->product_price, 0) }} ر.س</td>
                                    <td style="padding: 14px 18px; text-align: center;">{{ $item->quantity }}</td>
                                    <td style="padding: 14px 18px; text-align: left; font-weight: 700;">{{ number_format($item->subtotal, 0) }} ر.س</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="font-size: 0.95rem;">
                            <tr>
                                <td colspan="3" style="padding: 12px 18px; text-align: right; color: var(--text-muted);">المجموع الفرعي:</td>
                                <td style="padding: 12px 18px; text-align: left; font-weight: 600;">{{ number_format($order->subtotal, 0) }} ر.س</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 12px 18px; text-align: right; color: var(--text-muted);">تكلفة التوصيل:</td>
                                <td style="padding: 12px 18px; text-align: left; font-weight: 600;">{{ $order->shipping_cost == 0 ? 'مجاناً' : number_format($order->shipping_cost, 0) . ' ر.س' }}</td>
                            </tr>
                            <tr style="background: #FAF8F5; font-size: 1.15rem; font-weight: 700;">
                                <td colspan="3" style="padding: 14px 18px; text-align: right; color: var(--brand-primary);">المجموع الكلي النهائي:</td>
                                <td style="padding: 14px 18px; text-align: left; color: var(--brand-primary);">{{ number_format($order->total, 0) }} ر.س</td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>

                @if($order->notes)
                    <div style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 24px;">
                        <strong>ملاحظات العميل:</strong> {{ $order->notes }}
                    </div>
                @endif

                <div style="text-align: center; border-top: 1px dashed var(--border-light); padding-top: 20px; font-size: 0.85rem; color: var(--text-muted);">
                    شكراً لاختياركِ <strong>خيوط دعجاء للعبايات الخليجية</strong>. فخورون بخدمتكِ دائماً!
                </div>

            </div>
        </div>
    </section>

@endsection
