<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected static int $number = 1;

    public function definition(): array
    {
        $number = self::$number++;
        $locales = config('logat.locales', ['en']);

        foreach ($locales as $locale) {
            $titles[$locale] = match ($locale) {
                'ru' => "Тестовая категория #{$number}",
                'bg' => "Тестова категория #{$number}",
                default => "Test Category #{$number}",
            };
        }

        return [
            'title' => $titles,
        ];
    }
}
