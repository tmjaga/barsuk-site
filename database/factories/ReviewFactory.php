<?php

namespace Database\Factories;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'comment' => fake()->paragraphs(rand(1, 3), true),
            'rating' => fake()->numberBetween(3, 5),
            'status' => fake()->randomElement([
                Status::ACTIVE->value,
                Status::INACTIVE->value,
            ]),
            'ip_address' => fake()->ipv4(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => Status::ACTIVE->value,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => Status::INACTIVE->value,
        ]);
    }
}
