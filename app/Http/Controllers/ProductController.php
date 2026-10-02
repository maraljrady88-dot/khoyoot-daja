<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['category', 'images', 'approvedReviews.user'])
            ->firstOrFail();

        // Increment view count
        $product->increment('views_count');

        // Related products in same category
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images'])
            ->take(4)
            ->get();

        // User verification for reviews
        $canReview = false;
        $hasVerifiedPurchase = false;
        $existingReview = null;

        if (Auth::check()) {
            $userId = Auth::id();
            $existingReview = ProductReview::where('product_id', $product->id)
                ->where('user_id', $userId)
                ->first();

            // Check if user has a delivered or valid order with this product
            $purchased = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->whereIn('status', ['delivered', 'shipped', 'ready', 'processing']);
                })->exists();

            if ($purchased) {
                $hasVerifiedPurchase = true;
            }

            // Can review if they haven't already reviewed
            $canReview = ($existingReview === null);
        }

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'canReview',
            'hasVerifiedPurchase',
            'existingReview'
        ));
    }
}
