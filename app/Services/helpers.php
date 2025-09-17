<?php

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        $settings = Cache::rememberForever('global_settings', function () {
            return Setting::pluck('value', 'name')->toArray();
        });

        return $settings[$key] ?? $default;
    }
}

if (!function_exists('clear_settings_cache')) {
    function clear_settings_cache()
    {
        Cache::forget('global_settings');
    }
}

if (!function_exists('render_telegram_template')) {
    function render_telegram_template($settingKey, $data = [])
    {
        $template = setting($settingKey);
        if (!$template) return null;

        foreach ($data as $k => $v) {
            $template = str_replace('{' . $k . '}', $v, $template);
        }

        return $template;
    }
}
