<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReview::with(['product', 'user']);

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->rating);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('customer_name', 'like', "%{$term}%")
                  ->orWhere('review_text', 'like', "%{$term}%")
                  ->orWhereHas('product', function ($pQ) use ($term) {
                      $pQ->where('name', 'like', "%{$term}%");
                  });
            });
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $review = ProductReview::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->status = $request->status;
        $review->save();

        return back()->with('success', 'تم تحديث حالة المراجعة بنجاح');
    }

    public function toggleFeatured(int $id)
    {
        $review = ProductReview::findOrFail($id);
        $review->is_featured = !$review->is_featured;
        $review->save();

        $msg = $review->is_featured ? 'تم تمييز التقييم للعرض في الصفحة الرئيسية' : 'تمت إزالة تمييز التقييم';
        return back()->with('success', $msg);
    }

    public function destroy(int $id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();

        return back()->with('success', 'تم حذف التقييم بنجاح');
    }
}
