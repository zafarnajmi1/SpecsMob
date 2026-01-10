<?php

if (!function_exists('settings')) {
    /**
     * Get system settings
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return \App\Models\SystemSetting|mixed
     */
    function settings($key = null, $default = null)
    {
        $service = app('settings');

        if ($key === null) {
            return $service->all();
        }

        return $service->get($key, $default);
    }
}

if (!function_exists('setting')) {
    /**
     * Alias for settings() - Get a specific setting
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return settings($key, $default);
    }
}
