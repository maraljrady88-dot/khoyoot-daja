@extends('layouts.app')

@section('title', 'تم تأكيد طلبكِ بنجاح | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <section class="section" style="padding: 60px 0;">
        <div class="container" style="max-width: 760px;">
            <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 40px; box-shadow: var(--shadow-card); text-align: center;">
                
                <div style="width: 80px; height: 80px; margin: 0 auto 20px; border-radius: 50%; background: #ECFDF5; border: 2px solid #10B981; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #059669;">
                    <i class="fa-solid fa-check"></i>
                </div>

                <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                    شكراً لكِ! تم استلام طلبكِ بنجاح
                </h1>
                <p style="font-size: 1rem; color: var(--text-muted); margin-bottom: 24px;">
                    فريق خيوط دعجاء يعمل الآن على تجهيز عبايتكِ بعناية تليق بكِ
                </p>

                <!-- Order Number Badge -->
                <div style="background: #FAF8F5; border: 1px dashed var(--border-gold); border-radius: 8px; padding: 18px; display: inline-block; margin-bottom: 30px; min-width: 300px;">
                    <span style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 4px;">رقم الطلب الخاص بكِ:</span>
                    <strong style="font-size: 1.5rem; color: var(--brand-primary); letter-spacing: 1px;">{{ $order->order_number }}</strong>
                </div>

                <!-- Order Details Summary -->
                <div style="text-align: right; border-top: 1px solid var(--border-light); padding-top: 24px; margin-bottom: 30px;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 16px;">
                        تفاصيل الطلب:
                    </h3>

                    <div class="form-grid-2col" style="gap: 14px; font-size: 0.92rem; margin-bottom: 24px;">
                        <div>
                            <span style="color: var(--text-muted);">اسم العميل:</span>
                            <strong style="color: var(--text-primary); margin-right: 6px;">{{ $order->customer_name }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">رقم الجوال:</span>
                            <strong style="color: var(--text-primary); margin-right: 6px;" dir="ltr">{{ $order->customer_phone }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">المدينة / الحي:</span>
                            <strong style="color: var(--text-primary); margin-right: 6px;">{{ $order->city }} - {{ $order->district }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">طريقة الدفع:</span>
                            <strong style="color: var(--text-primary); margin-right: 6px;">{{ $order->payment_method_name }}</strong>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div style="border: 1px solid var(--border-light); border-radius: 8px; overflow: hidden; margin-bottom: 20px;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                            <thead style="background: #FAF8F5; border-bottom: 1px solid var(--border-light);">
                                <tr>
                                    <th style="padding: 10px 14px; text-align: right;">المنتج</th>
                                    <th style="padding: 10px 14px; text-align: center;">الكمية</th>
                                    <th style="padding: 10px 14px; text-align: left;">المجموع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr style="border-bottom: 1px solid var(--border-light);">
                                        <td style="padding: 12px 14px;">
                                            {{ $item->product_name }}
                                            @if($item->size) <span style="font-size: 0.78rem; color: #8E867F;">(مقاس {{ $item->size }})</span> @endif
                                        </td>
                                        <td style="padding: 12px 14px; text-align: center;">{{ $item->quantity }}</td>
                                        <td style="padding: 12px 14px; text-align: left; font-weight: 600;">{{ number_format($item->subtotal, 0) }} ر.س</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background: #FAF8F5; font-weight: 700;">
                                <tr>
                                    <td colspan="2" style="padding: 12px 14px; text-align: right;">الإجمالي الكلي:</td>
                                    <td style="padding: 12px 14px; text-align: left; color: var(--brand-primary); font-size: 1.1rem;">
                                        {{ number_format($order->total, 0) }} ر.س
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Actions Buttons -->
                <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('orders.track', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}" class="btn btn-primary">
                        <i class="fa-solid fa-truck-fast"></i> تتبع حالة طلبكِ
                    </a>
                    
                    @php $waNum = \App\Models\Setting::get('whatsapp_number', '966500000000'); @endphp
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNum) }}?text={{ urlencode("مرحباً، أود الاستفسار عن طلبي في متجر خيوط دعجاء رقم ({$order->order_number})") }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="btn btn-outline-gold">
                        <i class="fa-brands fa-whatsapp"></i> تواصل عبر واتساب بخصوص الطلب
                    </a>

                    <a href="{{ route('home') }}" class="btn btn-outline-dark">
                        العودة للرئيسية
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection
