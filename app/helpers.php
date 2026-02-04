<?php

if (!function_exists('get_setting')) {
    /**
     * Get a setting value.
     */
    function get_setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}
if (!function_exists('tafqeet')) {
    /**
     * Convert numbers to Arabic words (Tafqeet).
     */
    function tafqeet($number)
    {
        if (!is_numeric($number)) return '';

        $parts = explode('.', number_format($number, 2, '.', ''));
        $pounds = (int)$parts[0];
        $piastres = (int)$parts[1];

        $formatter = new \NumberFormatter('ar_EG', \NumberFormatter::SPELLOUT);
        $poundsText = $formatter->format($pounds);
        $piastresText = $piastres > 0 ? $formatter->format($piastres) : '';

        $result = $poundsText . ' جنيه مصري';
        if ($piastres > 0) {
            $result .= ' و' . $piastresText . ' قرشاً';
        }
        
        return 'فقط ' . $result . ' لا غير';
    }
}
