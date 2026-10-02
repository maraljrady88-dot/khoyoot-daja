@extends('layouts.admin')

@section('title', 'لوحة التحكم والإحصائيات | خيوط دعجاء')
@section('page_title', 'الرئيسية والإحصائيات')

@section('content')

    <!-- Period Filter Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 10px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="fa-regular fa-calendar-days" style="color: var(--admin-gold); font-size: 1.1rem;"></i>
            <span style="font-weight: 600; font-size: 0.95rem;">الفترة الزمنية للإحصائيات:</span>
        </div>

        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.dashboard', ['period' => 'all']) }}" class="btn btn-sm {{ $period === 'all' ? 'btn-gold' : 'btn-outline-dark' }}">
                كل الأوقات
            </a>
            <a href="{{ route('admin.dashboard', ['period' => 'today']) }}" class="btn btn-sm {{ $period === 'today' ? 'btn-gold' : 'btn-outline-dark' }}">
                اليوم
            </a>
            <a href="{{ route('admin.dashboard', ['period' => 'this_week']) }}" class="btn btn-sm {{ $period === 'this_week' ? 'btn-gold' : 'btn-outline-dark' }}">
                هذا الأسبوع
            </a>
            <a href="{{ route('admin.dashboard', ['period' => 'this_month']) }}" class="btn btn-sm {{ $period === 'this_month' ? 'btn-gold' : 'btn-outline-dark' }}">
                هذا الشهر
            </a>
            <a href="{{ route('admin.dashboard', ['period' => 'this_year']) }}" class="btn btn-sm {{ $period === 'this_year' ? 'btn-gold' : 'btn-outline-dark' }}">
                هذا العام
            </a>
        </div>
    </div>

    <!-- Stats Grid (Real Data from MySQL) -->
    <div class="stats-grid">
        <!-- Total Sales -->
        <div class="stat-card">
            <div class="stat-info">
                <h4>إجمالي المبيعات</h4>
                <div class="stat-value">
                    {{ number_format($totalSales, 0) }} <small style="font-size: 0.9rem; font-weight: normal; color: var(--admin-gold);">ر.س</small>
                </div>
            </div>
            <div class="stat-icon gold">
                <i class="fa-solid fa-coins"></i>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="stat-card">
            <div class="stat-info">
                <h4>إجمالي الطلبات</h4>
                <div class="stat-value">{{ $totalOrders }}</div>
            </div>
            <div class="stat-icon blue">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
        </div>

        <!-- New Orders -->
        <div class="stat-card">
            <div class="stat-info">
                <h4>طلبات جديدة بحاجة للتجهيز</h4>
                <div class="stat-value" style="color: #D97706;">{{ $newOrders }}</div>
            </div>
            <div class="stat-icon gold">
                <i class="fa-solid fa-bell"></i>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="stat-card">
            <div class="stat-info">
                <h4>طلبات مكتملة ومسلمة</h4>
                <div class="stat-value" style="color: #059669;">{{ $completedOrders }}</div>
            </div>
            <div class="stat-icon green">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="stat-card">
            <div class="stat-info">
                <h4>عدد العميلات المسجلات</h4>
                <div class="stat-value">{{ $totalCustomers }}</div>
            </div>
            <div class="stat-icon purple">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Total Products -->
        <div class="stat-card">
            <div class="stat-info">
                <h4>إجمالي موديلات العبايات</h4>
                <div class="stat-value">{{ $totalProducts }}</div>
            </div>
            <div class="stat-icon blue">
                <i class="fa-solid fa-shirt"></i>
            </div>
        </div>

        <!-- Low Stock Warning -->
        <div class="stat-card">
            <div class="stat-info">
                <h4>عبايات قاربت على النفاد</h4>
                <div class="stat-value" style="{{ $lowStockCount > 0 ? 'color: #D97706;' : '' }}">{{ $lowStockCount }}</div>
            </div>
            <div class="stat-icon gold">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="stat-card">
            <div class="stat-info">
                <h4>عبايات نفدت من المخزون</h4>
                <div class="stat-value" style="{{ $outOfStockCount > 0 ? 'color: #DC2626;' : '' }}">{{ $outOfStockCount }}</div>
            </div>
            <div class="stat-icon red">
                <i class="fa-solid fa-ban"></i>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
        <a href="{{ route('admin.products.create') }}" class="btn btn-gold btn-sm">
            <i class="fa-solid fa-plus"></i> إضافة عباية جديدة
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'new']) }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-box"></i> مشاهدة الطلبات الجديدة ({{ $newOrders }})
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-dark btn-sm">
            <i class="fa-solid fa-star"></i> مراجعة التقييمات
        </a>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-dark btn-sm">
            <i class="fa-solid fa-gear"></i> إعدادات المتجر
        </a>
    </div>

    <div class="admin-grid-dashboard">
        
        <!-- Recent Orders Table -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">آخر الطلبات المستلمة</h3>
                <a href="{{ route('admin.orders.index') }}" style="font-size: 0.85rem; color: var(--admin-gold); font-weight: 600;">
                    مشاهدة جميع الطلبات &larr;
                </a>
            </div>

            @if($recentOrders->count() > 0)
                <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>المدينة</th>
                            <th>المجموع</th>
                            <th>الحالة</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td style="font-weight: 700;">{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->city }}</td>
                                <td style="font-weight: 600;">{{ number_format($order->total, 0) }} ر.س</td>
                                <td>
                                    @php $st = $order->status_info; @endphp
                                    <span class="badge-status {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }}" style="border: 1px solid;">
                                        {{ $st['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-gold btn-sm" style="padding: 4px 10px; font-size: 0.8rem;">
                                        التفاصيل
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            @else
                <div style="padding: 40px; text-align: center; color: var(--admin-text-muted);">
                    لا توجد بيانات حتى الآن.
                </div>
            @endif
        </div>

        <!-- Side column: Low Stock Alerts & Top Selling -->
        <div>
            <!-- Low stock alert box -->
            <div class="admin-card" style="margin-bottom: 24px;">
                <div class="admin-card-header" style="background: #FEF3C7; border-bottom-color: #FDE68A;">
                    <h3 class="admin-card-title" style="color: #92400E; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        تنبيه المخزون المنخفض
                    </h3>
                </div>
                <div class="admin-card-body" style="padding: 16px 20px;">
                    @if($lowStockProducts->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach($lowStockProducts as $p)
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--admin-border);">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <img src="{{ $p->main_image_url }}" alt="{{ $p->name }}" class="table-img-thumb" style="width: 36px; height: 48px;">
                                        <div>
                                            <a href="{{ route('admin.products.edit', $p->id) }}" style="font-size: 0.88rem; font-weight: 600; color: var(--admin-text-main);">
                                                {{ $p->name }}
                                            </a>
                                            <div style="font-size: 0.75rem; color: var(--admin-text-muted);">كود: {{ $p->sku }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge" style="background: #FEE2E2; color: #DC2626; font-size: 0.75rem;">
                                            متبقي {{ $p->stock_quantity }} فقط
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; color: #059669; font-size: 0.88rem; padding: 10px 0;">
                            <i class="fa-solid fa-circle-check"></i> جميع العبايات متوفرة بكميات كافية في المخزون.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">أكثر العبايات طلباً ومبيعاً</h3>
                </div>
                <div class="admin-card-body" style="padding: 16px 20px;">
                    @if($topProducts->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach($topProducts as $top)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid var(--admin-border);">
                                    <div>
                                        <div style="font-size: 0.88rem; font-weight: 600; color: var(--admin-text-main);">
                                            {{ $top->product_name }}
                                        </div>
                                        <div style="font-size: 0.78rem; color: var(--admin-text-muted);">
                                            إيرادات: {{ number_format($top->total_revenue, 0) }} ر.س
                                        </div>
                                    </div>
                                    <span style="font-weight: 700; color: var(--admin-gold); font-size: 0.95rem;">
                                        {{ $top->total_qty }} مبيعة
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; color: var(--admin-text-muted); font-size: 0.88rem; padding: 10px 0;">
                            لا توجد بيانات حتى الآن.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection
