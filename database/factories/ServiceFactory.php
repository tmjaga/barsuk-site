<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected static int $number = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $number = self::$number++;
        $locales = config('logat.locales', ['en']);

        foreach ($locales as $locale) {
            $titles[$locale] = match ($locale) {
                'ru' => "Тестовый сервис #{$number}",
                'bg' => "Тестов сервис #{$number}",
                default => "Test Service #{$number}",
            };

            $descriptions[$locale] = $this->faker->paragraph();
        }

        return [
            'category_id' => Category::query()->inRandomOrder()->value('id'),
            'title' => $titles,
            'description' => $descriptions,
            'duration' => $this->faker->numberBetween(10, 120),
            'price' => $this->faker->randomFloat(2, 10, 200),
        ];
    }
}
