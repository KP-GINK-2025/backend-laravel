<?php

namespace App\Models\klasifikasiInstansi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KabupatenKota extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function bidang(): HasMany
    {
        return $this->hasMany(Bidang::class);
    }
}
