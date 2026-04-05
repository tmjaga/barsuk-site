<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'template', 'custom_template'];

    protected static function booted()
    {
        static::updating(function ($page) {
            $wasValid = $page->getOriginal('custom_template') && $page->getOriginal('template');
            $isValid = $page->useCustomTemplate();

            if ($wasValid && ! $isValid) {
                $page->services()->update([
                    'page_id' => null,
                ]);
            }
        });
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('created_at');
    }

    public function useCustomTemplate(): bool
    {
        return $this->custom_template && $this->template;
    }

    public function scopeCustomTemplate(Builder $query): Builder
    {
        return $query->whereNotNull('template')
            ->where('custom_template', true);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
