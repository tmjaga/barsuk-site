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

if (! function_exists('minutes_to_hhmm')) {
    function minutes_to_hhmm(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        return sprintf('%02d:%02d', $hours, $mins);
    }
}

if (! function_exists('page')) {
    function page(string $key)
    {
        [$slug, $sectionKey] = explode('.', $key);

        $locale = app()->getLocale();

        $page = Cache::remember("page_{$slug}", now()->addHours(12), function () use ($slug) {
            return Page::where('slug', $slug)->with('sections.translations')->first();
        }
        );

        if (! $page) {
            return null;
        }

        $section = $page->sections->where('key', $sectionKey)->first();

        if (! $section) {
            return null;
        }

        return $section->translations->where('locale', $locale)->first()?->value;
    }
}
