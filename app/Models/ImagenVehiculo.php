<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagenVehiculo extends Model
{
    protected $table = 'imagenes_vehiculos';
    public $timestamps = false;
    
    protected $fillable = ['vehiculo_id', 'ruta_imagen', 'es_principal'];

    protected $casts = [
        'es_principal' => 'boolean'
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }
}