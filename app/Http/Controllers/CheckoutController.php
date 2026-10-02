<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Show Checkout Form.
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'سلة التسوق فارغة، يرجى اختيار منتجات لإتمام الطلب.');
        }

        $items = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            if (!$product || !$product->is_active || $product->stock_quantity <= 0) {
                continue;
            }

            $qty = min($item['quantity'], $product->stock_quantity);
            $itemSubtotal = $product->price * $qty;
            $subtotal += $itemSubtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $qty,
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
                'subtotal' => $itemSubtotal,
            ];
        }

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'المنتجات في سلتك لم تعد متوفرة في المخزون.');
        }

        $shippingCost = (float) Setting::get('shipping_cost', 25);
        $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 350);

        if ($subtotal >= $freeShippingThreshold) {
            $shippingCost = 0;
        }

        $total = $subtotal + $shippingCost;

        $user = Auth::user();

        $saudiCities = [
            'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر',
            'الطائف', 'حفر الباطن', 'القصيم - بريدة', 'عنيزة', 'تبوك', 'أبها',
            'خميس مشيط', 'الأحساء - الهفوف', 'الجبيل', 'ينبع', 'حائل', 'نجران',
            'جازان', 'الباحة', 'عرعر', 'سكاكا', 'الخرج'
        ];

        return view('checkout.index', compact('items', 'subtotal', 'shippingCost', 'total', 'user', 'saudiCities'));
    }

    /**
     * Process & Place Order.
     */
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'سلة التسوق فارغة');
        }

        $request->validate([
            'customer_name' => 'required|string|max:150',
            'customer_phone' => ['required', 'regex:/^(05|\+?9665)[0-9]{8}$/'],
            'customer_email' => 'nullable|email|max:150',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cod,bank_transfer,card',
        ], [
            'customer_name.required' => 'يرجى إدخال اسم العميل',
            'customer_phone.required' => 'يرجى إدخال رقم الجوال السعودي',
            'customer_phone.regex' => 'يرجى إدخال رقم جوال سعودي صحيح (مثال: 05XXXXXXXX)',
            'city.required' => 'يرجى اختيار أو كتابة المدينة',
            'district.required' => 'يرجى إدخال اسم الحي',
            'address.required' => 'يرجى إدخال تفاصيل العنوان والشارع',
            'payment_method.required' => 'يرجى اختيار طريقة الدفع',
        ]);

        return DB::transaction(function () use ($request, $cart) {
            $subtotal = 0;
            $orderItemsData = [];

            // Verify stock and prepare items
            foreach ($cart as $key => $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (!$product || !$product->is_active) {
                    throw new \Exception("المنتج {$item['product_id']} لم يعد متوفراً في المتجر.");
                }

                $requestedQty = (int) $item['quantity'];
                if ($product->stock_quantity < $requestedQty) {
                    throw new \Exception("الكمية المطلوبة للعباية ({$product->name}) غير متوفرة في المخزون. المتبقي: {$product->stock_quantity}");
                }

                $itemSubtotal = $product->price * $requestedQty;
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $requestedQty,
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'subtotal' => $itemSubtotal,
                    'product_model' => $product,
                ];
            }

            $shippingCost = (float) Setting::get('shipping_cost', 25);
            $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 350);

            if ($subtotal >= $freeShippingThreshold) {
                $shippingCost = 0;
            }

            $total = $subtotal + $shippingCost;

            // Create Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'city' => $request->city,
                'district' => $request->district,
                'address' => $request->address,
                'notes' => $request->notes,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount_amount' => 0,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'card' ? 'paid' : 'pending',
                'status' => 'new',
            ]);

            // Save order items & decrement stock
            foreach ($orderItemsData as $itemData) {
                $productModel = $itemData['product_model'];
                unset($itemData['product_model']);

                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);

                // Inventory decrement
                $productModel->decrement('stock_quantity', $itemData['quantity']);
            }

            // Clear session cart
            session()->forget('cart');

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'تم استلام طلبكِ بنجاح! رقم طلبك هو: ' . $order->order_number);
        });
    }

    /**
     * Order Success / Confirmation Page.
     */
    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product.images'])
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
