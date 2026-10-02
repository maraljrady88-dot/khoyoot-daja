<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'site_name',
            'site_tagline',
            'site_subtitle',
            'about_text',
            'announcement_text',
            'announcement_bg',
            'announcement_link',
            'whatsapp_number',
            'instagram_url',
            'tiktok_url',
            'snapchat_url',
            'contact_email',
            'contact_phone',
            'shipping_cost',
            'free_shipping_threshold',
            'primary_color',
            'accent_color',
        ];

        // Explicitly set checkbox state
        Setting::set('announcement_enabled', $request->has('announcement_enabled') ? '1' : '0');

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        // Handle Logo upload
        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $logoPath);
        }

        // Handle Favicon upload
        if ($request->hasFile('site_favicon')) {
            $favPath = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $favPath);
        }

        // Flush all settings cache and compiled views
        \Illuminate\Support\Facades\Cache::flush();
        try {
            \Illuminate\Support\Facades\Artisan::call('view:clear');
        } catch (\Throwable $e) {}

        return back()->with('success', 'تم حفظ وتطبيق جميع إعدادات المتجر بنجاح');
    }
}
