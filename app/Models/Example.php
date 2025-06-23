<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    // public function instansi()
    // {
    //     return $this->belongsTo(Instansi::class, 'instansi_id');
    // }

    // public function files()
    // {
    //     return $this->hasMany(BankData::class, 'categories_id');
    // }
}