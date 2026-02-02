<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->updateOrInsert(
            ['id' => 1],
            [
                'work_from' => '09:00:00',
                'work_to' => '18:00:00',
                'break_minutes' => 30,
                'slot_step_minutes' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
