<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    use SoftDeletes;
    protected $table = 'vehiculos';
    
    protected $fillable = [
        'modelo_id', 'version', 'ano', 'kilometraje', 'transmision',
        'combustible', 'traccion', 'categoria', 'condicion', 'color',
        'equipamiento', 'precio_venta', 'acepta_financiamiento',
        'entidades_financieras', 'condiciones_financiamiento',
        'estado_publicacion', 'consignado', 'usuario_id',
        'venc_revision_tecnica', 'venc_permiso_circulacion',
    ];

    protected $casts = [
        'equipamiento' => 'array',
    ];

    public function modelo(): BelongsTo
    {
        return $this->belongsTo(Modelo::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenVehiculo::class);
    }
}