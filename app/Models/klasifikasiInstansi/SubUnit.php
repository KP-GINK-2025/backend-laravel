<?php

namespace App\Models\klasifikasiInstansi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubUnit extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function upb(): HasMany
    {
        return $this->hasMany(Upb::class);
    }
}
