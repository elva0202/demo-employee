<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class City extends Model
{
    use HasFactory;
    protected $fillabe = [
        'state_id',
        'name'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
