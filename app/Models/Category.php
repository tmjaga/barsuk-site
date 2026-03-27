<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title'];

    protected $fillable = ['title', 'active'];

    protected $appends = ['title_localized'];

    protected $casts = [
        'active' => Status::class,
    ];

    public function getTitleLocalizedAttribute()
    {
        return $this->getTranslation('title', app()->getLocale());
    }

}
