<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected const CACHE_KEY = 'eppms_app_settings';
    protected const CACHE_TTL = 86400; // 24 hours

    /**
     * Get all settings as a key => value array from cache.
     */
    public static function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            try {
                return Setting::all()->keyBy('key')->map(function ($item) {
                    return $item->value ?? $item->name;
                })->toArray();
            } catch (\Throwable $e) {
                return [];
            }
        });
    }

    /**
     * Get a setting by key with a fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = self::all();
        if (array_key_exists($key, $settings) && !is_null($settings[$key]) && $settings[$key] !== '') {
            return $settings[$key];
        }

        return $default;
    }

    /**
     * Set/update a setting and clear cache.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'text', ?string $name = null): Setting
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
                'name' => $name ?? $key,
                'active' => true,
            ]
        );

        self::clearCache();

        return $setting;
    }

    /**
     * Clear settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
