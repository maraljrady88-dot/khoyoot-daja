@extends('layouts.app')

@section('title', 'خيوط دعجاء للعبايات الخليجية | حيث تلتقي الأصالة بالفخامة')

@section('content')

    <!-- Hero Section -->
    @php
        $heroBanner = $banners->first();
    @endphp
    <section class="hero-section">
        @if($heroBanner)
            <img src="{{ $heroBanner->image_url }}" alt="عبايات خيوط دعجاء" class="hero-bg">
        @else
            <img src="{{ asset('images/hero_banner.jpg') }}" alt="عبايات خيوط دعجاء" class="hero-bg">
        @endif
        <div class="hero-overlay"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="hero-content">
                <span class="hero-badge">
                    <i class="fa-solid fa-crown" style="margin-left: 6px;"></i>
                    {{ $heroBanner->badge_text ?? 'خيوط دعجاء للعبايات الخليجية' }}
                </span>
                
                <h1 class="hero-title">
                    {{ \App\Models\Setting::get('site_name', 'خيوط دعجاء') }}
                    <br>
                    <span>{{ \App\Models\Setting::get('site_tagline', 'حيث تلتقي الأصالة بالفخامة') }}</span>
                </h1>
                
                <p class="hero-desc">
                    {{ \App\Models\Setting::get('site_subtitle', 'أرقى العبايات الخليجية التي تجمع بين الأصالة والحداثة.') }}
                </p>

                <div class="hero-buttons">
                    <a href="{{ route('shop.index') }}" class="btn btn-gold btn-lg">
                        <i class="fa-solid fa-bag-shopping"></i> تسوقي الآن
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'luxury-abayas']) }}" class="btn btn-outline-dark btn-lg" style="background: rgba(255,255,255,0.1); color: #FFFFFF; border-color: rgba(255,255,255,0.3);">
                        <i class="fa-regular fa-compass"></i> اكتشفي التشكيلة
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Propositions / Features Bar -->
    <section style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 30px 0;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap: 24px; text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 14px;">
                    <i class="fa-solid fa-gem" style="font-size: 1.8rem; color: var(--brand-gold);"></i>
                    <div style="text-align: right;">
                        <h4 style="font-size: 0.95rem; font-weight: 700; color: #181615;">أقمشة خليجية منتقاة</h4>
                        <p style="font-size: 0.8rem; color: #8E867F;">أجود أنواع الكريب والصالونا الأصلي</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; justify-content: center; gap: 14px;">
                    <i class="fa-solid fa-truck-fast" style="font-size: 1.8rem; color: var(--brand-gold);"></i>
                    <div style="text-align: right;">
                        <h4 style="font-size: 0.95rem; font-weight: 700; color: #181615;">شحن سريع بالمملكة</h4>
                        <p style="font-size: 0.8rem; color: #8E867F;">توصيل آمن لباب منزلكِ في كافة المدن</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; justify-content: center; gap: 14px;">
                    <i class="fa-solid fa-shield-halved" style="font-size: 1.8rem; color: var(--brand-gold);"></i>
                    <div style="text-align: right;">
                        <h4 style="font-size: 0.95rem; font-weight: 700; color: #181615;">دفع آمن ومتعدد</h4>
                        <p style="font-size: 0.8rem; color: #8E867F;">مدى، فيزا، Apple Pay، ودفع عند الاستلام</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; justify-content: center; gap: 14px;">
                    <i class="fa-solid fa-store" style="font-size: 1.8rem; color: var(--brand-gold);"></i>
                    <div style="text-align: right;">
                        <h4 style="font-size: 0.95rem; font-weight: 700; color: #181615;">فروعنا بخدمتكِ</h4>
                        <p style="font-size: 0.8rem; color: #8E867F;">الطائف الدولي وحفر الباطن - لوريت سنتر</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">تشكيلات حصرية</span>
                <h2 class="section-title">تصنيفات خيوط دعجاء</h2>
                <p class="section-desc">اختاري ما يناسب إطلالتكِ الراقية من تشكيلاتنا المتنوعة والمصممة لتلائم جميع الأوقات والمناسبات</p>
            </div>

            <div class="categories-grid">
                @foreach($categories as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="category-card">
                        <div class="category-icon-circle">
                            @if(str_contains($category->slug, 'new'))
                                <i class="fa-solid fa-sparkles"></i>
                            @elseif(str_contains($category->slug, 'gulf'))
                                <i class="fa-solid fa-feather-pointed"></i>
                            @elseif(str_contains($category->slug, 'luxury'))
                                <i class="fa-solid fa-crown"></i>
                            @elseif(str_contains($category->slug, 'daily'))
                                <i class="fa-solid fa-bag-shopping"></i>
                            @elseif(str_contains($category->slug, 'embroidered'))
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            @else
                                <i class="fa-solid fa-tag"></i>
                            @endif
                        </div>
                        <span class="category-name">{{ $category->name }}</span>
                        <span class="category-count">{{ $category->activeProducts()->count() }} عباية</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products (العبايات الأكثر طلباً والفاخرة) -->
    <section class="section" style="background-color: #F8F5EF; border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);">
        <div class="container">
            <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 36px; flex-wrap: wrap; gap: 16px;">
                <div>
                    <span class="section-badge">المختارات الفاخرة</span>
                    <h2 class="section-title" style="margin-bottom: 4px;">العبايات الأكثر طلباً</h2>
                    <p class="section-desc">قطع فنية تجمع بين سواد الليل وهيبة الإطلالة الخليجية</p>
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-outline-gold btn-sm">
                    مشاهدة كامل المجموعة &larr;
                </a>
            </div>

            <div class="products-grid">
                @forelse($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
                        لا توجد عبايات مميزة حالياً.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Brand Identity Intro Section (About Us Spotlight) -->
    <section class="brand-intro-section">
        <div class="container">
            <div class="brand-intro-card">
                <div style="width: 70px; height: 70px; margin: 0 auto 20px; border-radius: 50%; background: rgba(191, 161, 117, 0.15); display: flex; align-items: center; justify-content: center; color: var(--brand-gold);">
                    <i class="fa-solid fa-quote-right" style="font-size: 1.8rem;"></i>
                </div>
                
                <h3 class="brand-intro-tagline">
                    {{ \App\Models\Setting::get('site_name', 'خيوط دعجاء') }}.. حيث تلتقي الأصالة بالفخامة
                </h3>
                
                <p class="brand-intro-quote">
                    "نحن متجر متخصص في تقديم أرقى أنواع العبايات، حيث نحرص على توفير تشكيلات جديدة وجميلة تواكب أحدث صيحات الموضة مع الحفاظ على الرقي والجمال. نؤمن بأن العباية ليست مجرد قطعة قماش، بل هي تعبير عن الهوية والأناقة، لذا ننتقي لكم أجود الأقمشة وأجمل التصاميم لنضمن لكم إطلالة فريدة ومتميزة في كل وقت."
                </p>

                <div style="display: flex; justify-content: center; gap: 16px; margin-top: 24px;">
                    <a href="{{ route('pages.about') }}" class="btn btn-gold btn-sm">
                        تعرفي أكثر على خيوط دعجاء
                    </a>
                    <a href="{{ route('pages.branches') }}" class="btn btn-outline-dark btn-sm" style="color: #FFFFFF; border-color: rgba(255,255,255,0.3);">
                        زيارة فروعنا
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Discounted / Seasonal Offers Section -->
    @if($discountedProducts->count() > 0)
        <section class="section">
            <div class="container">
                <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 36px; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <span class="section-badge" style="color: #B91C1C;">عروض محدودة</span>
                        <h2 class="section-title" style="margin-bottom: 4px;">عروض وخصومات حصرية</h2>
                        <p class="section-desc">فرصتكِ لامتلاك أرقى عبايات الموسم بأسعار خاصة</p>
                    </div>
                    <a href="{{ route('shop.index', ['offers' => 1]) }}" class="btn btn-outline-gold btn-sm">
                        جميع العروض والتخفيضات &larr;
                    </a>
                </div>

                <div class="products-grid">
                    @foreach($discountedProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Customer Reviews Highlights Section -->
    @if($featuredReviews->count() > 0)
        <section class="section" style="background-color: #FFFFFF; border-top: 1px solid var(--border-light);">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">تجارب حقيقية</span>
                    <h2 class="section-title">ماذا تقول عميلات خيوط دعجاء؟</h2>
                    <p class="section-desc">ثقة عميلاتنا في مختلف مدن المملكة هي وسام فخرنا وسر استمرارنا في تقديم الأفضل</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 280px), 1fr)); gap: 24px;">
                    @foreach($featuredReviews as $review)
                        <div class="review-item" style="margin-bottom: 0;">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #FAF8F5; border: 1px solid var(--border-gold); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--brand-gold-dark);">
                                        {{ mb_substr($review->customer_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="reviewer-name">{{ $review->customer_name }}</div>
                                        @if($review->is_verified_purchase)
                                            <span class="verified-badge">
                                                <i class="fa-solid fa-circle-check"></i> تم الشراء ✔
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="score-stars" style="font-size: 0.95rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>

                            <p class="review-text" style="margin-bottom: 12px;">
                                "{{ $review->review_text }}"
                            </p>

                            @if($review->product)
                                <div style="padding-top: 10px; border-top: 1px dashed var(--border-light); font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-tag" style="color: var(--brand-gold-dark);"></i>
                                    المنتج: <a href="{{ route('products.show', $review->product->slug) }}" style="color: var(--text-primary); font-weight: 600;">{{ $review->product->name }}</a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Branches Section -->
    <section class="section" style="background-color: var(--bg-page); border-top: 1px solid var(--border-light);">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">فروعنا بالمملكة</span>
                <h2 class="section-title">فروع خيوط دعجاء</h2>
                <p class="section-desc">نسعد بزيارتكِ وتجربة عباياتنا على أرض الواقع في فروعنا</p>
            </div>

            <div class="branches-grid">
                @foreach($branches as $branch)
                    <div class="branch-card">
                        <h3 class="branch-title">
                            <i class="fa-solid fa-store" style="color: var(--brand-gold-dark);"></i>
                            {{ $branch->name }}
                        </h3>
                        <div class="branch-detail">
                            <i class="fa-solid fa-location-dot"></i>
                            <div><strong>العنوان:</strong> {{ $branch->address }} ({{ $branch->city }})</div>
                        </div>
                        @if($branch->working_hours)
                            <div class="branch-detail">
                                <i class="fa-regular fa-clock"></i>
                                <div><strong>أوقات العمل:</strong> {{ $branch->working_hours }}</div>
                            </div>
                        @endif
                        @if($branch->phone)
                            <div class="branch-detail">
                                <i class="fa-solid fa-phone"></i>
                                <div><strong>الهاتف:</strong> <span dir="ltr">{{ $branch->phone }}</span></div>
                            </div>
                        @endif
                        @if($branch->google_maps_url)
                            <div style="margin-top: 18px;">
                                <a href="{{ $branch->google_maps_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-gold btn-sm">
                                    <i class="fa-solid fa-map-location-dot"></i> الموقع على Google Maps
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
