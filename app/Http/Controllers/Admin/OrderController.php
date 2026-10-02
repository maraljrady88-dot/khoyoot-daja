<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items']);

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                  ->orWhere('customer_name', 'like', "%{$term}%")
                  ->orWhere('customer_phone', 'like', "%{$term}%")
                  ->orWhere('customer_email', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $statuses = Order::STATUSES;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(int $id)
    {
        $order = Order::with(['items.product.images', 'user'])->findOrFail($id);
        $statuses = Order::STATUSES;

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:new,processing,ready,shipped,delivered,cancelled,refunded',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // If cancelled and wasn't cancelled before, restore stock
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
                }
            }
        }
        // If uncancelled, deduct stock again
        elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->decrement('stock_quantity', $item->quantity);
                }
            }
        }

        $order->status = $newStatus;
        if ($request->filled('tracking_number')) {
            $order->tracking_number = $request->tracking_number;
        }
        $order->save();

        return back()->with('success', 'تم تحديث حالة الطلب إلى: ' . (Order::STATUSES[$newStatus]['label'] ?? $newStatus));
    }
}
