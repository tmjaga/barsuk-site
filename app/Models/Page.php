<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'template', 'custom_template'];

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('created_at');
    }
}
