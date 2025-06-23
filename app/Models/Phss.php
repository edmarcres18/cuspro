<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phss extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'full_name',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
