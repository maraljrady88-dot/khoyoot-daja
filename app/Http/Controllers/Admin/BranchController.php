<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('sort_order')->get();
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:300',
            'phone' => 'nullable|string|max:50',
            'google_maps_url' => 'nullable|string|max:500',
            'working_hours' => 'nullable|string|max:150',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        Branch::create([
            'name' => $request->name,
            'city' => $request->city,
            'address' => $request->address,
            'phone' => $request->phone,
            'google_maps_url' => $request->google_maps_url,
            'working_hours' => $request->working_hours,
            'sort_order' => $request->sort_order ?: 0,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'تمت إضافة الفرع بنجاح');
    }

    public function edit(int $id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, int $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:300',
            'phone' => 'nullable|string|max:50',
            'google_maps_url' => 'nullable|string|max:500',
            'working_hours' => 'nullable|string|max:150',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $branch->update([
            'name' => $request->name,
            'city' => $request->city,
            'address' => $request->address,
            'phone' => $request->phone,
            'google_maps_url' => $request->google_maps_url,
            'working_hours' => $request->working_hours,
            'sort_order' => $request->sort_order ?: 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'تم تحديث بيانات الفرع بنجاح');
    }

    public function toggleActive(int $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->is_active = !$branch->is_active;
        $branch->save();

        return back()->with('success', 'تم تغيير حالة ظهور الفرع');
    }

    public function destroy(int $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', 'تم حذف الفرع بنجاح');
    }
}
