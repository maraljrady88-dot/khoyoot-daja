<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');

        $orderQuery = Order::query();
        $startDate = null;

        if ($period === 'today') {
            $startDate = Carbon::today();
            $orderQuery->whereDate('created_at', $startDate);
        } elseif ($period === 'this_week') {
            $startDate = Carbon::now()->startOfWeek();
            $orderQuery->where('created_at', '>=', $startDate);
        } elseif ($period === 'this_month') {
            $startDate = Carbon::now()->startOfMonth();
            $orderQuery->where('created_at', '>=', $startDate);
        } elseif ($period === 'this_year') {
            $startDate = Carbon::now()->startOfYear();
            $orderQuery->where('created_at', '>=', $startDate);
        }

        $totalOrders = (clone $orderQuery)->count();
        $newOrders = (clone $orderQuery)->where('status', 'new')->count();
        $completedOrders = (clone $orderQuery)->where('status', 'delivered')->count();
        $totalSales = (clone $orderQuery)->whereNotIn('status', ['cancelled', 'refunded'])->sum('total');

        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $lowStockCount = Product::lowStock()->count();
        $outOfStockCount = Product::outOfStock()->count();
        $pendingReviewsCount = ProductReview::where('status', 'pending')->count();

        // Recent orders
        $recentOrders = Order::with('items')->latest()->take(6)->get();

        // Top selling products
        $topProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Low stock products alert list
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->with('images')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'period',
            'totalOrders',
            'newOrders',
            'completedOrders',
            'totalSales',
            'totalCustomers',
            'totalProducts',
            'lowStockCount',
            'outOfStockCount',
            'pendingReviewsCount',
            'recentOrders',
            'topProducts',
            'lowStockProducts'
        ));
    }
}
