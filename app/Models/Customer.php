<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'hospital_id',
        'phss_id',
        'contact_person',
        'position',
        'contact_no',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }
    public function phss(): BelongsTo
    {
        return $this->belongsTo(Phss::class);
    }
}
