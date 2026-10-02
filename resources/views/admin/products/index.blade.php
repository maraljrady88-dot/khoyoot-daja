@extends('layouts.admin')

@section('title', 'إدارة العبايات والمنتجات | خيوط دعجاء')
@section('page_title', 'إدارة العبايات والمنتجات')

@section('content')

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <!-- Search & Filters Form -->
            <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث باسم العباية أو كود SKU..." class="form-control" style="width: 240px; padding: 8px 14px; font-size: 0.88rem;">
                
                <select name="category_id" onchange="this.form.submit()" class="form-control" style="width: 170px; padding: 8px 14px; font-size: 0.88rem;">
                    <option value="">جميع التصنيفات</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="form-control" style="width: 150px; padding: 8px 14px; font-size: 0.88rem;">
                    <option value="">كل الحالات</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>المنشورة فقط</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>المخفية</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>مخزون منخفض</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>نفدت الكمية</option>
                </select>

                <button type="submit" class="btn btn-outline-dark btn-sm">تصفية</button>
            </form>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-gold btn-sm">
            <i class="fa-solid fa-plus"></i> إضافة عباية جديدة
        </a>
    </div>

    <!-- Products Table -->
    <div class="admin-card">
        @if($products->count() > 0)
            <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">الصورة</th>
                        <th>اسم العباية</th>
                        <th>التصنيف</th>
                        <th>السعر</th>
                        <th>المخزون</th>
                        <th>الحالة</th>
                        <th style="text-align: center;">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="table-img-thumb">
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--admin-text-main);">
                                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" style="color: inherit;">
                                        {{ $product->name }}
                                    </a>
                                </div>
                                <div style="font-size: 0.75rem; color: var(--admin-text-muted);">
                                    كود SKU: <span style="font-family: monospace;">{{ $product->sku }}</span>
                                    @if($product->is_featured)
                                        <span class="badge" style="background: #FDF4E6; color: #9A6B2F; margin-right: 6px;">الأكثر طلباً</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>
                                <strong>{{ number_format($product->price, 0) }} ر.س</strong>
                                @if($product->has_discount)
                                    <div style="font-size: 0.75rem; color: #DC2626; text-decoration: line-through;">
                                        {{ number_format($product->compare_at_price, 0) }} ر.س
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($product->stock_quantity <= 0)
                                    <span class="badge" style="background: #FEE2E2; color: #DC2626;">نفدت الكمية</span>
                                @elseif($product->stock_quantity <= ($product->low_stock_threshold ?: 3))
                                    <span class="badge" style="background: #FEF3C7; color: #D97706;">متبقي {{ $product->stock_quantity }} قطع</span>
                                @else
                                    <span style="font-weight: 600; color: #059669;">{{ $product->stock_quantity }} قطعة</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer;" title="اضغط للتغيير">
                                        @if($product->is_active)
                                            <span class="badge-status" style="background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0;">منشور بالمتجر</span>
                                        @else
                                            <span class="badge-status" style="background: #F3F4F6; color: #6B7280; border: 1px solid #E5E7EB;">مخفي</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-gold btn-sm" style="padding: 4px 10px; font-size: 0.8rem;" title="تعديل">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنتِ متأكدة من حذف هذه العباية؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background: #FEE2E2; color: #DC2626; border: none; padding: 4px 10px; font-size: 0.8rem;" title="حذف">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <div style="padding: 16px 20px;">
                {{ $products->links() }}
            </div>
        @else
            <div style="padding: 60px 20px; text-align: center; color: var(--admin-text-muted);">
                <i class="fa-solid fa-shirt" style="font-size: 3rem; color: #DFC8A8; margin-bottom: 12px;"></i>
                <p style="font-size: 1.1rem; color: var(--admin-text-main); font-weight: 600;">لا توجد عبايات مطابقة للبحث</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-gold btn-sm" style="margin-top: 14px;">إضافة أول عباية</a>
            </div>
        @endif
    </div>

@endsection
