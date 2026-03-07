<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Review extends Model
{
    use HasFactory;

    protected $appends = ['short_comment', 'formatted_creation_date'];

    protected $fillable = ['name', 'email', 'comment', 'rating', 'status'];

    public function getShortCommentAttribute(): string
    {
        return Str::limit($this->comment, 50, ' ...');
    }

    public function getFormattedCreationDateAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d.m.Y H:i') : '';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', Status::ACTIVE->value);
    }
}
