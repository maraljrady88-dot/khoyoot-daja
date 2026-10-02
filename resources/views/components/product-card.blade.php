@props(['product'])

@php
    $isWishlisted = false;
    if (Auth::check()) {
        $isWishlisted = Auth::user()->wishlists()->where('product_id', $product->id)->exists();
    } else {
        $isWishlisted = in_array($product->id, session()->get('wishlist', []));
    }
    $stockInfo = $product->stock_status;
@endphp

<div class="product-card">
    <!-- Badges -->
    <div class="product-badges">
        @if($product->has_discount)
            <span class="badge badge-discount">خصم {{ $product->discount_percent }}%</span>
        @endif
        @if($product->is_featured)
            <span class="badge badge-featured">الأكثر طلباً</span>
        @endif
    </div>

    <!-- Wishlist Button -->
    <button type="button" 
            class="wishlist-toggle-btn {{ $isWishlisted ? 'active' : '' }}" 
            onclick="toggleWishlist({{ $product->id }}, this)" 
            title="{{ $isWishlisted ? 'إزالة من المفضلة' : 'إضافة للمفضلة' }}">
        <i class="{{ $isWishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart' }}" style="{{ $isWishlisted ? 'color: #E11D48;' : '' }}"></i>
    </button>

    <!-- Smart Aspect-Ratio Preserving Image Container (Never crop!) -->
    <a href="{{ route('products.show', $product->slug) }}" class="product-image-wrap">
        <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="product-img" loading="lazy">
    </a>

    <!-- Card Body -->
    <div class="product-body">
        <div class="product-category">{{ $product->category->name ?? 'عبايات' }}</div>
        
        <h3 class="product-title">
            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>

        @if($product->short_description)
            <p class="product-desc-short">{{ $product->short_description }}</p>
        @endif

        <!-- Ratings & Reviews -->
        <div style="margin-bottom: 8px;">
            <x-rating-stars :rating="$product->average_rating" :count="$product->reviews_count" :showScore="true" />
        </div>

        <!-- Stock Status -->
        <div style="margin-bottom: 12px;">
            @if(!$stockInfo['available'])
                <span style="font-size: 0.75rem; color: #DC2626; font-weight: 600;">
                    <i class="fa-solid fa-circle-xmark"></i> {{ $stockInfo['label'] }}
                </span>
            @elseif($product->stock_quantity <= ($product->low_stock_threshold ?: 3))
                <span style="font-size: 0.75rem; color: #D97706; font-weight: 600;">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $stockInfo['label'] }}
                </span>
            @else
                <span style="font-size: 0.75rem; color: #059669; font-weight: 500;">
                    <i class="fa-solid fa-circle-check"></i> {{ $stockInfo['label'] }}
                </span>
            @endif
        </div>

        <!-- Price -->
        <div class="product-price-row">
            <div class="price-current">
                {{ number_format($product->price, 0) }} <small>ر.س</small>
            </div>
            @if($product->has_discount)
                <div class="price-old">
                    {{ number_format($product->compare_at_price, 0) }} ر.س
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="product-actions">
            @if($stockInfo['available'])
                <button type="button" class="btn btn-primary btn-sm" onclick="quickAddToCart({{ $product->id }})" title="إضافة سريعة للسلة">
                    <i class="fa-solid fa-cart-plus"></i> إضافة للسلة
                </button>
            @else
                <button type="button" class="btn btn-sm" style="background: #E5E7EB; color: #9CA3AF; cursor: not-allowed;" disabled>
                    نفذت الكمية
                </button>
            @endif

            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-gold btn-sm" title="عرض تفاصيل العباية">
                <i class="fa-regular fa-eye"></i> التفاصيل
            </a>
        </div>
    </div>
</div>
