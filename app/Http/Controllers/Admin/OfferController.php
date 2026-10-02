<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        // Products that currently have a discount (compare_at_price > price)
        $discountedProducts = Product::whereNotNull('compare_at_price')
            ->whereColumn('compare_at_price', '>', 'price')
            ->with(['category', 'images'])
            ->latest()
            ->paginate(15);

        $allProducts = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.offers.index', compact('discountedProducts', 'allProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_price' => 'required|numeric|min:1',
            'original_price' => 'required|numeric|gt:discount_price',
        ], [
            'discount_price.required' => 'يرجى إدخال السعر المخفض بعد الخصم',
            'original_price.required' => 'يرجى إدخال السعر الأصلي قبل الخصم',
            'original_price.gt' => 'يجب أن يكون السعر الأصلي أكبر من السعر المخفض',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->price = $request->discount_price;
        $product->compare_at_price = $request->original_price;
        $product->discount_percent = round((($request->original_price - $request->discount_price) / $request->original_price) * 100);
        $product->save();

        return back()->with('success', "تم تطبيق العرض على العباية ({$product->name}) بنسبة خصم {$product->discount_percent}%");
    }

    public function remove(int $productId)
    {
        $product = Product::findOrFail($productId);
        if ($product->compare_at_price) {
            // Restore price to compare_at_price and remove discount
            $product->price = $product->compare_at_price;
            $product->compare_at_price = null;
            $product->discount_percent = null;
            $product->save();
        }

        return back()->with('success', 'تم إلغاء العرض والخصم وإعادة السعر الأصلي بنجاح');
    }
}
