<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnotacaoFoto extends Model
{
    protected $table = 'anotacoes_foto';

    protected $fillable = [
        'foto_id',
        'x',
        'y',
        'texto',
    ];

    public function foto(){
        return $this->belongsTo(Foto::class);
    }
    
}
