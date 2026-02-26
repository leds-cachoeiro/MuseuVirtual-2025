<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eon extends Model
{
    public function eras()
    {
        return $this->hasMany(Era::class);
    }
}