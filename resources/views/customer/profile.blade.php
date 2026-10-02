@extends('layouts.app')

@section('title', 'الملف الشخصي | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 30px 0;">
        <div class="container">
            <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                حسابي الشخصي
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">
                أهلاً بكِ، {{ $user->name }}! يمكنكِ إدارة معلوماتكِ ومتابعة طلباتكِ هنا
            </p>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="profile-layout-grid">
                
                <!-- Profile Edit Form -->
                <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 30px; box-shadow: var(--shadow-soft);">
                    <div style="text-align: center; margin-bottom: 24px;">
                        <div style="width: 72px; height: 72px; margin: 0 auto 12px; border-radius: 50%; background: #FAF8F5; border: 2px solid var(--border-gold); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 700; color: var(--brand-gold-dark);">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary);">{{ $user->name }}</h3>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $user->email }}</div>
                    </div>

                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">رقم الجوال</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required dir="ltr" style="text-align: right;">
                        </div>

                        <div class="form-group">
                            <label class="form-label">تغيير كلمة المرور (اختياري)</label>
                            <div class="password-input-wrap">
                                <input type="password" name="password" class="form-control" placeholder="اتركيها فارغة إن لم ترغبي بالتغيير">
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility(this)" title="إظهار / إخفاء كلمة المرور" aria-label="إظهار كلمة المرور">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <div class="password-input-wrap">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="تأكيد كلمة المرور">
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility(this)" title="إظهار / إخفاء كلمة المرور" aria-label="إظهار كلمة المرور">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gold" style="width: 100%;">
                            حفظ التعديلات
                        </button>
                    </form>
                </div>

                <!-- Recent Orders -->
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--text-primary);">
                            آخر الطلبات
                        </h2>
                        <a href="{{ route('orders.index') }}" style="color: var(--brand-gold-dark); font-size: 0.9rem; font-weight: 600;">
                            عرض جميع الطلبات &larr;
                        </a>
                    </div>

                    @if($recentOrders->count() > 0)
                        <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-soft);">
                            <table style="width: 100%; border-collapse: collapse; text-align: right; font-size: 0.9rem;">
                                <thead style="background: #FAF8F5; border-bottom: 1px solid var(--border-light); color: var(--text-muted);">
                                    <tr>
                                        <th style="padding: 14px 18px;">رقم الطلب</th>
                                        <th style="padding: 14px 18px;">التاريخ</th>
                                        <th style="padding: 14px 18px;">الإجمالي</th>
                                        <th style="padding: 14px 18px;">الحالة</th>
                                        <th style="padding: 14px 18px; text-align: center;">إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr style="border-bottom: 1px solid var(--border-light);">
                                            <td style="padding: 14px 18px; font-weight: 700;">{{ $order->order_number }}</td>
                                            <td style="padding: 14px 18px; color: var(--text-muted);">{{ $order->created_at->format('Y/m/d') }}</td>
                                            <td style="padding: 14px 18px; font-weight: 600;">{{ number_format($order->total, 0) }} ر.س</td>
                                            <td style="padding: 14px 18px;">
                                                @php $st = $order->status_info; @endphp
                                                <span class="badge-status {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }}" style="padding: 3px 10px; font-size: 0.78rem; border: 1px solid;">
                                                    {{ $st['label'] }}
                                                </span>
                                            </td>
                                            <td style="padding: 14px 18px; text-align: center;">
                                                <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-outline-gold btn-sm" style="padding: 4px 10px; font-size: 0.8rem;">
                                                    الفاتورة
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 40px; text-align: center; color: var(--text-muted);">
                            <p style="margin-bottom: 16px;">لم تقومي بعمل أي طلب حتى الآن.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-gold btn-sm">تسوقي الآن</a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

@endsection
