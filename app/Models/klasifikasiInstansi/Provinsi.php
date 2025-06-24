<?php

namespace App\Models\klasifikasiInstansi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provinsi extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function kabupaten_kota(): HasMany
    {
        return $this->hasMany(KabupatenKota::class);
    }
}
