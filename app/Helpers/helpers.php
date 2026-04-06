<?php

use App\Models\Page;

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
        // key must be in <page_slug>.<section_name> format e.x. about.about_text_1
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

if (! function_exists('formatEmailContent')) {
    function formatEmailContent($html)
    {
        $map = [
            '<align-left>' => '<div style="text-align:left;">',
            '<align-center>' => '<div style="text-align:center;">',
            '<align-right>' => '<div style="text-align:right;">',
            '</align-left>' => '</div>',
            '</align-center>' => '</div>',
            '</align-right>' => '</div>',
        ];

        return str_replace(array_keys($map), array_values($map), $html);
    }
}

if (! function_exists('getManifestCssFile')) {
    function getManifestCssFile(): string
    {
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);

        return public_path('build/'.$manifest['resources/css/app.css']['file']);
    }
}

if (! function_exists('getPageTemplates')) {
    function getPageTemplates(): array
    {
        $files = glob(resource_path('views/pages/templates/*_custom.blade.php'));

        $pages = [];
        foreach ($files as $file) {
            $name = basename($file, '.blade.php');
            $title = Str::of($name)
                ->replace('-', ' ')
                ->replace('_', ' ')
                ->title();
            $pages[$name] = $title->value();
        }

        return $pages;
    }
}
