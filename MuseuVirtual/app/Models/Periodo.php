<?php
// app/Models/Periodo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    public function era()
    {
        return $this->belongsTo(Era::class);
        
    }

    public function aquisicoes()
    {
        return $this->hasMany(Aquisicoes::class);
    }

}

