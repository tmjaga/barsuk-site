<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }
}
