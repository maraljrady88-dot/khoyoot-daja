@extends('layouts.app')

@section('title', 'تتبع حالة الطلب | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 30px 0;">
        <div class="container">
            <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                تتبع حالة الطلب
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">
                تابعي مسار عبايتكِ من لحظة التجهيز حتى وصولها إلى عتبة منزلكِ
            </p>
        </div>
    </div>

    <section class="section">
        <div class="container" style="max-width: 800px;">
            
            <!-- Lookup Form -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 30px; margin-bottom: 36px; box-shadow: var(--shadow-soft);">
                <form action="{{ route('orders.track') }}" method="GET">
                    <div class="form-grid-3col" style="align-items: flex-end;">
                        <div>
                            <label class="form-label">رقم الطلب (مثال: DJ-2026...)</label>
                            <input type="text" name="order_number" value="{{ request('order_number') }}" required class="form-control" placeholder="أدخلي رقم الطلب">
                        </div>
                        <div>
                            <label class="form-label">رقم الجوال المسجل</label>
                            <input type="tel" name="phone" value="{{ request('phone') }}" required class="form-control" placeholder="05XXXXXXXX" dir="ltr" style="text-align: right;">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-gold" style="height: 46px; width: 100%;">
                                <i class="fa-solid fa-magnifying-glass"></i> تتبع الآن
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Result Display -->
            @if($order)
                <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 36px; box-shadow: var(--shadow-card);">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border-light); flex-wrap: wrap; gap: 12px;">
                        <div>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">رقم الطلب:</span>
                            <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--brand-primary);">{{ $order->order_number }}</h2>
                        </div>
                        <div>
                            @php $stInfo = $order->status_info; @endphp
                            <span class="badge-status" style="background: #FAF8F5; border: 1px solid #DFC8A8; color: var(--brand-primary); font-size: 0.9rem; padding: 6px 16px;">
                                الحالة الحالية: <strong>{{ $stInfo['label'] }}</strong>
                            </span>
                        </div>
                    </div>

                    <!-- Visual Timeline -->
                    @php
                        $steps = [
                            'new' => 'تم استلام الطلب',
                            'processing' => 'قيد المعالجة',
                            'ready' => 'تم التجهيز',
                            'shipped' => 'تم الشحن',
                            'delivered' => 'تم التسليم بنجاح',
                        ];
                        $statusKeys = array_keys($steps);
                        $currentIdx = array_search($order->status, $statusKeys);
                        if ($currentIdx === false && in_array($order->status, ['cancelled', 'refunded'])) {
                            $isCancelled = true;
                        } else {
                            $isCancelled = false;
                        }
                    @endphp

                    @if(!$isCancelled)
                        <div style="margin: 40px 0 30px; overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; padding-bottom: 10px;">
                            <div style="display: grid; grid-template-columns: repeat(5, 1fr); min-width: 480px; text-align: center; position: relative;">
                                @foreach($steps as $key => $label)
                                    @php
                                        $stepIdx = array_search($key, $statusKeys);
                                        $isDone = ($currentIdx !== false && $stepIdx <= $currentIdx);
                                        $isCurrent = ($key === $order->status);
                                    @endphp
                                    <div style="position: relative; z-index: 2;">
                                        <div style="width: 38px; height: 38px; margin: 0 auto 10px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; {{ $isDone ? 'background: #BFA175; color: #FFFFFF;' : 'background: #F3EDE3; color: #8E867F;' }} {{ $isCurrent ? 'box-shadow: 0 0 0 4px rgba(191, 161, 117, 0.25);' : '' }}">
                                            @if($isDone)
                                                <i class="fa-solid fa-check"></i>
                                            @else
                                                <span>{{ $loop->iteration }}</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 0.78rem; font-weight: {{ $isDone ? '700' : '500' }}; color: {{ $isDone ? 'var(--text-primary)' : 'var(--text-muted)' }};">
                                            {{ $label }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="alert alert-error" style="margin-bottom: 24px;">
                            <i class="fa-solid fa-circle-xmark"></i>
                            <span>حالة هذا الطلب: <strong>{{ $order->status_info['label'] }}</strong>. يرجى التواصل مع الدعم لمزيد من الاستفسار.</span>
                        </div>
                    @endif

                    @if($order->tracking_number)
                        <div style="background: #FAF8F5; border: 1px solid var(--border-light); border-radius: 8px; padding: 14px 20px; margin-bottom: 24px; font-size: 0.9rem;">
                            <strong>رقم بوليصة الشحن والتتبع:</strong> <span dir="ltr" style="font-family: monospace; font-size: 1rem; font-weight: 700; color: #BFA175; margin-right: 8px;">{{ $order->tracking_number }}</span>
                        </div>
                    @endif

                    <!-- Items Summary -->
                    <div style="border: 1px solid var(--border-light); border-radius: 8px; overflow: hidden; margin-top: 24px;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
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
                                        <td style="padding: 12px 14px;">{{ $item->product_name }} @if($item->size) (مقاس {{ $item->size }}) @endif</td>
                                        <td style="padding: 12px 14px; text-align: center;">{{ $item->quantity }}</td>
                                        <td style="padding: 12px 14px; text-align: left; font-weight: 600;">{{ number_format($item->subtotal, 0) }} ر.س</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background: #FAF8F5; font-weight: 700;">
                                <tr>
                                    <td colspan="2" style="padding: 12px 14px; text-align: right;">الإجمالي مع الشحن:</td>
                                    <td style="padding: 12px 14px; text-align: left; color: var(--brand-primary); font-size: 1.05rem;">{{ number_format($order->total, 0) }} ر.س</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            @endif

        </div>
    </section>

@endsection
