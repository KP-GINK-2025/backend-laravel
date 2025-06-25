<?php

namespace App\Models\klasifikasiAset;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
