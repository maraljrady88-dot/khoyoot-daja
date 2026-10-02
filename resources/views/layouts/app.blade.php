<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'خيوط دعجاء للعبايات الخليجية | حيث تلتقي الأصالة بالفخامة')</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('site_subtitle', 'أرقى العبايات الخليجية التي تجمع بين الأصالة والحداثة.'))">
    
    <!-- Open Graph / SEO -->
    <meta property="og:title" content="@yield('title', 'خيوط دعجاء للعبايات الخليجية')">
    <meta property="og:description" content="@yield('meta_description', 'أرقى العبايات الخليجية التي تجمع بين الأصالة والحداثة.')">
    <meta property="og:image" content="/images/logo.webp">
    <meta property="og:type" content="website">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logo.webp">
    
    <!-- Thmanyah Arabic Font -->
    <link rel="stylesheet" href="/css/thmanyah-font.css">
    
    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Main Luxury Storefront CSS -->
    <link rel="stylesheet" href="/css/style.css">
    
    @php
        $primaryColor = \App\Models\Setting::get('primary_color', '#1A1817');
        $accentColor = \App\Models\Setting::get('accent_color', '#BFA175');
    @endphp
    <!-- Dynamic Theme Colors from Admin Settings -->
    <style>
        :root {
            --brand-primary: {{ $primaryColor }};
            --brand-gold: {{ $accentColor }};
            --brand-gold-dark: {{ $accentColor }};
            --brand-gold-light: {{ $accentColor }}18;
            --border-gold: {{ $accentColor }};
        }
        
        /* Direct Overrides to Ensure Theme Color Applies Everywhere */
        .btn-primary {
            background-color: {{ $primaryColor }} !important;
            border-color: {{ $primaryColor }} !important;
            color: #FFFFFF !important;
        }
        .btn-gold {
            background-color: {{ $accentColor }} !important;
            border-color: {{ $accentColor }} !important;
            color: #FFFFFF !important;
        }
        .btn-gold:hover {
            filter: brightness(0.92);
        }
        .btn-outline-gold {
            border-color: {{ $accentColor }} !important;
            color: {{ $accentColor }} !important;
        }
        .btn-outline-gold:hover {
            background-color: {{ $accentColor }} !important;
            color: #FFFFFF !important;
        }
        .action-badge {
            background-color: {{ $accentColor }} !important;
            color: #FFFFFF !important;
        }
        .brand-subtitle, 
        .product-category, 
        .section-badge,
        .product-price-current,
        .hero-title span,
        .brand-gold-text {
            color: {{ $accentColor }} !important;
        }
        .nav-link.active::after, 
        .footer-col-title::after,
        .category-card:hover .category-icon-circle {
            background-color: {{ $accentColor }} !important;
            color: #FFFFFF !important;
        }
        .category-card:hover {
            border-color: {{ $accentColor }} !important;
        }
        .product-card:hover {
            border-color: {{ $accentColor }} !important;
        }
        .nav-link.active,
        .nav-link:hover {
            color: {{ $accentColor }} !important;
        }
        .action-btn:hover {
            border-color: {{ $accentColor }} !important;
            color: {{ $accentColor }} !important;
        }
        .form-control:focus {
            border-color: {{ $accentColor }} !important;
            outline: none;
            box-shadow: 0 0 0 3px {{ $accentColor }}25;
        }
    </style>
    
    @stack('styles')
</head>
<body>

    <!-- Dynamic Manageable Announcement Bar (Continuous Animated Marquee) -->
    @php
        $announcementEnabled = \App\Models\Setting::get('announcement_enabled', '0');
        $announcementText = \App\Models\Setting::get('announcement_text', '');
        $announcementBg = \App\Models\Setting::get('announcement_bg', '#141312');
        $announcementLink = \App\Models\Setting::get('announcement_link', '');
    @endphp
    @if($announcementEnabled && !empty(trim($announcementText)))
        <div class="announcement-bar" style="background-color: {{ $announcementBg }};" title="{{ $announcementText }}">
            <div class="announcement-bar-inner">
                <div class="announcement-track">
                    @for($i = 0; $i < 4; $i++)
                        @if(!empty($announcementLink))
                            <a href="{{ $announcementLink }}" class="announcement-item" dir="rtl">
                                <i class="fa-solid fa-sparkles announcement-icon"></i>
                                <span>{{ $announcementText }}</span>
                                <i class="fa-solid fa-arrow-left" style="font-size: 0.72rem; opacity: 0.7;"></i>
                            </a>
                        @else
                            <span class="announcement-item" dir="rtl">
                                <i class="fa-solid fa-sparkles announcement-icon"></i>
                                <span>{{ $announcementText }}</span>
                            </span>
                        @endif
                        <span class="announcement-sep" aria-hidden="true">✦</span>
                    @endfor
                </div>

                <div class="announcement-track" aria-hidden="true">
                    @for($i = 0; $i < 4; $i++)
                        @if(!empty($announcementLink))
                            <a href="{{ $announcementLink }}" class="announcement-item" dir="rtl">
                                <i class="fa-solid fa-sparkles announcement-icon"></i>
                                <span>{{ $announcementText }}</span>
                                <i class="fa-solid fa-arrow-left" style="font-size: 0.72rem; opacity: 0.7;"></i>
                            </a>
                        @else
                            <span class="announcement-item" dir="rtl">
                                <i class="fa-solid fa-sparkles announcement-icon"></i>
                                <span>{{ $announcementText }}</span>
                            </span>
                        @endif
                        <span class="announcement-sep" aria-hidden="true">✦</span>
                    @endfor
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-inner">
                <!-- Mobile Menu Button -->
                <button type="button" class="mobile-toggle" id="mobileMenuBtn" aria-label="القائمة">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <!-- Brand Logo & Name -->
                <a href="{{ route('home') }}" class="brand-logo-wrap">
                    <img src="/images/logo.webp" alt="شعار خيوط دعجاء" class="brand-logo-img">
                    <div>
                        <div class="brand-title">{{ \App\Models\Setting::get('site_name', 'خيوط دعجاء') }}</div>
                        <div class="brand-subtitle">{{ \App\Models\Setting::get('site_tagline', 'حيث تلتقي الأصالة بالفخامة') }}</div>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="main-nav">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">الرئيسية</a>
                    <a href="{{ route('shop.index') }}" class="nav-link {{ request()->routeIs('shop.index') && !request()->filled('offers') ? 'active' : '' }}">المتجر</a>
                    <a href="{{ route('shop.index', ['offers' => 1]) }}" class="nav-link {{ request()->filled('offers') ? 'active' : '' }}">العروض</a>
                    <a href="{{ route('pages.branches') }}" class="nav-link {{ request()->routeIs('pages.branches') ? 'active' : '' }}">فروعنا</a>
                    <a href="{{ route('pages.about') }}" class="nav-link {{ request()->routeIs('pages.about') ? 'active' : '' }}">من نحن</a>
                    <a href="{{ route('pages.contact') }}" class="nav-link {{ request()->routeIs('pages.contact') ? 'active' : '' }}">تواصل معنا</a>
                </nav>

                <!-- Header Actions (Search, Wishlist, User, Cart) -->
                <div class="header-actions">
                    <!-- Search Trigger -->
                    <button type="button" class="action-btn" id="searchOpenBtn" title="بحث في المنتجات">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="action-btn" title="المفضلة">
                        <i class="fa-regular fa-heart"></i>
                        @php
                            $wishlistCount = Auth::check() 
                                ? Auth::user()->wishlists()->count() 
                                : count(session()->get('wishlist', []));
                        @endphp
                        @if($wishlistCount > 0)
                            <span class="action-badge" id="wishlistBadge">{{ $wishlistCount }}</span>
                        @endif
                    </a>

                    <!-- Shopping Cart -->
                    <a href="{{ route('cart.index') }}" class="action-btn" title="سلة التسوق">
                        <i class="fa-solid fa-bag-shopping"></i>
                        @php
                            $cart = session()->get('cart', []);
                            $cartCount = array_sum(array_column($cart, 'quantity'));
                        @endphp
                        <span class="action-badge" id="cartBadge" style="{{ $cartCount > 0 ? '' : 'display:none;' }}">{{ $cartCount }}</span>
                    </a>

                    <!-- Customer Account Dropdown -->
                    @auth
                        <div style="position: relative;" id="userMenuWrapper">
                            <button type="button" class="action-btn" id="userMenuBtn" title="حسابي">
                                <i class="fa-regular fa-user"></i>
                            </button>
                            <div id="userDropdown" style="display: none; position: absolute; left: 0; top: 110%; background: #fff; min-width: 190px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #EBE5DB; z-index: 100; overflow: hidden;">
                                <div style="padding: 12px 16px; border-bottom: 1px solid #071d59ff; background: #FAF8F5;">
                                    <div style="font-weight: 600; font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                                    <div style="font-size: 0.75rem; color: #8E867F;">{{ Auth::user()->email }}</div>
                                </div>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; font-size: 0.88rem; color: var(--brand-gold); font-weight: 600; border-bottom: 1px solid #F3EDE3;">
                                        <i class="fa-solid fa-gauge-high"></i>
                                        لوحة التحكم
                                    </a>
                                @endif
                                <a href="{{ route('customer.profile') }}" style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; font-size: 0.88rem; color: #181615;">
                                    <i class="fa-solid fa-id-card"></i>
                                    الملف الشخصي
                                </a>
                                <a href="{{ route('orders.index') }}" style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; font-size: 0.88rem; color: #181615;">
                                    <i class="fa-solid fa-box"></i>
                                    طلباتي
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" style="width: 100%; text-align: right; background: none; border: none; padding: 10px 16px; font-size: 0.88rem; color: #DC2626; cursor: pointer; display: flex; align-items: center; gap: 8px; border-top: 1px solid #F3EDE3;">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="action-btn" title="تسجيل الدخول">
                            <i class="fa-regular fa-user"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div id="mobileDrawer" style="display: none; position: fixed; inset: 0; z-index: 1000;">
        <div id="mobileBackdrop" style="position: absolute; inset: 0; background: rgba(0,0,0,0.5);"></div>
        <div style="position: absolute; top: 0; bottom: 0; right: 0; width: 280px; background: #FFFFFF; z-index: 2; padding: 24px; display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 1px solid #EBE5DB;">
                <span style="font-weight: 700; font-size: 1.15rem; color: #181615;">خيوط دعجاء</span>
                <button type="button" id="mobileCloseBtn" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #181615;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div style="display: flex; flex-direction: column; gap: 14px; font-size: 1rem;">
                <a href="{{ route('home') }}" style="color: #181615; padding: 6px 0;">الرئيسية</a>
                <a href="{{ route('shop.index') }}" style="color: #181615; padding: 6px 0;">المتجر</a>
                <a href="{{ route('shop.index', ['offers' => 1]) }}" style="color: #181615; padding: 6px 0;">العروض والتخفيضات</a>
                <a href="{{ route('pages.branches') }}" style="color: #181615; padding: 6px 0;">فروعنا</a>
                <a href="{{ route('pages.about') }}" style="color: #181615; padding: 6px 0;">من نحن</a>
                <a href="{{ route('pages.contact') }}" style="color: #181615; padding: 6px 0;">تواصل معنا</a>
                <a href="{{ route('orders.track') }}" style="color: #181615; padding: 6px 0;">تتبع طلبك</a>
            </div>
            <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid #EBE5DB;">
                @auth
                    <div style="font-size: 0.9rem; font-weight: 600; margin-bottom: 10px;">مرحباً، {{ Auth::user()->name }}</div>
                    <a href="{{ route('customer.profile') }}" class="btn btn-outline-dark btn-sm" style="width: 100%; margin-bottom: 8px;">حسابي</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="width: 100%; margin-bottom: 8px;">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-gold btn-sm" style="width: 100%;">إنشاء حساب جديد</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Live Search Modal -->
    <div id="searchModal" style="display: none; position: fixed; inset: 0; z-index: 1050;">
        <div id="searchBackdrop" style="position: absolute; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);"></div>
        <div style="position: relative; max-width: 640px; margin: 80px auto; background: #FFFFFF; border-radius: 12px; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); z-index: 2;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 1.15rem; font-weight: 700; color: #181615;">البحث في متجر خيوط دعجاء</h3>
                <button type="button" id="searchCloseBtn" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #8E867F;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('shop.index') }}" method="GET">
                <div style="position: relative;">
                    <input type="text" name="q" placeholder="ابحثي باسم العباية، القماش، أو التصنيف..." class="form-control" style="padding-left: 45px; font-size: 1.05rem;" autofocus>
                    <button type="submit" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--brand-gold); font-size: 1.2rem; cursor: pointer;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
            <div style="margin-top: 18px; display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                <span style="font-size: 0.8rem; color: #8E867F;">عمليات بحث شائعة:</span>
                <a href="{{ route('shop.index', ['category' => 'luxury-abayas']) }}" style="font-size: 0.8rem; background: #FAF8F5; border: 1px solid #EBE5DB; padding: 4px 10px; border-radius: 20px; color: #181615;">عبايات فاخرة</a>
                <a href="{{ route('shop.index', ['category' => 'gulf-abayas']) }}" style="font-size: 0.8rem; background: #FAF8F5; border: 1px solid #EBE5DB; padding: 4px 10px; border-radius: 20px; color: #181615;">بشت تراثي</a>
                <a href="{{ route('shop.index', ['category' => 'daily-abayas']) }}" style="font-size: 0.8rem; background: #FAF8F5; border: 1px solid #EBE5DB; padding: 4px 10px; border-radius: 20px; color: #181615;">كريب يومي</a>
                <a href="{{ route('shop.index', ['offers' => 1]) }}" style="font-size: 0.8rem; background: #FAF8F5; border: 1px solid #EBE5DB; padding: 4px 10px; border-radius: 20px; color: #B91C1C;">تخفيضات العروض</a>
            </div>
        </div>
    </div>

    <!-- Flash Notifications -->
    <div class="container" style="margin-top: 16px;">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">
                <i class="fa-solid fa-circle-info"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Floating WhatsApp Button -->
    @php
        $whatsappNumber = \App\Models\Setting::get('whatsapp_number', '966500000000');
    @endphp
    @if($whatsappNumber)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber) }}?text={{ urlencode('السلام عليكم ورحمة الله، أود الاستفسار عن عبايات خيوط دعجاء') }}" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="floating-whatsapp" 
           title="تواصلي معنا عبر واتساب">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    @endif

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Col 1: Store Intro -->
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                        <img src="/images/logo.webp" alt="خيوط دعجاء" style="height: 48px; width: auto; object-fit: contain;">
                        <div>
                            <span style="font-size: 1.3rem; font-weight: 700; color: #FFFFFF;">{{ \App\Models\Setting::get('site_name', 'خيوط دعجاء') }}</span>
                            <div style="font-size: 0.8rem; color: var(--brand-gold);">للعبايات الخليجية</div>
                        </div>
                    </div>
                    <p style="font-size: 0.9rem; line-height: 1.8; color: #BDB4A8; margin-bottom: 16px;">
                        {{ \App\Models\Setting::get('site_tagline', 'خيوط دعجاء.. حيث تلتقي الأصالة بالفخامة') }}. 
                        {{ \App\Models\Setting::get('site_subtitle', 'أرقى العبايات الخليجية التي تجمع بين الأصالة والحداثة.') }}
                    </p>

                    <!-- Social Media Links (editable from dashboard, no fake hardcoded links) -->
                    <div class="social-links">
                        @if($snap = \App\Models\Setting::get('snapchat_url'))
                            <a href="{{ $snap }}" target="_blank" rel="noopener noreferrer" class="social-icon-btn" title="Snapchat">
                                <i class="fa-brands fa-snapchat"></i>
                            </a>
                        @endif
                        @if($tiktok = \App\Models\Setting::get('tiktok_url'))
                            <a href="{{ $tiktok }}" target="_blank" rel="noopener noreferrer" class="social-icon-btn" title="TikTok">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        @endif
                        @if($insta = \App\Models\Setting::get('instagram_url'))
                            <a href="{{ $insta }}" target="_blank" rel="noopener noreferrer" class="social-icon-btn" title="Instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        @endif
                        @if($wa = \App\Models\Setting::get('whatsapp_number'))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}" target="_blank" rel="noopener noreferrer" class="social-icon-btn" title="WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="footer-col-title">روابط سريعة</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">الرئيسية</a></li>
                        <li><a href="{{ route('shop.index') }}">جميع العبايات</a></li>
                        <li><a href="{{ route('shop.index', ['offers' => 1]) }}">عروض الموسم</a></li>
                        <li><a href="{{ route('orders.track') }}">تتبع الطلب</a></li>
                        <li><a href="{{ route('wishlist.index') }}">قائمة المفضلة</a></li>
                        <li><a href="{{ route('pages.about') }}">من نحن</a></li>
                    </ul>
                </div>

                <!-- Col 3: Categories -->
                <div>
                    <h4 class="footer-col-title">أقسام المتجر</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('shop.index', ['category' => 'luxury-abayas']) }}">العبايات الفاخرة</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'gulf-abayas']) }}">العبايات الخليجية</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'new-abayas']) }}">عبايات جديدة</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'daily-abayas']) }}">العبايات اليومية</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'embroidered-abayas']) }}">العبايات المطرزة</a></li>
                    </ul>
                </div>

                <!-- Col 4: Branches & Help -->
                <div>
                    <h4 class="footer-col-title">فروعنا في المملكة</h4>
                    <ul class="footer-links" style="gap: 14px;">
                        <li style="display: flex; gap: 8px; align-items: flex-start;">
                            <i class="fa-solid fa-location-dot" style="color: var(--brand-gold); margin-top: 4px;"></i>
                            <div>
                                <strong style="color: #FFFFFF; font-size: 0.9rem;">فرع الطائف الدولي</strong>
                                <div style="font-size: 0.8rem; color: #8E867F;">مجمع الطائف الدولي - طريق الملك خالد</div>
                            </div>
                        </li>
                        <li style="display: flex; gap: 8px; align-items: flex-start;">
                            <i class="fa-solid fa-location-dot" style="color: var(--brand-gold); margin-top: 4px;"></i>
                            <div>
                                <strong style="color: #FFFFFF; font-size: 0.9rem;">فرع حفر الباطن</strong>
                                <div style="font-size: 0.8rem; color: #8E867F;">لوريت سنتر - طريق الملك فيصل</div>
                            </div>
                        </li>
                        <li style="margin-top: 6px;">
                            <a href="{{ route('pages.branches') }}" style="color: var(--brand-gold); font-size: 0.85rem; font-weight: 600;">
                                عرض تفاصيل الفروع وساعات العمل &larr;
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div>
                    جميع الحقوق محفوظة &copy; {{ date('Y') }} <strong>خيوط دعجاء للعبايات الخليجية</strong>.
                </div>
                <!-- Payment Badges -->
                <div style="display: flex; align-items: center; gap: 14px; font-size: 1.4rem; color: #BDB4A8;">
                    <span style="font-size: 0.8rem; color: #8E867F;">طرق الدفع المتاحة:</span>
                    <i class="fa-brands fa-cc-visa" title="Visa"></i>
                    <i class="fa-brands fa-cc-mastercard" title="Mastercard"></i>
                    <i class="fa-brands fa-apple-pay" title="Apple Pay"></i>
                    <i class="fa-solid fa-money-bill-wave" title="الدفع عند الاستلام"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Interactions (Drawer, Modal, User Dropdown) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // User Menu Dropdown
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userDropdown');
            if (userMenuBtn && userDropdown) {
                userMenuBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    userDropdown.style.display = userDropdown.style.display === 'none' ? 'block' : 'none';
                });
                document.addEventListener('click', function () {
                    userDropdown.style.display = 'none';
                });
            }

            // Mobile Navigation Drawer
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const mobileDrawer = document.getElementById('mobileDrawer');
            const mobileCloseBtn = document.getElementById('mobileCloseBtn');
            const mobileBackdrop = document.getElementById('mobileBackdrop');

            if (mobileBtn && mobileDrawer) {
                mobileBtn.addEventListener('click', () => { mobileDrawer.style.display = 'block'; });
                if (mobileCloseBtn) mobileCloseBtn.addEventListener('click', () => { mobileDrawer.style.display = 'none'; });
                if (mobileBackdrop) mobileBackdrop.addEventListener('click', () => { mobileDrawer.style.display = 'none'; });
            }

            // Search Modal
            const searchOpenBtn = document.getElementById('searchOpenBtn');
            const searchModal = document.getElementById('searchModal');
            const searchCloseBtn = document.getElementById('searchCloseBtn');
            const searchBackdrop = document.getElementById('searchBackdrop');

            if (searchOpenBtn && searchModal) {
                searchOpenBtn.addEventListener('click', () => { searchModal.style.display = 'block'; });
                if (searchCloseBtn) searchCloseBtn.addEventListener('click', () => { searchModal.style.display = 'none'; });
                if (searchBackdrop) searchBackdrop.addEventListener('click', () => { searchModal.style.display = 'none'; });
            }
        });

        // Global Wishlist Toggle via AJAX
        function toggleWishlist(productId, btnElement) {
            fetch(`/wishlist/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.added) {
                        btnElement.classList.add('active');
                        btnElement.innerHTML = '<i class="fa-solid fa-heart" style="color: #E11D48;"></i>';
                    } else {
                        btnElement.classList.remove('active');
                        btnElement.innerHTML = '<i class="fa-regular fa-heart"></i>';
                    }
                }
            })
            .catch(err => console.error(err));
        }

        // Global Quick Add to Cart via AJAX
        function quickAddToCart(productId) {
            fetch(`/cart/add`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('cartBadge');
                    if (badge) {
                        badge.innerText = data.cart_count;
                        badge.style.display = 'flex';
                    }
                    alert(data.message);
                } else {
                    alert(data.message || 'حدث خطأ أثناء الإضافة للسلة');
                }
            })
            .catch(err => console.error(err));
        }

        // Global Password Visibility Toggle
        function togglePasswordVisibility(button) {
            const wrap = button.closest('.password-input-wrap');
            if (!wrap) return;
            const input = wrap.querySelector('input');
            const icon = button.querySelector('i');
            if (!input || !icon) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
