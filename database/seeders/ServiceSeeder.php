<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            echo "No categories found. Please create some categories first.\n";
            return;
        }

        Service::factory(5)->create();
        /*
        Service::factory(5)->make()->each(function ($service) use ($categories) {
            $service->category_id = $categories->random()->id;
            $service->save();
        });
        */

        echo "Services seeded successfully!\n";
    }
}
