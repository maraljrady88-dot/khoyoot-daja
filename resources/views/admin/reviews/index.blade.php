@extends('layouts.admin')

@section('title', 'التقييمات والمراجعات | خيوط دعجاء')
@section('page_title', 'إدارة تقييمات ومراجعات العميلات ⭐')

@section('content')

    <!-- Filters Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 10px; padding: 18px 20px; margin-bottom: 24px;">
        <form action="{{ route('admin.reviews.index') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث باسم العميلة، نص التقييم، أو العباية..." class="form-control" style="width: 280px; padding: 8px 14px; font-size: 0.88rem;">
            
            <select name="rating" onchange="this.form.submit()" class="form-control" style="width: 160px; padding: 8px 14px; font-size: 0.88rem;">
                <option value="">جميع النجوم</option>
                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 نجوم)</option>
                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 نجوم)</option>
                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 نجوم)</option>
                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ (نجمتان)</option>
                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ (نجمة واحدة)</option>
            </select>

            <select name="status" onchange="this.form.submit()" class="form-control" style="width: 150px; padding: 8px 14px; font-size: 0.88rem;">
                <option value="">جميع الحالات</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>معتمد ومقبول</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>بانتظار المراجعة</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوض ومخفي</option>
            </select>

            <button type="submit" class="btn btn-outline-dark btn-sm">تصفية</button>
            
            @if(request()->hasAny(['q', 'rating', 'status']))
                <a href="{{ route('admin.reviews.index') }}" style="font-size: 0.82rem; color: #DC2626;">إلغاء الفلتر</a>
            @endif
        </form>
    </div>

    <!-- Reviews Table -->
    <div class="admin-card">
        @if($reviews->count() > 0)
            <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>العميلة</th>
                        <th>العباية</th>
                        <th>التقييم</th>
                        <th>نص المراجعة</th>
                        <th>صورة مرفقة</th>
                        <th>توثيق الشراء</th>
                        <th>الحالة</th>
                        <th>مميز للرئيسية</th>
                        <th style="text-align: center;">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td>
                                <strong>{{ $review->customer_name }}</strong>
                                <div style="font-size: 0.75rem; color: var(--admin-text-muted);">{{ $review->created_at->format('Y/m/d') }}</div>
                            </td>
                            <td>
                                @if($review->product)
                                    <a href="{{ route('products.show', $review->product->slug) }}" target="_blank" style="font-weight: 600; color: var(--admin-text-main);">
                                        {{ $review->product->name }}
                                    </a>
                                @else
                                    <span style="color: var(--admin-text-muted);">منتج محذوف</span>
                                @endif
                            </td>
                            <td style="white-space: nowrap; color: #F59E0B; font-size: 0.9rem;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                @endfor
                                <span style="font-weight: 700; color: var(--admin-text-main); margin-right: 4px;">{{ $review->rating }}</span>
                            </td>
                            <td style="max-width: 280px; font-size: 0.85rem; line-height: 1.6;">
                                "{{ $review->review_text }}"
                            </td>
                            <td>
                                @if($review->image_url)
                                    <img src="{{ $review->image_url }}" alt="صورة المراجعة" style="width: 48px; height: 48px; object-fit: cover; border-radius: 4px; border: 1px solid var(--admin-border); cursor: pointer;" onclick="window.open(this.src)">
                                @else
                                    <span style="color: var(--admin-text-muted); font-size: 0.8rem;">لا توجد</span>
                                @endif
                            </td>
                            <td>
                                @if($review->is_verified_purchase)
                                    <span class="badge" style="background: #ECFDF5; color: #059669; font-size: 0.75rem;">
                                        <i class="fa-solid fa-circle-check"></i> تم الشراء ✔
                                    </span>
                                @else
                                    <span style="color: var(--admin-text-muted); font-size: 0.75rem;">زائر</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.reviews.updateStatus', $review->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="form-control" style="width: auto; padding: 4px 8px; font-size: 0.78rem; {{ $review->status === 'approved' ? 'color: #059669; font-weight: 600;' : ($review->status === 'rejected' ? 'color: #DC2626;' : 'color: #D97706;') }}">
                                        <option value="approved" {{ $review->status === 'approved' ? 'selected' : '' }}>معتمد ومقبول</option>
                                        <option value="pending" {{ $review->status === 'pending' ? 'selected' : '' }}>معلق للمراجعة</option>
                                        <option value="rejected" {{ $review->status === 'rejected' ? 'selected' : '' }}>مرفوض ومخفي</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.reviews.toggleFeatured', $review->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer;" title="تبديل تمييز التقييم في الصفحة الرئيسية">
                                        @if($review->is_featured)
                                            <i class="fa-solid fa-star" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                        @else
                                            <i class="fa-regular fa-star" style="color: #D1D5DB; font-size: 1.1rem;"></i>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: center;">
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('هل أنتِ متأكدة من حذف هذا التقييم نهائياً؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background: #FEE2E2; color: #DC2626; border: none; padding: 4px 10px; font-size: 0.8rem;" title="حذف">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <div style="padding: 16px 20px;">
                {{ $reviews->links() }}
            </div>
        @else
            <div style="padding: 60px 20px; text-align: center; color: var(--admin-text-muted);">
                <i class="fa-regular fa-star" style="font-size: 3rem; color: #DFC8A8; margin-bottom: 12px;"></i>
                <p style="font-size: 1.1rem; color: var(--admin-text-main); font-weight: 600;">لا توجد تقييمات تطابق معايير البحث</p>
            </div>
        @endif
    </div>

@endsection
