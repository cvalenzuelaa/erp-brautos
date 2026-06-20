<?php

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Vehiculo;
// Obtener todos los vehículos publicados
Route::get('/vehiculos/publicos', function () {
    $vehiculos = Vehiculo::with(['modelo.marca', 'imagenes'])
        ->where('estado_publicacion', 'Publicado')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($vehiculo) {
            return [
                'id' => $vehiculo->id,
                'marca' => $vehiculo->modelo->marca->nombre ?? '',
                'modelo' => $vehiculo->modelo->nombre ?? '',
                'version' => $vehiculo->version,
                'ano' => $vehiculo->ano,
                'kilometraje' => $vehiculo->kilometraje,
                'transmision' => $vehiculo->transmision,
                'combustible' => $vehiculo->combustible,
                'precio' => $vehiculo->precio_venta,
                'imagen_principal' => $vehiculo->imagenes->where('es_principal', true)->first()?->ruta_imagen,
                'imagenes' => $vehiculo->imagenes->pluck('ruta_imagen'),
            ];
        });
    
    return response()->json($vehiculos);
});

// Obtener un vehículo específico
Route::get('/vehiculos/publicos/{id}', function ($id) {
    $vehiculo = Vehiculo::with(['modelo.marca', 'imagenes'])
        ->where('estado_publicacion', 'Publicado')
        ->findOrFail($id);
    
    return response()->json([
        'id' => $vehiculo->id,
        'marca' => $vehiculo->modelo->marca->nombre ?? '',
        'modelo' => $vehiculo->modelo->nombre ?? '',
        'version' => $vehiculo->version,
        'ano' => $vehiculo->ano,
        'kilometraje' => $vehiculo->kilometraje,
        'transmision' => $vehiculo->transmision,
        'combustible' => $vehiculo->combustible,
        'traccion' => $vehiculo->traccion,
        'precio' => $vehiculo->precio_venta,
        'equipamiento' => $vehiculo->equipamiento,
        'imagen_principal' => $vehiculo->imagenes->where('es_principal', true)->first()?->ruta_imagen,
        'imagenes' => $vehiculo->imagenes->pluck('ruta_imagen'),
    ]);
});

Route::get('/modelos', function (Request $request) {
    $marcaId = $request->get('marca_id');
    if ($marcaId) {
        return Modelo::where('marca_id', $marcaId)->get();
    }
    return Modelo::all();
});