<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $exists = Admin::where('email', 'admin@mail.com')
            ->where('name', 'Admin User')
            ->exists();
        if (! $exists) {
            Admin::factory()->create();
        }
    }
}
