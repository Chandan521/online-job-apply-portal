<?php

if (!function_exists('get_browser_from_agent')) {
    function get_browser_from_agent($agent)
    {
        if (stripos($agent, 'Chrome') !== false) return 'Chrome';
        if (stripos($agent, 'Firefox') !== false) return 'Firefox';
        if (stripos($agent, 'Safari') !== false) return 'Safari';
        if (stripos($agent, 'Edge') !== false) return 'Edge';
        return 'Unknown';
    }
}

if (!function_exists('get_os_from_agent')) {
    function get_os_from_agent($agent)
    {
        if (stripos($agent, 'Windows') !== false) return 'Windows';
        if (stripos($agent, 'Android') !== false) return 'Android';
        if (stripos($agent, 'iPhone') !== false) return 'iOS';
        if (stripos($agent, 'Mac') !== false) return 'macOS';
        return 'Unknown';
    }
}
if (!function_exists('get_device_icon')) {
    function get_device_icon($os)
    {
        $os = strtolower($os);
        if (str_contains($os, 'android') || str_contains($os, 'ios') || str_contains($os, 'iphone')) {
            return 'fa-mobile-alt';
        }
        return 'fa-laptop';
    }
}
