<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        Review::factory()
            ->count(7)
            ->approved()
            ->create();

        Review::factory()
            ->count(3)
            ->pending()
            ->create();
    }
}
