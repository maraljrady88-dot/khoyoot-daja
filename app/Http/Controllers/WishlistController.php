<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $products = Auth::user()->wishlistProducts()->with(['images', 'category'])->get();
        } else {
            $wishlistIds = session()->get('wishlist', []);
            $products = Product::whereIn('id', $wishlistIds)->with(['images', 'category'])->get();
        }

        return view('wishlist.index', compact('products'));
    }

    public function toggle(Request $request, int $productId)
    {
        $product = Product::findOrFail($productId);
        $added = false;

        if (Auth::check()) {
            $userId = Auth::id();
            $existing = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

            if ($existing) {
                $existing->delete();
                $added = false;
                $message = 'تمت إزالة العباية من قائمة المفضلة';
            } else {
                Wishlist::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                ]);
                $added = true;
                $message = 'تمت إضافة العباية إلى قائمة المفضلة بنجاح';
            }
        } else {
            $wishlist = session()->get('wishlist', []);
            if (in_array($productId, $wishlist)) {
                $wishlist = array_values(array_diff($wishlist, [$productId]));
                $added = false;
                $message = 'تمت إزالة العباية من قائمة المفضلة';
            } else {
                $wishlist[] = $productId;
                $added = true;
                $message = 'تمت إضافة العباية إلى قائمة المفضلة بنجاح';
            }
            session()->put('wishlist', $wishlist);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
