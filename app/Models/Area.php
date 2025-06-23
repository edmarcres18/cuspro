<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function phss(): HasMany
    {
        return $this->hasMany(Phss::class);
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
