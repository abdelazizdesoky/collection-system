<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_activity' => 'nullable|string|max:255',
            'company_phone' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
            'company_logo' => 'nullable|image|max:2048',
        ]);

        foreach ($validated as $key => $value) {
            if ($key === 'company_logo' && $request->hasFile('company_logo')) {
                $path = $request->file('company_logo')->store('settings', 'public');
                Setting::set($key, $path);
            } elseif ($key !== 'company_logo') {
                Setting::set($key, $value);
            }
        }

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
