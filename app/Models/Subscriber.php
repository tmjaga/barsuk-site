<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Str;

class Subscriber extends Model
{
    use Notifiable;

    protected $fillable = ['email', 'token', 'is_verified', 'verified_at'];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            $subscriber->token = Str::random(60);
        });
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
}
