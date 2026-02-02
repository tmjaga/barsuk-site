<?php
if (! function_exists('settings')) {
    function settings()
    {
        return Cache::rememberForever('settings', fn () => \App\Models\Setting::first());
    }
}
