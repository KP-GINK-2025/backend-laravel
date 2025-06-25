<?php

namespace App\Models\klasifikasiAset;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjekAset extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];

    public function jenis_aset(): BelongsTo
    {
        return $this->belongsTo(JenisAset::class);
    }
}
