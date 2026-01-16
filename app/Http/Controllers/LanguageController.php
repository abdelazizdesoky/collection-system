<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function setLanguage(string $lang, Request $request): RedirectResponse
    {
        // Validate language
        if (! in_array($lang, ['en', 'ar'])) {
            return redirect()->back();
        }

        // Set language in session (if sessions available)
        try {
            session()->put('locale', $lang);
        } catch (\Throwable $e) {
            // ignore session write errors
        }

        // Also set app locale for this request
        app()->setLocale($lang);

        // Persist locale in a cookie as a fallback so it works without a session
        $response = redirect()->back()->with('success', 'Language changed to '.($lang === 'ar' ? 'العربية' : 'English'));

        return $response->withCookie(cookie(name: 'locale', value: $lang, minutes: 525600)); // 1 year
    }
}
