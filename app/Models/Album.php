<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Album extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['title', 'active'];

    protected $appends = ['status_badge'];

    protected $casts = [
        'active' => Status::class,
    ];

    public function getStatusBadgeAttribute(): string
    {
        if ($this->active == Status::ACTIVE) {
            return '<span class="inline-flex items-center justify-center gap-1 rounded-full bg-success-500 px-2.5 py-0.5 text-sm font-medium text-white">Active</span>';
        }

        if ($this->active == Status::INACTIVE) {
            return '<span class="inline-flex items-center justify-center gap-1 rounded-full bg-error-500 px-2.5 py-0.5 text-sm font-medium text-white">Inactive</span>';
        }

        return '';
    }
}
