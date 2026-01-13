<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'names' => sprintf(
                '%s %s',
                $this->faker->firstName(),
                $this->faker->lastName()
            ),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(OrderStatus::cases()),
            'order_start' => $this->faker->dateTimeBetween('now', '+1 month'),
            'order_end' => null,
        ];
    }
}
