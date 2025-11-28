<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $exists = User::where('email', 'test@mail.com')
            ->where('name', 'Test User')
            ->exists();

        if (! $exists) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@mail.com',
            ]);
        }

        $this->call(AdminSeeder::class);
    }
}
