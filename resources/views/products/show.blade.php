@extends('layouts.app')

@section('title', $product->name . ' | خيوط دعجاء للعبايات الخليجية')
@section('meta_description', $product->short_description ?: $product->name)

@section('content')

    <!-- Breadcrumbs -->
    <div style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 18px 0;">
        <div class="container">
            <div style="font-size: 0.85rem; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                <a href="{{ route('home') }}" style="color: var(--text-muted);">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('shop.index') }}" style="color: var(--text-muted);">المتجر</a>
                <span>/</span>
                <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" style="color: var(--text-muted);">
                    {{ $product->category->name }}
                </a>
                <span>/</span>
                <span style="color: var(--text-primary); font-weight: 500;">{{ $product->name }}</span>
            </div>
        </div>
    </div>

    <!-- Product Main Section -->
    <section class="section" style="padding-top: 40px;">
        <div class="container">
            <div class="product-detail-grid">
                
                <!-- Gallery Column (Preserves Aspect Ratio, No Crop!) -->
                <div>
                    <!-- Main Image Display Container -->
                    <div style="position: relative; width: 100%; aspect-ratio: 3 / 4; background: #F6F3ED; border-radius: var(--radius-md); border: 1px solid var(--border-light); display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 16px; margin-bottom: 16px;">
                        <img id="mainProductImage" 
                             src="{{ $product->main_image_url }}" 
                             alt="{{ $product->name }}" 
                             style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; transition: transform 0.3s ease;">

                        @if($product->has_discount)
                            <div style="position: absolute; top: 16px; right: 16px;">
                                <span class="badge badge-discount" style="font-size: 0.85rem; padding: 6px 12px;">
                                    خصم {{ $product->discount_percent }}%
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails Carousel/Row -->
                    @if($product->images->count() > 1)
                        <div style="display: flex; gap: 12px; overflow-x: auto; padding-bottom: 6px;">
                            @foreach($product->images as $img)
                                <button type="button" 
                                        onclick="document.getElementById('mainProductImage').src = '{{ $img->url }}'"
                                        style="width: 80px; height: 100px; padding: 6px; background: #F6F3ED; border: 2px solid var(--border-light); border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    <img src="{{ $img->url }}" alt="{{ $product->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Details Column -->
                <div>
                    <div style="font-size: 0.85rem; color: var(--brand-gold-dark); font-weight: 600; margin-bottom: 6px;">
                        {{ $product->category->name }}
                    </div>

                    <h1 style="font-size: 2.1rem; font-weight: 700; color: var(--text-primary); line-height: 1.3; margin-bottom: 12px;">
                        {{ $product->name }}
                    </h1>

                    <!-- Rating and SKU -->
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border-light);">
                        <a href="#reviewsSection" style="display: flex; align-items: center; gap: 8px;">
                            <x-rating-stars :rating="$product->average_rating" :count="$product->reviews_count" :showScore="true" />
                            <span style="font-size: 0.85rem; color: var(--brand-gold-dark); text-decoration: underline;">
                                ({{ $product->reviews_count }} تقييم)
                            </span>
                        </a>

                        <div style="font-size: 0.85rem; color: var(--text-muted);">
                            رمز الموديل (SKU): <strong style="color: var(--text-primary);">{{ $product->sku }}</strong>
                        </div>
                    </div>

                    <!-- Price -->
                    <div style="display: flex; align-items: baseline; gap: 14px; margin-bottom: 20px;">
                        <span style="font-size: 2.2rem; font-weight: 700; color: var(--text-primary);">
                            {{ number_format($product->price, 0) }} <small style="font-size: 1.1rem; font-weight: 500; color: var(--brand-gold-dark);">ر.س</small>
                        </span>

                        @if($product->has_discount)
                            <span style="font-size: 1.3rem; color: #9CA3AF; text-decoration: line-through;">
                                {{ number_format($product->compare_at_price, 0) }} ر.س
                            </span>
                            <span style="background: #FEE2E2; color: #DC2626; padding: 4px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: 700;">
                                وفري {{ number_format($product->compare_at_price - $product->price, 0) }} ر.س
                            </span>
                        @endif
                    </div>

                    <!-- Short Description -->
                    @if($product->short_description)
                        <p style="font-size: 1rem; color: var(--text-secondary); line-height: 1.8; margin-bottom: 24px;">
                            {{ $product->short_description }}
                        </p>
                    @endif

                    <!-- Stock Status Banner -->
                    @php $stockInfo = $product->stock_status; @endphp
                    <div style="padding: 12px 18px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; font-size: 0.9rem; border: 1px solid; {{ $stockInfo['available'] ? 'background: #F0FDF4; border-color: #BBF7D0; color: #166534;' : 'background: #FEF2F2; border-color: #FECACA; color: #991B1B;' }}">
                        <i class="{{ $stockInfo['available'] ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-xmark' }}"></i>
                        <strong>حالة التوفر:</strong> <span>{{ $stockInfo['label'] }}</span>
                    </div>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- Sizes Selector -->
                        @if(!empty($product->sizes) && count($product->sizes) > 0)
                            <div style="margin-bottom: 22px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <label class="form-label" style="margin-bottom: 0;">اختر المقاس:</label>
                                    <span style="font-size: 0.8rem; color: var(--brand-gold-dark); cursor: pointer;" onclick="alert('دليل المقاسات:\nمقاس 52: الطول 150-153 سم\nمقاس 54: الطول 154-158 سم\nمقاس 56: الطول 159-164 سم\nمقاس 58: الطول 165-170 سم\nمقاس 60: الطول 171-175 سم')">
                                        <i class="fa-solid fa-ruler"></i> دليل المقاسات
                                    </span>
                                </div>
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    @foreach($product->sizes as $idx => $size)
                                        <label style="cursor: pointer;">
                                            <input type="radio" name="size" value="{{ $size }}" {{ $idx === 0 ? 'checked' : '' }} style="display: none;" class="size-radio">
                                            <span class="size-pill" style="display: inline-block; min-width: 48px; padding: 10px 16px; border: 1px solid var(--border-light); border-radius: 6px; text-align: center; font-weight: 600; font-size: 0.95rem; background: #FFFFFF; transition: var(--transition);">
                                                {{ $size }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Colors Selector (if applicable) -->
                        @if(!empty($product->colors) && count($product->colors) > 0)
                            <div style="margin-bottom: 22px;">
                                <label class="form-label">اللون:</label>
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    @foreach($product->colors as $cIdx => $color)
                                        <label style="cursor: pointer;">
                                            <input type="radio" name="color" value="{{ $color }}" {{ $cIdx === 0 ? 'checked' : '' }} style="display: none;" class="color-radio">
                                            <span class="color-pill" style="display: inline-block; padding: 8px 16px; border: 1px solid var(--border-light); border-radius: 6px; font-size: 0.88rem; background: #FFFFFF; transition: var(--transition);">
                                                {{ $color }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Quantity & Buttons -->
                        <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 28px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; border: 1px solid var(--border-light); border-radius: 6px; background: #FFFFFF; overflow: hidden;">
                                <button type="button" onclick="const q = document.getElementById('qtyInput'); if(q.value > 1) q.value--;" style="width: 40px; height: 46px; background: none; border: none; font-size: 1.1rem; cursor: pointer;">-</button>
                                <input type="number" id="qtyInput" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" style="width: 50px; text-align: center; border: none; font-weight: 700; font-size: 1rem; outline: none;">
                                <button type="button" onclick="const q = document.getElementById('qtyInput'); if(q.value < {{ $product->stock_quantity }}) q.value++;" style="width: 40px; height: 46px; background: none; border: none; font-size: 1.1rem; cursor: pointer;">+</button>
                            </div>

                            @if($stockInfo['available'])
                                <button type="submit" class="btn btn-primary btn-lg" style="flex-grow: 1;">
                                    <i class="fa-solid fa-cart-plus"></i> إضافة إلى سلة التسوق
                                </button>
                            @else
                                <button type="button" class="btn btn-lg" style="flex-grow: 1; background: #E5E7EB; color: #9CA3AF; cursor: not-allowed;" disabled>
                                    نفذت الكمية من المخزون
                                </button>
                            @endif

                            <!-- Wishlist -->
                            @php
                                $isWishlisted = Auth::check() 
                                    ? Auth::user()->wishlists()->where('product_id', $product->id)->exists()
                                    : in_array($product->id, session()->get('wishlist', []));
                            @endphp
                            <button type="button" 
                                    class="action-btn {{ $isWishlisted ? 'active' : '' }}" 
                                    style="width: 50px; height: 50px; font-size: 1.2rem;" 
                                    onclick="toggleWishlist({{ $product->id }}, this)" 
                                    title="المفضلة">
                                <i class="{{ $isWishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart' }}" style="{{ $isWishlisted ? 'color: #E11D48;' : '' }}"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Share Buttons -->
                    <div style="display: flex; align-items: center; gap: 12px; padding-top: 18px; border-top: 1px solid var(--border-light); font-size: 0.9rem; color: var(--text-muted);">
                        <span>مشاركة العباية:</span>
                        <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" style="color: #25D366; font-size: 1.2rem;" title="مشاركة عبر واتساب">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($product->name) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" style="color: #1DA1F2; font-size: 1.2rem;" title="مشاركة على تويتر/X">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('تم نسخ رابط العباية بنجاح!');" style="background: none; border: none; color: var(--text-primary); font-size: 1.1rem; cursor: pointer;" title="نسخ الرابط">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabs: Description & Detailed Specifications -->
            <div style="margin-top: 60px; background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 36px; box-shadow: var(--shadow-soft);">
                <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light);">
                    وصف وتفاصيل العباية
                </h3>

                <div style="font-size: 1rem; line-height: 1.9; color: var(--text-secondary); margin-bottom: 28px; white-space: pre-line;">
                    {{ $product->description ?: $product->short_description }}
                </div>

                @if($product->details)
                    <div style="background: #FAF8F5; border: 1px solid var(--border-light); border-radius: 8px; padding: 24px;">
                        <h4 style="font-size: 1rem; font-weight: 700; color: var(--brand-gold-dark); margin-bottom: 12px;">
                            المواصفات وخامة القماش:
                        </h4>
                        <div style="font-size: 0.95rem; line-height: 1.8; color: var(--text-primary); white-space: pre-line;">
                            {{ $product->details }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- PRODUCT REVIEWS & RATINGS SYSTEM (نظام تقييم ومراجعة المنتجات ⭐) -->
            <div id="reviewsSection" style="margin-top: 50px;">
                <div class="section-header" style="text-align: right; margin-bottom: 28px;">
                    <span class="section-badge">آراء العميلات</span>
                    <h2 class="section-title">تقييمات ومراجعات العباية</h2>
                </div>

                <!-- Reviews Summary Card -->
                <div class="reviews-summary-card">
                    <!-- Left: Score Box -->
                    <div class="score-box">
                        <div class="score-number">{{ number_format($product->average_rating, 1) }}</div>
                        <div class="score-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($product->average_rating) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <div class="score-text">
                            بناءً على <strong>{{ $product->reviews_count }}</strong> تقييم
                        </div>
                    </div>

                    <!-- Right: Rating Distribution Bars -->
                    <div class="bars-list">
                        @php $distribution = $product->rating_distribution; @endphp
                        @for($s = 5; $s >= 1; $s--)
                            <div class="bar-row">
                                <div class="bar-label">
                                    <span>{{ $s }}</span>
                                    <i class="fa-solid fa-star" style="color: #F59E0B; font-size: 0.8rem;"></i>
                                </div>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width: {{ $distribution[$s]['percentage'] }}%;"></div>
                                </div>
                                <div class="bar-count">
                                    {{ $distribution[$s]['count'] }}
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Add Review Form (Eligible for customers) -->
                <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 30px; margin-bottom: 36px; box-shadow: var(--shadow-soft);">
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-regular fa-comment-dots" style="color: var(--brand-gold);"></i>
                        أضيفي تقييمكِ ورأيكِ حول هذه العباية
                    </h3>

                    @if($existingReview)
                        <div class="alert alert-info">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>شكراً لكِ! لقد قمتِ مسبقاً بتقييم هذه العباية بـ {{ $existingReview->rating }} نجوم.</span>
                        </div>
                    @else
                        <form action="{{ route('reviews.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-grid-2col" style="margin-bottom: 18px;">
                                <!-- Customer Name -->
                                <div>
                                    <label class="form-label">الاسم الكريم *</label>
                                    <input type="text" name="customer_name" class="form-control" value="{{ Auth::check() ? Auth::user()->name : old('customer_name') }}" required placeholder="اسمكِ كما يظهر في التقييم">
                                </div>

                                <!-- Star Rating Selection -->
                                <div>
                                    <label class="form-label">تقييمكِ بالنجوم *</label>
                                    <div style="display: flex; gap: 8px; align-items: center; height: 46px;">
                                        @for($i = 5; $i >= 1; $i--)
                                            <label style="cursor: pointer; display: flex; align-items: center; gap: 4px; font-size: 0.9rem; color: #F59E0B;">
                                                <input type="radio" name="rating" value="{{ $i }}" {{ old('rating') == $i || $i == 5 ? 'checked' : '' }}>
                                                <span>{{ $i }} ⭐</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <!-- Review Text -->
                            <div class="form-group">
                                <label class="form-label">رأيكِ وملاحظاتكِ عن العباية والقماش والخياطة *</label>
                                <textarea name="review_text" rows="4" class="form-control" required placeholder="شاركينا تجربتكِ حول سواد العباية، المقاس، جودة القماش، وسرعة التوصيل...">{{ old('review_text') }}</textarea>
                            </div>

                            <!-- Optional Image Attachment -->
                            <div class="form-group" style="margin-bottom: 24px;">
                                <label class="form-label">صورة للعباية عند الاستلام (اختياري)</label>
                                <input type="file" name="image" accept="image/*" class="form-control">
                                <small style="color: var(--text-muted); font-size: 0.8rem;">يمكنكِ إرفاق صورة واضحة لتفاصيل العباية (JPEG, PNG, WebP بحد أقصى 3 ميجابايت).</small>
                            </div>

                            <button type="submit" class="btn btn-gold">
                                <i class="fa-solid fa-paper-plane"></i> إرسال التقييم
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Reviews List -->
                <div>
                    <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 20px;">
                        تقييمات العميلات ({{ $product->approved_reviews_count ?: $product->approvedReviews->count() }})
                    </h3>

                    @forelse($product->approvedReviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #F6F3ED; border: 1px solid var(--border-gold); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--brand-gold-dark); font-size: 1.1rem;">
                                        {{ mb_substr($review->customer_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="reviewer-name">{{ $review->customer_name }}</div>
                                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 3px;">
                                            @if($review->is_verified_purchase)
                                                <span class="verified-badge">
                                                    <i class="fa-solid fa-circle-check"></i> تم الشراء ✔
                                                </span>
                                            @endif
                                            <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="score-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>

                            <p class="review-text">
                                {{ $review->review_text }}
                            </p>

                            @if($review->image_url)
                                <div style="margin-top: 14px;">
                                    <img src="{{ $review->image_url }}" alt="صورة التقييم" style="max-width: 140px; border-radius: 8px; border: 1px solid var(--border-light); cursor: pointer;" onclick="window.open(this.src)">
                                </div>
                            @endif
                        </div>
                    @empty
                        <div style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 40px; text-align: center; color: var(--text-muted);">
                            <i class="fa-regular fa-star" style="font-size: 2.5rem; color: #DFC8A8; margin-bottom: 12px;"></i>
                            <p style="font-size: 1.05rem; font-weight: 600; color: var(--text-primary);">
                                لا توجد تقييمات لهذا المنتج حتى الآن.
                            </p>
                            <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 4px;">
                                كوني أول من يشارك رأيه حول هذه العباية الأنيقة!
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div style="margin-top: 70px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px;">
                        <h3 style="font-size: 1.4rem; font-weight: 700; color: var(--text-primary);">
                            عبايات قد تعجبكِ أيضاً
                        </h3>
                        <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" style="font-size: 0.9rem; color: var(--brand-gold-dark); font-weight: 600;">
                            المزيد من هذا التصنيف &larr;
                        </a>
                    </div>

                    <div class="products-grid">
                        @foreach($relatedProducts as $relProduct)
                            <x-product-card :product="$relProduct" />
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

    <style>
        .size-radio:checked + .size-pill {
            background-color: var(--brand-primary) !important;
            color: #FFFFFF !important;
            border-color: var(--brand-primary) !important;
        }
        .color-radio:checked + .color-pill {
            background-color: var(--brand-primary) !important;
            color: #FFFFFF !important;
            border-color: var(--brand-primary) !important;
        }
    </style>

@endsection
