@extends('layouts.app')

@section('title', 'إتمام الطلب | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 30px 0;">
        <div class="container">
            <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                إتمام الطلب
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">
                أدخلي عنوان التوصيل واختاري طريقة الدفع المفضلة لديكِ
            </p>
        </div>
    </div>

    <section class="section">
        <div class="container">
            
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="list-style: none; margin: 0; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="checkout-layout-grid">
                    
                    <!-- Form Fields Column -->
                    <div>
                        <!-- Customer Info Box -->
                        <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 28px; margin-bottom: 24px; box-shadow: var(--shadow-soft);">
                            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; gap: 8px;">
                                <i class="fa-regular fa-user" style="color: var(--brand-gold);"></i>
                                بيانات العميل والتواصل
                            </h3>

                            <div class="form-grid-2col">
                                <div class="form-group">
                                    <label class="form-label">الاسم الكامل *</label>
                                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $user->name ?? '') }}" required placeholder="مثال: نورة محمد">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">رقم الجوال السعودي *</label>
                                    <input type="tel" name="customer_phone" class="form-control" value="{{ old('customer_phone', $user->phone ?? '') }}" required placeholder="05XXXXXXXX" dir="ltr" style="text-align: right;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">البريد الإلكتروني (اختياري - لاستلام الفاتورة)</label>
                                <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', $user->email ?? '') }}" placeholder="name@domain.com" dir="ltr" style="text-align: right;">
                            </div>
                        </div>

                        <!-- Delivery Address Box -->
                        <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 28px; margin-bottom: 24px; box-shadow: var(--shadow-soft);">
                            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-location-dot" style="color: var(--brand-gold);"></i>
                                عنوان التوصيل داخل المملكة العربية السعودية
                            </h3>

                            <div class="form-grid-2col">
                                <div class="form-group">
                                    <label class="form-label">المدينة *</label>
                                    <select name="city" class="form-control" required>
                                        <option value="">اختاري مدينتكِ...</option>
                                        @foreach($saudiCities as $city)
                                            <option value="{{ $city }}" {{ old('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">الحي *</label>
                                    <input type="text" name="district" class="form-control" value="{{ old('district') }}" required placeholder="مثال: حي النرجس / حي العليا">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">العنوان بالتفصيل ورقم المبنى أو الشارع *</label>
                                <textarea name="address" rows="2" class="form-control" required placeholder="مثال: شارع عثمان بن عفان، فيلا رقم 12، بجانب مسجد...">{{ old('address') }}</textarea>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">ملاحظات إضافية للطلب أو شركة التوصيل (اختياري)</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="مثال: يرجى الاتصال قبل الوصول بنصف ساعة">
                            </div>
                        </div>

                        <!-- Payment Methods Box -->
                        <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 28px; box-shadow: var(--shadow-soft);">
                            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; gap: 8px;">
                                <i class="fa-regular fa-credit-card" style="color: var(--brand-gold);"></i>
                                طريقة الدفع
                            </h3>

                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; border: 2px solid var(--border-gold); background: #FAF8F5; border-radius: 8px; cursor: pointer;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <input type="radio" name="payment_method" value="cod" checked>
                                        <div>
                                            <strong style="display: block; font-size: 0.95rem; color: var(--text-primary);">الدفع عند الاستلام (COD)</strong>
                                            <span style="font-size: 0.8rem; color: var(--text-muted);">ادفعي نقداً أو عبر الشبكة عند استلام العباية</span>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-hand-holding-dollar" style="font-size: 1.4rem; color: var(--brand-gold-dark);"></i>
                                </label>

                                <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; border: 1px solid var(--border-light); border-radius: 8px; cursor: pointer;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <input type="radio" name="payment_method" value="card">
                                        <div>
                                            <strong style="display: block; font-size: 0.95rem; color: var(--text-primary);">بطاقة مدى / فيزا / ماستركارد</strong>
                                            <span style="font-size: 0.8rem; color: var(--text-muted);">دفع إلكتروني فوري وآمن مشفر 100%</span>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 6px; font-size: 1.4rem; color: var(--text-secondary);">
                                        <i class="fa-brands fa-cc-visa"></i>
                                        <i class="fa-brands fa-cc-mastercard"></i>
                                    </div>
                                </label>

                                <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; border: 1px solid var(--border-light); border-radius: 8px; cursor: pointer;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <input type="radio" name="payment_method" value="bank_transfer">
                                        <div>
                                            <strong style="display: block; font-size: 0.95rem; color: var(--text-primary);">تحويل بنكي مباشر (حساب الراجحي / الأهلي)</strong>
                                            <span style="font-size: 0.8rem; color: var(--text-muted);">سيتم تزويدكِ برقم الحساب والآيبان فور تأكيد الطلب</span>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-building-columns" style="font-size: 1.3rem; color: var(--brand-gold-dark);"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Box -->
                    <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 26px; box-shadow: var(--shadow-soft); position: sticky; top: 104px;">
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light);">
                            المنتجات في الطلب ({{ count($items) }})
                        </h3>

                        <!-- Items list -->
                        <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 20px; max-height: 280px; overflow-y: auto; padding-left: 6px;">
                            @foreach($items as $item)
                                <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 50px; height: 65px; background: #F6F3ED; border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                            <img src="{{ $item['product']->main_image_url }}" alt="{{ $item['product']->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                        <div>
                                            <div style="font-size: 0.88rem; font-weight: 600; color: var(--text-primary); line-height: 1.3;">
                                                {{ $item['product']->name }}
                                            </div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                الكمية: {{ $item['quantity'] }} @if($item['size']) | مقاس {{ $item['size'] }} @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div style="font-size: 0.9rem; font-weight: 700; color: var(--text-primary); white-space: nowrap;">
                                        {{ number_format($item['subtotal'], 0) }} ر.س
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Calculations -->
                        <div style="border-top: 1px solid var(--border-light); padding-top: 16px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.9rem; color: var(--text-secondary);">
                                <span>المجموع الفرعي:</span>
                                <span style="font-weight: 600; color: var(--text-primary);">{{ number_format($subtotal, 0) }} ر.س</span>
                            </div>

                            <div style="display: flex; justify-content: space-between; margin-bottom: 14px; font-size: 0.9rem; color: var(--text-secondary);">
                                <span>الشحن والتوصيل:</span>
                                <span style="font-weight: 600; {{ $shippingCost == 0 ? 'color: #059669;' : 'color: var(--text-primary);' }}">
                                    @if($shippingCost == 0)
                                        مجاناً
                                    @else
                                        {{ number_format($shippingCost, 0) }} ر.س
                                    @endif
                                </span>
                            </div>

                            <div style="border-top: 1px dashed var(--border-light); padding-top: 14px; margin-bottom: 22px; display: flex; justify-content: space-between; align-items: baseline;">
                                <span style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary);">الإجمالي النهائي:</span>
                                <span style="font-size: 1.55rem; font-weight: 700; color: var(--brand-primary);">
                                    {{ number_format($total, 0) }} <small style="font-size: 0.85rem; color: var(--brand-gold-dark);">ر.س</small>
                                </span>
                            </div>

                            <button type="submit" class="btn btn-gold btn-lg" style="width: 100%; margin-bottom: 14px;">
                                <i class="fa-solid fa-check"></i> تأكيد وإرسال الطلب
                            </button>

                            <div style="text-align: center; font-size: 0.78rem; color: var(--text-muted); line-height: 1.5;">
                                بالضغط على تأكيد الطلب، فإنكِ توافقين على شروط الاستخدام وسياسة التوصيل الخاصة بمتجر خيوط دعجاء.
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </section>

@endsection
