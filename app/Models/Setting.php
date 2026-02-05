<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $casts = [
        'work_from' => 'string',
        'work_to' => 'string',
    ];

    protected $fillable = ['work_from', 'work_to', 'break_minutes', 'slot_step_minutes'];

    protected static function booted(): void
    {
        static::saved(function ($setting) {
            Cache::forget('settings');
        });

        static::deleted(function ($setting) {
            Cache::forget('settings');
        });
    }
}
