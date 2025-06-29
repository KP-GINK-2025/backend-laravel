<?php

namespace App\Models\klasifikasiAset;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class JenisAset extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function kelompok_aset(): BelongsTo
    {
        return $this->belongsTo(KelompokAset::class);
    }


    public function objek_aset(): HasMany
    {
        return $this->hasMany(ObjekAset::class);
    }

}
