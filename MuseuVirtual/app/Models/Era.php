<?php
// app/Models/Era.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Era extends Model
{
    public function eon()
    {
        return $this->belongsTo(Eon::class);
    }

    public function aquisicoes()
    {
        return $this->hasMany(Aquisicoes::class);
    }

    public function periodos()
    {
        return $this->hasMany(Periodo::class);
    }

}

