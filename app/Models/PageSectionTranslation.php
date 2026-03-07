<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSectionTranslation extends Model
{
    protected $fillable = ['section_id', 'locale', 'value'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(PageSection::class);
    }
}
