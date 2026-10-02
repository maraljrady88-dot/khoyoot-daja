<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'title' => 'nullable|string|max:150',
            'subtitle' => 'nullable|string|max:250',
            'badge_text' => 'nullable|string|max:100',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:250',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'image_path' => $path,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'badge_text' => $request->badge_text,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url ?: '/shop',
            'sort_order' => $request->sort_order ?: 0,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'تمت إضافة البنر بنجاح');
    }

    public function edit(int $id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, int $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'title' => 'nullable|string|max:150',
            'subtitle' => 'nullable|string|max:250',
            'badge_text' => 'nullable|string|max:100',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:250',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        $banner->badge_text = $request->badge_text;
        $banner->button_text = $request->button_text;
        $banner->button_url = $request->button_url ?: '/shop';
        $banner->sort_order = $request->sort_order ?: 0;
        $banner->is_active = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $banner->image_path = $request->file('image')->store('banners', 'public');
        }

        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'تم تحديث بيانات البنر بنجاح');
    }

    public function toggleActive(int $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return back()->with('success', 'تم تغيير حالة البنر');
    }

    public function destroy(int $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'تم حذف البنر بنجاح');
    }
}
