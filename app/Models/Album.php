<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    protected $casts = [
        'active' => Status::class,
    ];

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->active == Status::ACTIVE) {
                $html = '<span class="inline-flex items-center justify-center gap-1 rounded-full bg-success-500 px-2.5 py-0.5 text-sm font-medium text-white">Active</span>';
            } elseif ($this->active == Status::INACTIVE) {
                $html = '<span class="inline-flex items-center justify-center gap-1 rounded-full bg-error-500 px-2.5 py-0.5 text-sm font-medium text-white">InActive</span>';
            }

            return $html;
        });
    }
}
