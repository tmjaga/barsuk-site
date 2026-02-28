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

    protected $fillable = [
        'work_from',
        'work_to',
        'break_minutes',
        'slot_step_minutes',
        'phone',
        'email',
        'address',
        'fb_link',
        'inst_link',
    ];

    protected static function booted(): void
    {
        static::saved(function ($setting) {
            Cache::forget('settings');
        });

        static::deleted(function ($setting) {
            Cache::forget('settings');
        });
    }

    public function getFormattedPhoneAttribute()
    {
        $phone = $this->phone;

        if (preg_match('/^\+(\d{3})(\d{3})(\d+)$/', $phone, $matches)) {
            return "(+{$matches[1]}) {$matches[2]} {$matches[3]}";
        }

        return $phone;
    }
}
