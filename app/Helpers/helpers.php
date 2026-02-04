<?php

if (! function_exists('settings')) {
    function settings()
    {
        return Cache::rememberForever('settings', fn () => \App\Models\Setting::first());
    }
}

if (! function_exists('percentGrowth')) {
    function percentGrowth(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
