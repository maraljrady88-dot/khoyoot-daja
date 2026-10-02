<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get cart array from session.
     */
    private function getCart(): array
    {
        return session()->get('cart', []);
    }

    /**
     * Save cart array to session.
     */
    private function saveCart(array $cart): void
    {
        session()->put('cart', $cart);
    }

    /**
     * View Cart Page.
     */
    public function index()
    {
        $cart = $this->getCart();
        $subtotal = 0;
        $items = [];

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            if (!$product || !$product->is_active) {
                // Remove if product deleted or disabled
                unset($cart[$key]);
                continue;
            }

            // Adjust quantity if exceeds stock
            $itemQty = min($item['quantity'], max(1, $product->stock_quantity));
            $itemSubtotal = $product->price * $itemQty;
            $subtotal += $itemSubtotal;

            $items[$key] = [
                'key' => $key,
                'product' => $product,
                'quantity' => $itemQty,
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
                'subtotal' => $itemSubtotal,
            ];
        }

        $this->saveCart($cart);

        $shippingCost = (float) Setting::get('shipping_cost', 25);
        $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 350);

        if ($subtotal >= $freeShippingThreshold && $subtotal > 0) {
            $shippingCost = 0;
        }

        $total = $subtotal > 0 ? ($subtotal + $shippingCost) : 0;

        return view('cart.index', compact('items', 'subtotal', 'shippingCost', 'total', 'freeShippingThreshold'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock_quantity <= 0) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'عذراً، هذا المنتج غير متوفر حالياً'], 422);
            }
            return back()->with('error', 'عذراً، هذا المنتج غير متوفر حالياً');
        }

        $quantity = (int) ($request->quantity ?: 1);
        $size = $request->size ?: ($product->sizes[0] ?? null);
        $color = $request->color ?: ($product->colors[0] ?? null);

        // Cart item unique key (product_id + size + color)
        $cartKey = $product->id . '_' . ($size ?: 'nosize') . '_' . ($color ?: 'nocolor');

        $cart = $this->getCart();

        if (isset($cart[$cartKey])) {
            $newQty = $cart[$cartKey]['quantity'] + $quantity;
            if ($newQty > $product->stock_quantity) {
                $newQty = $product->stock_quantity;
            }
            $cart[$cartKey]['quantity'] = $newQty;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'quantity' => min($quantity, $product->stock_quantity),
                'size' => $size,
                'color' => $color,
            ];
        }

        $this->saveCart($cart);

        $totalCartCount = array_sum(array_column($cart, 'quantity'));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة العباية إلى سلة التسوق بنجاح',
                'cart_count' => $totalCartCount,
            ]);
        }

        return back()->with('success', 'تمت إضافة العباية إلى سلة التسوق بنجاح');
    }

    /**
     * Update item quantity.
     */
    public function update(Request $request, string $key)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getCart();

        if (!isset($cart[$key])) {
            return back()->with('error', 'المنتج غير موجود في السلة');
        }

        $product = Product::find($cart[$key]['product_id']);
        if (!$product) {
            unset($cart[$key]);
            $this->saveCart($cart);
            return back()->with('error', 'المنتج غير متوفر');
        }

        $qty = min((int) $request->quantity, $product->stock_quantity);
        $cart[$key]['quantity'] = $qty;

        $this->saveCart($cart);

        return back()->with('success', 'تم تحديث الكمية بنجاح');
    }

    /**
     * Remove item from cart.
     */
    public function remove(string $key)
    {
        $cart = $this->getCart();

        if (isset($cart[$key])) {
            unset($cart[$key]);
            $this->saveCart($cart);
        }

        return back()->with('success', 'تم حذف المنتج من السلة');
    }

    /**
     * Clear cart.
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'تم إفراغ السلة');
    }
}
