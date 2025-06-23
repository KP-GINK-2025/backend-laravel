<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Upb extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function sub_unit(): BelongsTo
    {
        return $this->belongsTo(SubUnit::class);
    }
}
