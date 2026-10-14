<?php

use App\Services\SettingService;

if (! function_exists('setting')) {
    /**
     * Get a setting value with fallback.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return SettingService::get($key, $default);
    }
}

if (! function_exists('setting_asset')) {
    /**
     * Get a setting asset/image URL or fallback to default asset.
     */
    function setting_asset(string $key, string $defaultAssetPath): string
    {
        $val = SettingService::get($key);
        if ($val) {
            if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
                return $val;
            }
            if (file_exists(public_path('storage/' . $val))) {
                return asset('storage/' . $val);
            }
            if (file_exists(public_path($val))) {
                return asset($val);
            }
        }
        return asset($defaultAssetPath);
    }
}
