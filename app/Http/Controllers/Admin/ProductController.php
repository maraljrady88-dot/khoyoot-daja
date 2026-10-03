<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'low_stock') {
                $query->lowStock();
            } elseif ($request->status === 'out_of_stock') {
                $query->outOfStock();
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|string', // comma separated in input
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'يرجى كتابة اسم العباية / المنتج.',
            'category_id.required' => 'يرجى اختيار القسم.',
            'category_id.exists' => 'القسم المختار غير موجود.',
            'price.required' => 'يرجى كتابة سعر البيع.',
            'price.numeric' => 'يجب أن يكون السعر قيمة عددية.',
            'price.min' => 'سعر البيع لا يمكن أن يكون سالباً.',
            'compare_at_price.numeric' => 'السعر قبل الخصم يجب أن يكون قيمة عددية.',
            'compare_at_price.min' => 'السعر قبل الخصم لا يمكن أن يكون سالباً.',
            'stock_quantity.required' => 'يرجى إدخال كمية المخزون.',
            'stock_quantity.integer' => 'كمية المخزون يجب أن تكون رقماً صحيحاً.',
            'primary_image.image' => 'الملف يجب أن يكون صورة صالحة.',
            'primary_image.max' => 'حجم الصورة لا يجب أن يتجاوز 5 ميجابايت.',
        ]);

        $colors = [];
        if ($request->filled('colors')) {
            $colors = array_filter(array_map('trim', explode(',', $request->colors)));
        }

        // Compare at price should be higher than price to qualify as a discount
        $compareAtPrice = ($request->filled('compare_at_price') && (float) $request->compare_at_price > (float) $request->price)
            ? $request->compare_at_price
            : null;

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'sku' => $request->sku ?: ('DJ-' . strtoupper(Str::random(6))),
            'price' => $request->price,
            'compare_at_price' => $compareAtPrice,
            'stock_quantity' => $request->stock_quantity,
            'low_stock_threshold' => $request->low_stock_threshold ?: 3,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'details' => $request->details,
            'sizes' => $request->sizes ?: ['52', '54', '56', '58', '60'],
            'colors' => $colors,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        // Primary image
        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => true,
                'sort_order' => 1,
            ]);
        }

        // Additional gallery images
        if ($request->hasFile('images')) {
            $order = 2;
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => false,
                    'sort_order' => $order++,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'تمت إضافة العباية بنجاح إلى المتجر');
    }

    public function edit(int $id)
    {
        $product = Product::with(['images'])->findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'يرجى كتابة اسم العباية / المنتج.',
            'category_id.required' => 'يرجى اختيار القسم.',
            'category_id.exists' => 'القسم المختار غير موجود.',
            'price.required' => 'يرجى كتابة سعر البيع.',
            'price.numeric' => 'يجب أن يكون السعر قيمة عددية.',
            'price.min' => 'سعر البيع لا يمكن أن يكون سالباً.',
            'compare_at_price.numeric' => 'السعر قبل الخصم يجب أن يكون قيمة عددية.',
            'compare_at_price.min' => 'السعر قبل الخصم لا يمكن أن يكون سالباً.',
            'stock_quantity.required' => 'يرجى إدخال كمية المخزون.',
            'stock_quantity.integer' => 'كمية المخزون يجب أن تكون رقماً صحيحاً.',
            'primary_image.image' => 'الملف يجب أن يكون صورة صالحة.',
            'primary_image.max' => 'حجم الصورة لا يجب أن يتجاوز 5 ميجابايت.',
        ]);

        $colors = [];
        if ($request->filled('colors')) {
            $colors = array_filter(array_map('trim', explode(',', $request->colors)));
        }

        $compareAtPrice = ($request->filled('compare_at_price') && (float) $request->compare_at_price > (float) $request->price)
            ? $request->compare_at_price
            : null;

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'sku' => $request->sku ?: $product->sku,
            'price' => $request->price,
            'compare_at_price' => $compareAtPrice,
            'stock_quantity' => $request->stock_quantity,
            'low_stock_threshold' => $request->low_stock_threshold ?: 3,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'details' => $request->details,
            'sizes' => $request->sizes ?: $product->sizes,
            'colors' => $colors,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('products', 'public');

            $currentPrimary = ProductImage::where('product_id', $product->id)
                ->where('is_primary', true)
                ->first();

            if ($currentPrimary) {
                // Delete previous file from storage if it exists
                if (Storage::disk('public')->exists($currentPrimary->image_path)) {
                    Storage::disk('public')->delete($currentPrimary->image_path);
                }
                $currentPrimary->update([
                    'image_path' => $path,
                ]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => true,
                    'sort_order' => 1,
                ]);
            }
        }

        if ($request->hasFile('images')) {
            $maxOrder = ProductImage::where('product_id', $product->id)->max('sort_order') ?: 1;
            foreach ($request->file('images') as $file) {
                $maxOrder++;
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => false,
                    'sort_order' => $maxOrder,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث بيانات العباية بنجاح');
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'تم حذف العباية بنجاح');
    }

    public function toggleActive(int $id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        $statusMsg = $product->is_active ? 'تم نشر العباية وإتاحتها في المتجر' : 'تم إخفاء العباية من المتجر';
        return back()->with('success', $statusMsg);
    }

    public function deleteImage(int $imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        $wasPrimary = $image->is_primary;
        $productId = $image->product_id;
        $image->delete();

        // If primary was deleted, promote first remaining image
        if ($wasPrimary) {
            $next = ProductImage::where('product_id', $productId)->orderBy('sort_order', 'asc')->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'تم حذف الصورة بنجاح');
    }
}
