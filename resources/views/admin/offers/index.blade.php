@extends('layouts.admin')

@section('title', 'إدارة العروض والخصومات | خيوط دعجاء')
@section('page_title', 'إدارة العروض والخصومات')

@section('content')

    <div class="admin-grid-offers">
        
        <!-- Add / Apply Offer Form -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">تطبيق عرض أو خصم على عباية</h3>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.offers.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">اختاري العباية *</label>
                        <select name="product_id" required class="form-control" onchange="updatePriceFields(this)">
                            <option value="">اختاري من القائمة...</option>
                            @foreach($allProducts as $p)
                                <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-old="{{ $p->compare_at_price }}">
                                    {{ $p->name }} ({{ number_format($p->price, 0) }} ر.س)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">السعر الأصلي قبل الخصم (ر.س) *</label>
                        <input type="number" step="0.01" id="originalPriceInput" name="original_price" required class="form-control" placeholder="مثال: 550">
                    </div>

                    <div class="form-group">
                        <label class="form-label">السعر المخفض الجديد (ر.س) *</label>
                        <input type="number" step="0.01" id="discountPriceInput" name="discount_price" required class="form-control" placeholder="مثال: 420">
                    </div>

                    <button type="submit" class="btn btn-gold btn-lg" style="width: 100%; margin-top: 10px;">
                        <i class="fa-solid fa-tag"></i> تطبيق وتفعيل العرض
                    </button>
                </form>
            </div>
        </div>

        <!-- Current Active Discounts Table -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">العبايات المطبق عليها خصومات حالياً ({{ $discountedProducts->total() }})</h3>
            </div>

            @if($discountedProducts->count() > 0)
                <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">الصورة</th>
                            <th>العباية</th>
                            <th>السعر قبل الخصم</th>
                            <th>السعر المخفض</th>
                            <th>نسبة الخصم</th>
                            <th style="text-align: center;">إلغاء الخصم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($discountedProducts as $product)
                            <tr>
                                <td>
                                    <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="table-img-thumb">
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <div style="font-size: 0.75rem; color: var(--admin-text-muted);">{{ $product->category->name }}</div>
                                </td>
                                <td style="color: #6B7280; text-decoration: line-through;">
                                    {{ number_format($product->compare_at_price, 0) }} ر.س
                                </td>
                                <td style="font-weight: 700; color: #059669;">
                                    {{ number_format($product->price, 0) }} ر.س
                                </td>
                                <td>
                                    <span class="badge" style="background: #FEE2E2; color: #DC2626; font-size: 0.8rem;">
                                        خصم {{ $product->discount_percent }}%
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <form action="{{ route('admin.offers.remove', $product->id) }}" method="POST" onsubmit="return confirm('إلغاء الخصم واستعادة السعر الأصلي؟');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm" style="background: #FEE2E2; color: #DC2626; border: none; font-size: 0.8rem;">
                                            إلغاء العرض
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

                <div style="padding: 16px 20px;">
                    {{ $discountedProducts->links() }}
                </div>
            @else
                <div style="padding: 50px 20px; text-align: center; color: var(--admin-text-muted);">
                    لا توجد عروض أو خصومات نشطة حالياً.
                </div>
            @endif
        </div>

    </div>

    <script>
        function updatePriceFields(select) {
            const opt = select.options[select.selectedIndex];
            if (opt && opt.dataset.price) {
                const curPrice = parseFloat(opt.dataset.price);
                const oldPrice = opt.dataset.old ? parseFloat(opt.dataset.old) : (curPrice * 1.25);
                document.getElementById('originalPriceInput').value = oldPrice.toFixed(0);
                document.getElementById('discountPriceInput').value = curPrice.toFixed(0);
            }
        }
    </script>

@endsection
