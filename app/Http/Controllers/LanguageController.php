<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    public function setLanguage(string $lang): RedirectResponse
    {
        // Validate language
        if (!in_array($lang, ['en', 'ar'])) {
            return redirect()->back();
        }

        // Set language in session
        session()->put('locale', $lang);
        
        // Also set app locale
        app()->setLocale($lang);

        // Redirect back to previous page or dashboard
        return redirect()->back()->with('success', "Language changed to " . ($lang === 'ar' ? 'العربية' : 'English'));
    }
}