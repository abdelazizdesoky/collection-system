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
