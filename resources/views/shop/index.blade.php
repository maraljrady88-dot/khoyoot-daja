@extends('layouts.app')

@section('title', 'متجر العبايات | خيوط دعجاء للعبايات الخليجية')

@section('content')

    <!-- Breadcrumb & Header -->
    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 36px 0;">
        <div class="container">
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">
                <a href="{{ route('home') }}" style="color: var(--text-muted);">الرئيسية</a>
                <span style="margin: 0 6px;">/</span>
                <span style="color: var(--text-primary); font-weight: 500;">
                    @if($currentCategory)
                        {{ $currentCategory->name }}
                    @elseif(request()->filled('offers'))
                        عروض وتخفيضات الموسم
                    @elseif(request()->filled('q'))
                        نتائج البحث عن "{{ request('q') }}"
                    @else
                        جميع العبايات
                    @endif
                </span>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                <div>
                    <h1 style="font-size: 1.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">
                        @if($currentCategory)
                            {{ $currentCategory->name }}
                        @elseif(request()->filled('offers'))
                            عروض وتخفيضات خيوط دعجاء
                        @elseif(request()->filled('q'))
                            نتائج البحث عن: "{{ request('q') }}"
                        @else
                            تشكيلة عبايات خيوط دعجاء
                        @endif
                    </h1>
                    <p style="font-size: 0.9rem; color: var(--text-muted);">
                        عرض {{ $products->total() }} عباية بتصاميم وأقمشة خليجية منتقاة
                    </p>
                </div>

                <!-- Sorting Dropdown -->
                <form action="{{ route('shop.index') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
                    @if(request()->filled('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request()->filled('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    @if(request()->filled('offers'))
                        <input type="hidden" name="offers" value="{{ request('offers') }}">
                    @endif
                    @if(request()->filled('in_stock'))
                        <input type="hidden" name="in_stock" value="{{ request('in_stock') }}">
                    @endif

                    <label for="sortSelect" style="font-size: 0.85rem; color: var(--text-secondary); white-space: nowrap;">ترتيب حسب:</label>
                    <select id="sortSelect" name="sort" onchange="this.form.submit()" class="form-control" style="width: auto; padding: 8px 14px; font-size: 0.9rem;">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>الأحدث وصولاً</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                        <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                        <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>الأكثر طلباً ومشاهدة</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Shop Content with Filter Sidebar -->
    <div class="section">
        <div class="container">
            <button type="button" class="mobile-filter-btn" id="mobileFilterToggleBtn">
                <span><i class="fa-solid fa-sliders"></i> تصفية وفرز النتائج</span>
                <i class="fa-solid fa-chevron-down" id="mobileFilterChevron"></i>
            </button>

            <div class="shop-layout-container">
                
                <!-- Filters Sidebar -->
                <aside class="shop-filters-sidebar" id="shopFiltersSidebar">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between;">
                        <span>تصفية النتائج</span>
                        @if(request()->hasAny(['category', 'q', 'offers', 'in_stock', 'min_price', 'max_price']))
                            <a href="{{ route('shop.index') }}" style="font-size: 0.75rem; color: #DC2626; font-weight: normal;">إعادة ضبط</a>
                        @endif
                    </h3>

                    <!-- Categories Filter -->
                    <div style="margin-bottom: 24px;">
                        <h4 style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary); margin-bottom: 12px;">التصنيفات</h4>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <a href="{{ route('shop.index', request()->except('category', 'page')) }}" 
                               style="font-size: 0.88rem; display: flex; justify-content: space-between; align-items: center; padding: 6px 8px; border-radius: 6px; {{ empty(request('category')) ? 'background: #F7F1E7; color: #9F8257; font-weight: 600;' : 'color: #5C5550;' }}">
                                <span>جميع التصنيفات</span>
                                <span style="font-size: 0.75rem; color: #8E867F;">{{ \App\Models\Product::active()->count() }}</span>
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" 
                                   style="font-size: 0.88rem; display: flex; justify-content: space-between; align-items: center; padding: 6px 8px; border-radius: 6px; {{ request('category') === $cat->slug ? 'background: #F7F1E7; color: #9F8257; font-weight: 600;' : 'color: #5C5550;' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span style="font-size: 0.75rem; color: #8E867F;">{{ $cat->active_products_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Checkboxes Form -->
                    <form action="{{ route('shop.index') }}" method="GET">
                        @if(request()->filled('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request()->filled('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        @if(request()->filled('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <div style="margin-bottom: 24px; padding-top: 16px; border-top: 1px solid var(--border-light);">
                            <h4 style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary); margin-bottom: 12px;">حالة المنتج</h4>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; color: #5C5550; margin-bottom: 10px; cursor: pointer;">
                                <input type="checkbox" name="offers" value="1" {{ request()->boolean('offers') ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>عروض وخصومات فقط</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; color: #5C5550; cursor: pointer;">
                                <input type="checkbox" name="in_stock" value="1" {{ request()->boolean('in_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>متوفر في المخزون فقط</span>
                            </label>
                        </div>
                    </form>
                </aside>

                <!-- Products Grid -->
                <main style="min-width: 0;">
                    @if($products->count() > 0)
                        <div class="products-grid">
                            @foreach($products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>

                        <!-- Pagination Links -->
                        <div style="margin-top: 40px; display: flex; justify-content: center;">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 60px 20px; text-align: center;">
                            <i class="fa-solid fa-bag-shopping" style="font-size: 3.5rem; color: #DFC8A8; margin-bottom: 18px;"></i>
                            <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">لم نجد عبايات تطابق خيارات البحث</h3>
                            <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 24px;">جربي إزالة بعض الفلاتر أو البحث بكلمة مختلفة</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-gold">
                                عرض جميع العبايات
                            </a>
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtn = document.getElementById('mobileFilterToggleBtn');
        const sidebar = document.getElementById('shopFiltersSidebar');
        const chevron = document.getElementById('mobileFilterChevron');
        if (filterBtn && sidebar) {
            filterBtn.addEventListener('click', function () {
                sidebar.classList.toggle('show-mobile');
                if (chevron) {
                    chevron.classList.toggle('fa-chevron-down');
                    chevron.classList.toggle('fa-chevron-up');
                }
            });
        }
    });
</script>
@endpush

@endsection
