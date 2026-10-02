@extends('layouts.app')

@section('title', 'طلباتي | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 30px 0;">
        <div class="container">
            <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                قائمة طلباتي
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">
                تتبعي سجل مشترياتكِ وطلباتكِ السابقة في متجر خيوط دعجاء
            </p>
        </div>
    </div>

    <section class="section">
        <div class="container">
            @if($orders->count() > 0)
                <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-soft);">
                    <table style="width: 100%; border-collapse: collapse; text-align: right;">
                        <thead style="background: #FAF8F5; border-bottom: 1px solid var(--border-light); font-size: 0.85rem; color: var(--text-muted);">
                            <tr>
                                <th style="padding: 16px 20px;">رقم الطلب</th>
                                <th style="padding: 16px 20px;">التاريخ</th>
                                <th style="padding: 16px 20px;">عدد المنتجات</th>
                                <th style="padding: 16px 20px;">الإجمالي</th>
                                <th style="padding: 16px 20px;">حالة الطلب</th>
                                <th style="padding: 16px 20px; text-align: center;">التفاصيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr style="border-bottom: 1px solid var(--border-light);">
                                    <td style="padding: 18px 20px; font-weight: 700; color: var(--brand-primary);">
                                        {{ $order->order_number }}
                                    </td>
                                    <td style="padding: 18px 20px; font-size: 0.88rem; color: var(--text-muted);">
                                        {{ $order->created_at->format('Y/m/d') }}
                                    </td>
                                    <td style="padding: 18px 20px; font-size: 0.9rem;">
                                        {{ $order->items->sum('quantity') }} عباية
                                    </td>
                                    <td style="padding: 18px 20px; font-weight: 700; color: var(--text-primary);">
                                        {{ number_format($order->total, 0) }} ر.س
                                    </td>
                                    <td style="padding: 18px 20px;">
                                        @php $st = $order->status_info; @endphp
                                        <span class="badge-status {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }}" style="padding: 4px 12px; font-size: 0.8rem; border: 1px solid;">
                                            {{ $st['label'] }}
                                        </span>
                                    </td>
                                    <td style="padding: 18px 20px; text-align: center;">
                                        <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-outline-gold btn-sm">
                                            عرض الفاتورة
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="padding: 16px 20px;">
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 60px 20px; text-align: center; max-width: 600px; margin: 0 auto;">
                    <i class="fa-solid fa-box-open" style="font-size: 3rem; color: #DFC8A8; margin-bottom: 16px;"></i>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">لا توجد طلبات سابقة حتى الآن</h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 24px;">ابدئي تجربة تسوق فريدة واختاري عبايتكِ القادمة</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-gold">تصفح المتجر</a>
                </div>
            @endif
        </div>
    </section>

@endsection
