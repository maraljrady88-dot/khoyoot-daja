<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Customer orders list (for logged in users).
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'يرجى تسجيل الدخول لمشاهدة طلباتكِ');
        }

        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product.images'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show single order details.
     */
    public function show(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product.images'])
            ->firstOrFail();

        // If user logged in and order belongs to someone else, check access
        if (Auth::check() && !Auth::user()->isAdmin() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك باستعراض هذا الطلب');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Order Tracking Lookup Page.
     */
    public function track(Request $request)
    {
        $order = null;

        if ($request->filled('order_number') && $request->filled('phone')) {
            $phone = trim($request->phone);
            // clean phone
            $order = Order::where('order_number', trim($request->order_number))
                ->where(function ($q) use ($phone) {
                    $q->where('customer_phone', $phone)
                      ->orWhere('customer_phone', 'like', "%{$phone}%");
                })
                ->with(['items.product.images'])
                ->first();

            if (!$order) {
                return back()->with('error', 'لم يتم العثور على طلب يطابق هذا الرقم ورقم الجوال المدخلين.');
            }
        }

        return view('orders.track', compact('order'));
    }
}
