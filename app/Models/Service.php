<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;
use Str;

class Service extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title', 'description'];

    protected $fillable = ['title', 'slug', 'category_id', 'description', 'duration', 'active', 'price'];

    protected $appends = ['title_localized'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (self $service) {
            $locale = config('logat.default');
            $title = $service->getTranslation('title', $locale);

            if (! $title) {
                return;
            }

            $service->slug = Str::slug($title).'-'.mt_rand(1000, 9999);
        });
    }

    protected function duration(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $hours = intdiv($value, 60);
                $minutes = $value % 60;

                return sprintf('%02d:%02d', $hours, $minutes);
            },

            set: function ($value) {
                if (! preg_match('/^\d{1,2}:\d{2}$/', $value)) {
                    return 10;
                }

                [$hours, $minutes] = explode(':', $value);
                $total = ((int) $hours * 60) + (int) $minutes;

                return max(10, $total);
            }
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function getTitleLocalizedAttribute()
    {
        return $this->getTranslation('title', app()->getLocale());
    }
}
