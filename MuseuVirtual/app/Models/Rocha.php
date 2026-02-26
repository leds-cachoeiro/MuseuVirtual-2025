<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Rocha extends Model
{
    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'composicao',
        'tipo',
        'jazida_id', // Adicionado para permitir preenchimento via create/update
        'ornamental',
    ];

    protected $casts = [
        'ornamental' => 'boolean',
    ];

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function minerais()
    {
        return $this->belongsToMany(Mineral::class, 'rocha_minerals');
    }

    public function fotos()
    {
        return $this->hasMany(Fotos::class, 'idRocha');
    }

    public function jazida()
    {
        return $this->belongsTo(Jazida::class);
    }

    // Gera slug automaticamente ao criar
    protected static function booted()
    {
        static::creating(function ($rocha) {
            if (empty($rocha->slug)) {
                $rocha->slug = Str::slug($rocha->nome);
            }
        });
    }


    // Traduz os tipos para nomes amigáveis
    public function getTipoNomeAttribute()
    {
        return match($this->tipo) {
            '1' => 'igneas',
            '2' => 'sedimentares',
            '3' => 'metamorficas',
        };
    }
}
