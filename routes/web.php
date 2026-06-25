<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use Illuminate\Support\Facades\Route;
use App\Models\Vehiculo;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalVehiculos = Vehiculo::count();
    $publicados = Vehiculo::where('estado_publicacion', 'Publicado')->count();
    $sumaTotal = Vehiculo::sum('precio_venta');
    
    $valorInventario = $sumaTotal >= 1000000 
        ? '$' . number_format($sumaTotal / 1000000, 1, ',', '.') . 'M' 
        : '$' . number_format($sumaTotal, 0, ',', '.');

    return view('dashboard', compact('totalVehiculos', 'publicados', 'valorInventario'));
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::resource('vehiculos', VehiculoController::class);
    Route::resource('marcas', MarcaController::class);
    Route::resource('modelos', ModeloController::class);
    // API para cargar modelos por marca (usada en create/edit de vehículos)
    Route::get('/api/modelos', function () {
        $marcaId = request('marca_id');
        return \App\Models\Modelo::where('marca_id', $marcaId)->orderBy('nombre')->get();
    });
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/usuarios', function () {
        return view('usuarios.index');
    })->name('usuarios.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/vitrina', function () {
    return view('vitrina');
})->name('vitrina');

Route::post('/vehiculos/{vehiculo}/publicar', [VehiculoController::class, 'publicar'])
    ->name('vehiculos.publicar')
    ->middleware(['auth', 'can:publicar_redes']);

require __DIR__.'/auth.php';