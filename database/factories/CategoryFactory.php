<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected static int $number = 1;

    public function definition(): array
    {
        return [
            'title' => 'Test Category #'.self::$number++,
        ];
    }
}
