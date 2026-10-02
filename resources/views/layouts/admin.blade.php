<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'لوحة التحكم | خيوط دعجاء للعبايات الخليجية')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logo.webp">
    
    <!-- Thmanyah Arabic Font -->
    <link rel="stylesheet" href="/css/thmanyah-font.css">
    
    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Storefront and Admin Styles -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/admin.css">
    
    @stack('styles')
</head>
<body class="admin-body">

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-brand">
            <img src="{{ asset('images/logo.webp') }}" alt="خيوط دعجاء" class="admin-sidebar-logo">
            <div>
                <div class="admin-brand-text">{{ \App\Models\Setting::get('site_name', 'خيوط دعجاء') }}</div>
                <span class="admin-badge">لوحة الإدارة</span>
            </div>
        </div>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>الرئيسية والإحصائيات</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="admin-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fa-solid fa-shirt"></i>
                <span>إدارة المنتجات والعبايات</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i>
                <span>التصنيفات والأقسام</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="admin-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box-open"></i>
                <span>الطلبات والمبيعات</span>
                @php
                    $newOrdersCount = \App\Models\Order::where('status', 'new')->count();
                @endphp
                @if($newOrdersCount > 0)
                    <span style="margin-right: auto; background: #DC2626; color: #fff; font-size: 0.72rem; padding: 2px 7px; border-radius: 10px; font-weight: 700;">
                        {{ $newOrdersCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.customers.index') }}" class="admin-nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span>العملاء والمستخدمين</span>
            </a>

            <a href="{{ route('admin.offers.index') }}" class="admin-nav-item {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-percent"></i>
                <span>العروض والتخفيضات</span>
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="admin-nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="fa-solid fa-star"></i>
                <span>التقييمات والمراجعات</span>
            </a>

            <a href="{{ route('admin.banners.index') }}" class="admin-nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="fa-regular fa-image"></i>
                <span>البنرات والواجهة</span>
            </a>

            <a href="{{ route('admin.branches.index') }}" class="admin-nav-item {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                <i class="fa-solid fa-store"></i>
                <span>فروع المتجر</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders"></i>
                <span>إعدادات المتجر العامة</span>
            </a>
        </nav>

        <div style="padding: 16px; border-top: 1px solid rgba(255,255,255,0.08);">
            <a href="{{ route('home') }}" target="_blank" style="display: flex; align-items: center; gap: 8px; color: #DFC8A8; font-size: 0.85rem; font-weight: 500;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>زيارة المتجر للعملاء</span>
            </a>
        </div>
    </aside>

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="admin-backdrop" id="adminBackdrop"></div>

    <!-- Admin Main Body -->
    <div class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div class="admin-topbar-left">
                <button type="button" id="sidebarToggleBtn" aria-label="تبديل القائمة الجانبية">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div style="font-weight: 600; font-size: 1.05rem; color: var(--admin-text-main);">
                    @yield('page_title', 'لوحة التحكم')
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-dark btn-sm" title="عرض المتجر">
                    <i class="fa-solid fa-eye"></i> <span class="d-none-sm">عرض المتجر</span>
                </a>

                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #FAF8F5; border: 1px solid #DFC8A8; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #BFA175; flex-shrink: 0;">
                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="admin-user-name" style="font-size: 0.88rem; font-weight: 600;">
                        {{ Auth::user()->name }}
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-sm" style="background: #FEE2E2; color: #DC2626; border: none; padding: 7px 12px;" title="تسجيل الخروج">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash messages -->
        <div style="padding: 20px 28px 0;">
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
        </div>

        <!-- Main Admin Content -->
        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('sidebarToggleBtn');
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminBackdrop');

            function toggleSidebar() {
                sidebar.classList.toggle('show');
                if (backdrop) {
                    backdrop.classList.toggle('show');
                }
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                if (backdrop) {
                    backdrop.classList.remove('show');
                }
            }

            if (toggle && sidebar) {
                toggle.addEventListener('click', toggleSidebar);
            }
            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
