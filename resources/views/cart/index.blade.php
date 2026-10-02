@extends('layouts.app')

@section('title', 'سلة التسوق | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 30px 0;">
        <div class="container">
            <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                سلة التسوق
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">
                راجعي اختياراتكِ واستكملي طلبكِ بكل سهولة وأمان
            </p>
        </div>
    </div>

    <section class="section">
        <div class="container">
            @if(count($items) > 0)

                <!-- Free Shipping Progress Notice -->
                @if($subtotal < $freeShippingThreshold)
                    <div style="background: #F7F1E7; border: 1px solid #DFC8A8; border-radius: var(--radius-sm); padding: 14px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-size: 0.92rem; color: #785A34;">
                        <i class="fa-solid fa-truck" style="font-size: 1.2rem; color: #BFA175;"></i>
                        <span>
                            أضيفي منتجات بقيمة <strong>{{ number_format($freeShippingThreshold - $subtotal, 0) }} ر.س</strong> إضافية للحصول على <strong>شحن مجاني</strong> لكافة مدن المملكة!
                        </span>
                    </div>
                @else
                    <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: var(--radius-sm); padding: 14px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-size: 0.92rem; color: #065F46;">
                        <i class="fa-solid fa-circle-check" style="font-size: 1.2rem; color: #059669;"></i>
                        <span>
                            مبروك! لقد حصلتِ على <strong>شحن مجاني</strong> لهذا الطلب.
                        </span>
                    </div>
                @endif

                <div class="cart-layout-grid">
                    
                    <!-- Cart Items Table/List -->
                    <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-soft);">
                        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%;">
                        <table style="width: 100%; border-collapse: collapse; text-align: right; min-width: 500px;">
                            <thead>
                                <tr style="background-color: #FAF8F5; border-bottom: 1px solid var(--border-light); font-size: 0.88rem; color: var(--text-muted);">
                                    <th style="padding: 16px 20px;">العباية</th>
                                    <th style="padding: 16px 20px;">السعر</th>
                                    <th style="padding: 16px 20px;">الكمية</th>
                                    <th style="padding: 16px 20px;">الإجمالي</th>
                                    <th style="padding: 16px 20px; text-align: center;">إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr style="border-bottom: 1px solid var(--border-light);">
                                        <!-- Product Info -->
                                        <td style="padding: 20px;">
                                            <div style="display: flex; align-items: center; gap: 16px;">
                                                <div style="width: 70px; height: 90px; background: #F6F3ED; border-radius: 6px; border: 1px solid var(--border-light); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                                    <img src="{{ $item['product']->main_image_url }}" alt="{{ $item['product']->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                                <div>
                                                    <h4 style="font-size: 0.98rem; font-weight: 600; margin-bottom: 4px;">
                                                        <a href="{{ route('products.show', $item['product']->slug) }}" style="color: var(--text-primary);">
                                                            {{ $item['product']->name }}
                                                        </a>
                                                    </h4>
                                                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                                                        @if($item['size'])
                                                            <span>المقاس: <strong>{{ $item['size'] }}</strong></span>
                                                        @endif
                                                        @if($item['color'])
                                                            <span style="margin-right: 8px;">اللون: <strong>{{ $item['color'] }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Price -->
                                        <td style="padding: 20px; font-weight: 600; color: var(--text-primary); white-space: nowrap;">
                                            {{ number_format($item['product']->price, 0) }} ر.س
                                        </td>

                                        <!-- Quantity Form -->
                                        <td style="padding: 20px;">
                                            <form action="{{ route('cart.update', $item['key']) }}" method="POST" style="display: flex; align-items: center; gap: 6px;">
                                                @csrf
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock_quantity }}" style="width: 60px; padding: 6px 10px; border: 1px solid var(--border-light); border-radius: 4px; text-align: center; font-weight: 600;" onchange="this.form.submit()">
                                            </form>
                                        </td>

                                        <!-- Subtotal -->
                                        <td style="padding: 20px; font-weight: 700; color: var(--text-primary); white-space: nowrap;">
                                            {{ number_format($item['subtotal'], 0) }} ر.س
                                        </td>

                                        <!-- Remove Action -->
                                        <td style="padding: 20px; text-align: center;">
                                            <form action="{{ route('cart.remove', $item['key']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background: none; border: none; color: #DC2626; cursor: pointer; font-size: 1.1rem;" title="حذف العباية من السلة">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>

                        <div style="padding: 16px 20px; background-color: #FAF8F5; display: flex; justify-content: space-between; align-items: center;">
                            <a href="{{ route('shop.index') }}" style="color: var(--brand-gold-dark); font-size: 0.9rem; font-weight: 600;">
                                &larr; متابعة التسوق وإضافة عبايات أخرى
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: #8E867F; font-size: 0.85rem; cursor: pointer;">
                                    إفراغ كامل السلة
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary Box -->
                    <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 26px; box-shadow: var(--shadow-soft); position: sticky; top: 104px;">
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light);">
                            ملخص الطلب
                        </h3>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.95rem; color: var(--text-secondary);">
                            <span>المجموع الفرعي:</span>
                            <span style="font-weight: 600; color: var(--text-primary);">{{ number_format($subtotal, 0) }} ر.س</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 18px; font-size: 0.95rem; color: var(--text-secondary);">
                            <span>تكلفة التوصيل:</span>
                            <span style="font-weight: 600; {{ $shippingCost == 0 ? 'color: #059669;' : 'color: var(--text-primary);' }}">
                                @if($shippingCost == 0)
                                    مجاناً
                                @else
                                    {{ number_format($shippingCost, 0) }} ر.س
                                @endif
                            </span>
                        </div>

                        <div style="border-top: 1px dashed var(--border-light); padding-top: 16px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: baseline;">
                            <span style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">المجموع الكلي:</span>
                            <span style="font-size: 1.6rem; font-weight: 700; color: var(--brand-primary);">
                                {{ number_format($total, 0) }} <small style="font-size: 0.9rem; color: var(--brand-gold-dark);">ر.س</small>
                            </span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-gold btn-lg" style="width: 100%; margin-bottom: 14px;">
                            <i class="fa-solid fa-lock"></i> إتمام الطلب الآن
                        </a>

                        <div style="text-align: center; font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <i class="fa-solid fa-shield-halved" style="color: #059669;"></i>
                            تسوق آمن ومضمون 100% داخل المملكة العربية السعودية
                        </div>
                    </div>

                </div>

            @else
                <!-- Empty Cart State -->
                <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 70px 20px; text-align: center; max-width: 600px; margin: 0 auto;">
                    <div style="width: 80px; height: 80px; margin: 0 auto 20px; border-radius: 50%; background: #FAF8F5; border: 1px solid var(--border-gold); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: var(--brand-gold-dark);">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <h2 style="font-size: 1.4rem; font-weight: 700; color: var(--text-primary); margin-bottom: 10px;">
                        سلة التسوق فارغة حالياً
                    </h2>
                    <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 28px; line-height: 1.7;">
                        لم تقومي بإضافة أي عباية إلى سلتكِ بعد. اكتشفي مجموعتنا الفاخرة من العبايات الخليجية والتصاميم الحصرية.
                    </p>
                    <a href="{{ route('shop.index') }}" class="btn btn-gold btn-lg">
                        تصفح العبايات الآن
                    </a>
                </div>
            @endif
        </div>
    </section>

@endsection
