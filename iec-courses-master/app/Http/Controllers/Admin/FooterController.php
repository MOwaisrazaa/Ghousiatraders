<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterSetting;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index()
    {
        $footer = FooterSetting::getSettings();
        return view('admin.footer.index', compact('footer'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_tagline' => 'required|string|max:255',
            'brand_description' => 'required|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'address' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:30',
            'copyright_name' => 'required|string|max:255',
            'copyright_url' => 'required|url',
            'footer_text' => 'required|string',
        ]);

        $footer = FooterSetting::getSettings();
        $footer->update($validated);

        return redirect()->route('admin.footer.index')->with('success', 'Footer settings updated successfully!');
    }
}
