<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Jazida extends Model
{
    use HasFactory;

    protected $fillable = [
        'localizacao', 
        'descricao', 
        'slug',
    ];

    public function fotos()
    {
        return $this->hasMany(Fotos::class, 'idJazida');
    }

    public function rochas()
    {
        return $this->hasMany(Rocha::class);
    }
    
    public function minerais()
    {
        return $this->hasMany(Mineral::class);
    }
     // Gera slug automaticamente ao criar
     protected static function booted()
     {
         static::creating(function ($jazida) {
             if (empty($jazida->slug)) {
                 $jazida->slug = Str::slug($jazida->nome);
             }
         });
     }
}
