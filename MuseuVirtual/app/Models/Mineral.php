<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Mineral extends Model
{
    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'propriedades',
        'idJazida',
        'era_id',
        'periodo_id',
    ];
    public function fotos()
    {
        return $this->hasMany(Fotos::class, 'idMineral');
    }
    public function jazida()
    {
        return $this->belongsTo(Jazida::class, 'idJazida');
    }

    public function rochas()
    {
        return $this->belongsToMany(Rocha::class, 'rocha_minerals');
    }

    // Gera slug automaticamente ao criar
    protected static function booted()
    {
        static::creating(function ($mineral) {
            if (empty($mineral->slug)) {
                $mineral->slug = Str::slug($mineral->nome);
            }
        });
    }
}
