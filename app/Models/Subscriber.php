<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Str;

class Subscriber extends Model
{
    use Notifiable;

    protected $fillable = ['email', 'token', 'is_verified', 'verified_at'];

    protected $appends = ['formatted_creation_date'];

    protected $casts = [
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

    public function getFormattedCreationDateAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d.m.Y H:i') : '';
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }
}
