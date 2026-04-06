<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title'];

    protected $fillable = ['title', 'active', 'slug', 'page_id'];

    protected $appends = ['title_localized'];

    protected $casts = [
        'active' => Status::class,
    ];

    public function getTitleLocalizedAttribute()
    {
        return $this->getTranslation('title', app()->getLocale());
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function activeServices(): HasMany
    {
        return $this->hasMany(Service::class)->where('active', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', Status::ACTIVE->value);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
