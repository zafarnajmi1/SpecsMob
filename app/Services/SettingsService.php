<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    private const CACHE_KEY = 'system_settings';
    private const CACHE_DURATION = 3600; // 1 hour

    /**
     * Get all settings from cache or database
     */
    public function all()
    {
        return Cache::remember(
            self::CACHE_KEY,
            self::CACHE_DURATION,
            function () {
                return SystemSetting::firstOrCreate(['id' => 1]);
            }
        );
    }

    /**
     * Get a specific setting by key
     */
    public function get($key, $default = null)
    {
        $settings = $this->all();
        return $settings->$key ?? $default;
    }

    /**
     * Clear the settings cache
     */
    public function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get settings collection
     */
    public function getSettings()
    {
        return $this->all();
    }
}
