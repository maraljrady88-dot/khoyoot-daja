<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $aboutText = Setting::get('about_text');
        $branches = Branch::active()->get();
        return view('pages.about', compact('aboutText', 'branches'));
    }

    public function branches()
    {
        $branches = Branch::active()->get();
        return view('pages.branches', compact('branches'));
    }

    public function contact()
    {
        $branches = Branch::active()->get();
        return view('pages.contact', compact('branches'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:150',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'يرجى كتابة الاسم الكريم',
            'phone.required' => 'يرجى كتابة رقم الجوال للتواصل',
            'message.required' => 'يرجى كتابة نص الرسالة أو الاستفسار',
        ]);

        ContactMessage::create($request->only('name', 'phone', 'email', 'subject', 'message'));

        return back()->with('success', 'شكراً لتواصلكِ مع خيوط دعجاء. تم استلام رسالتكِ وسيقوم فريقنا بالرد عليكِ في أقرب وقت.');
    }
}
