<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aquisicoes extends Model
{
    protected $fillable = ['era_id', 'periodo_id', 'rocha_id', 'mineral_id'];

    public function era()
    {
        return $this->belongsTo(Era::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function rocha()
    {
        return $this->belongsTo(Rocha::class);
    }

    public function mineral()
    {
        return $this->belongsTo(Mineral::class);
    }
}

