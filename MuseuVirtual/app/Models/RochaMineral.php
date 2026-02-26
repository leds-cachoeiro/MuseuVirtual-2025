<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RochaMineral extends Model
{
    protected $fillable = ['rocha_id', 'mineral_id'];

    public function rocha()
    {
        return $this->belongsTo(Rocha::class);
    }

    public function mineral()
    {
        return $this->belongsTo(Mineral::class);
    }
}
