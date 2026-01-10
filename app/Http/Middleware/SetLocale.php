<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: session -> cookie -> request header
        $allowed = ['en', 'ar'];

        $locale = null;
        try {
            if (session()->has('locale')) {
                $locale = strtolower(trim((string) session('locale')));
            } elseif ($request->cookies->has('locale')) {
                $locale = strtolower(trim((string) $request->cookies->get('locale')));
            }
        } catch (\Throwable $e) {
            $locale = null;
        }

        if ($locale && in_array($locale, $allowed, true)) {
            app()->setLocale($locale);
        } else {
            // fallback to browser preferred language when available
            $preferred = $request->getPreferredLanguage();
            if ($preferred) {
                $preferred = substr($preferred, 0, 2);
                if (in_array($preferred, $allowed, true)) {
                    app()->setLocale($preferred);
                }
            }
        }

        return $next($request);
    }
}
