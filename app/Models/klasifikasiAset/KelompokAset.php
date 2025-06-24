<?php

namespace App\Models\klasifikasiAset;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KelompokAset extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function akun_aset(): BelongsTo
    {
        return $this->belongsTo(AkunAset::class);
    }
}
