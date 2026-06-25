<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marca extends Model
{
    protected $table = 'marcas';
    
    // ¡Esta es la línea clave que debes agregar!
    public $timestamps = false;
    
    protected $fillable = ['nombre'];

    public function modelos(): HasMany
    {
        return $this->hasMany(Modelo::class);
    }
}