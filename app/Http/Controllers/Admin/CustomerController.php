<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders');

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(int $id)
    {
        $customer = User::where('role', 'customer')
            ->with(['orders.items'])
            ->findOrFail($id);

        $totalSpent = $customer->orders()
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('total');

        return view('admin.customers.show', compact('customer', 'totalSpent'));
    }

    public function toggleStatus(int $id)
    {
        $customer = User::findOrFail($id);
        $customer->is_active = !$customer->is_active;
        $customer->save();

        $msg = $customer->is_active ? 'تم تفعيل حساب العميل بنجاح' : 'تم تعطيل حساب العميل بنجاح';
        return back()->with('success', $msg);
    }

    public function destroy(int $id)
    {
        $customer = User::findOrFail($id);
        
        // Prevent deleting admin
        if ($customer->isAdmin()) {
            return back()->with('error', 'لا يمكن حذف حساب المسؤول الرئيسي');
        }

        // Dissociate orders so invoices stay intact
        $customer->orders()->update(['user_id' => null]);
        
        // Delete wishlists
        $customer->wishlists()->delete();

        $customerName = $customer->name;
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', "تم حذف حساب العميل ({$customerName}) نهائياً من النظام.");
    }
}
