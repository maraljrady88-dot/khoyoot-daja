@extends('layouts.admin')

@section('title', 'تعديل عباية: ' . $product->name . ' | خيوط دعجاء')
@section('page_title', 'تعديل بيانات العباية')

@section('content')

    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.products.index') }}" style="color: var(--admin-gold); font-size: 0.9rem; font-weight: 600;">
            &larr; العودة لقائمة العبايات
        </a>
        <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn btn-outline-dark btn-sm">
            <i class="fa-solid fa-eye"></i> معاينة العباية في المتجر
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="list-style: none; margin: 0; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="admin-grid-main-side">
            
            <!-- Details Column -->
            <div>
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">معلومات العباية</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label class="form-label">اسم العباية *</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="form-control">
                        </div>

                        <div class="admin-grid-2col">
                            <div class="form-group">
                                <label class="form-label">التصنيف *</label>
                                <select name="category_id" required class="form-control">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">كود SKU</label>
                                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">وصف مختصر</label>
                            <textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">الوصف الكامل</label>
                            <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">المواصفات وخامة القماش</label>
                            <textarea name="details" rows="4" class="form-control">{{ old('details', $product->details) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">الأسعار والمخزون</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-grid-2col">
                            <div class="form-group">
                                <label class="form-label">سعر البيع الحالي (ر.س) *</label>
                                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="form-label">السعر قبل الخصم إن وجد (ر.س)</label>
                                <input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}" class="form-control">
                            </div>
                        </div>

                        <div class="admin-grid-2col" style="margin-bottom: 0;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">الكمية المتوفرة في المخزون *</label>
                                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0" class="form-control">
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">تنبيه انخفاض المخزون</label>
                                <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="1" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">المقاسات والألوان</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label class="form-label">المقاسات المتاحة:</label>
                            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                @php $currentSizes = $product->sizes ?: []; @endphp
                                @foreach(['50', '52', '54', '56', '58', '60', '62'] as $sizeVal)
                                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 6px 12px; background: #FAF8F5; border: 1px solid var(--admin-border); border-radius: 6px;">
                                        <input type="checkbox" name="sizes[]" value="{{ $sizeVal }}" {{ in_array($sizeVal, old('sizes', $currentSizes)) ? 'checked' : '' }}>
                                        <span>مقاس {{ $sizeVal }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">الألوان المتاحة (مفصولة بفاصلة):</label>
                            @php $currentColors = is_array($product->colors) ? implode(', ', $product->colors) : ''; @endphp
                            <input type="text" name="colors" value="{{ old('colors', $currentColors) }}" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Existing Images Manager -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">إدارة صور العباية المرفوعة حالياً</h3>
                    </div>
                    <div class="admin-card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 14px;">
                            @foreach($product->images as $img)
                                <div style="position: relative; aspect-ratio: 3/4; background: #F6F3ED; border: 1px solid var(--admin-border); border-radius: 6px; overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 6px;">
                                    <img src="{{ $img->url }}" alt="صورة" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    
                                    @if($img->is_primary)
                                        <span style="position: absolute; top: 4px; right: 4px; background: #059669; color: #fff; font-size: 0.65rem; padding: 2px 6px; border-radius: 4px; font-weight: 700;">
                                            رئيسية
                                        </span>
                                    @endif

                                    <!-- Delete Image Button -->
                                    <button type="button" 
                                            onclick="if(confirm('حذف هذه الصورة؟')) document.getElementById('deleteImageForm_{{ $img->id }}').submit();"
                                            style="position: absolute; bottom: 4px; left: 4px; background: rgba(220,38,38,0.85); color: #fff; border: none; border-radius: 4px; width: 26px; height: 26px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Column -->
            <div>
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">خيارات النشر</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <strong>العباية مفعلة ومنشورة بالمتجر</strong>
                            </label>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <span>تمييز العباية في الصفحة الرئيسية</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-gold btn-lg" style="width: 100%;">
                            <i class="fa-solid fa-check"></i> حفظ التحديثات
                        </button>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">تغيير الصورة الرئيسية</h3>
                    </div>
                    <div class="admin-card-body">
                        <input type="file" name="primary_image" accept="image/*" class="form-control">
                        <small style="color: var(--admin-text-muted); font-size: 0.8rem; display: block; margin-top: 6px;">
                            اختاري صورة جديدة فقط إن كنتِ ترغبين بتغيير الصورة الرئيسية الحالية.
                        </small>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">إضافة صور إضافية للمعرض</h3>
                    </div>
                    <div class="admin-card-body">
                        <input type="file" name="images[]" multiple accept="image/*" class="form-control">
                    </div>
                </div>
            </div>

        </div>
    </form>

    <!-- Hidden Delete Image Forms -->
    @foreach($product->images as $img)
        <form id="deleteImageForm_{{ $img->id }}" action="{{ route('admin.products.deleteImage', $img->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

@endsection
