<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, int $productId)
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'customer_name' => 'required|string|max:100',
            'review_text' => 'required|string|min:5|max:1500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'rating.required' => 'يرجى اختيار عدد النجوم للتقييم',
            'customer_name.required' => 'يرجى إدخال اسمكِ الكريم',
            'review_text.required' => 'يرجى كتابة نص المراجعة أو الرأي حول المنتج',
            'review_text.min' => 'يجب أن يكون نص المراجعة 5 أحرف على الأقل',
            'image.image' => 'يجب أن يكون الملف المرفق صورة صحيحة',
            'image.max' => 'الحد الأقصى لحجم الصورة هو 3 ميغابايت',
        ]);

        $userId = Auth::id();

        // Check if user already reviewed this product
        if ($userId) {
            $alreadyReviewed = ProductReview::where('product_id', $product->id)
                ->where('user_id', $userId)
                ->exists();

            if ($alreadyReviewed) {
                return back()->with('error', 'لقد قمتِ بإضافة تقييم مسبق لهذه العباية.');
            }
        }

        // Check verified purchase
        $isVerified = false;
        $orderId = null;

        if ($userId) {
            $orderItem = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->whereIn('status', ['delivered', 'shipped', 'ready', 'processing', 'new']);
                })
                ->latest()
                ->first();

            if ($orderItem) {
                $isVerified = true;
                $orderId = $orderItem->order_id;
            }
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'image_path' => $imagePath,
            'is_verified_purchase' => $isVerified,
            'status' => 'approved', // Auto-approved or managed via dashboard
            'is_featured' => false,
        ]);

        return back()->with('success', 'شكراً لكِ! تم تسجيل تقييمكِ بنجاح وسيكون ظاهراً لجميع الزائرات.');
    }
}
