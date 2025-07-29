<?php
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

if (!function_exists('setting')) {
    function setting($key, $default = null) {
        static $settings = null;

        // Optional protection: skip DB if connection not ready
        if (!app()->bound('db') || !Schema::hasTable('settings')) {
            return $default;
        }

        if ($settings === null) {
            $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        }

        $fallbacks = [
            'site_name' => env('APP_NAME', 'Laravel'),
            'site_email' => env('MAIL_FROM_ADDRESS', 'admin@example.com'),
            'site_logo' => 'defaults/logo.png',
            'site_favicon' => 'defaults/favicon.png',
            'mail_enabled' => '1',
            'maintenance_mode' => 'off',
        ];

        return $settings[$key] ?? $fallbacks[$key] ?? $default;
    }
}

