<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->take(6)->get();
        
        $featuredProducts = Product::active()
            ->featured()
            ->with(['category', 'images'])
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::active()
            ->with(['category', 'images'])
            ->latest()
            ->take(8)
            ->get();

        $discountedProducts = Product::active()
            ->whereNotNull('compare_at_price')
            ->whereColumn('compare_at_price', '>', 'price')
            ->with(['category', 'images'])
            ->latest()
            ->take(4)
            ->get();

        $featuredReviews = ProductReview::approved()
            ->where('rating', '>=', 4)
            ->with(['product'])
            ->latest()
            ->take(6)
            ->get();

        $branches = Branch::active()->take(3)->get();

        return view('home', compact(
            'banners',
            'categories',
            'featuredProducts',
            'latestProducts',
            'discountedProducts',
            'featuredReviews',
            'branches'
        ));
    }
}
