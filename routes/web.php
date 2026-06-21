<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehiculoController;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\Route;

// Ruta principal (puedes cambiarla para que redirija al login o dashboard)
Route::get('/', function () {
    return view('welcome');
});

// Dashboard con cálculos reales
Route::get('/dashboard', function () {
    $total_vehiculos = Vehiculo::count();
    $publicados = Vehiculo::where('estado_publicacion', 'Publicado')->count();
    $valor_bruto = Vehiculo::sum('precio_venta');
    
    if ($valor_bruto >= 1000000) {
        $valor_inventario = '$' . number_format($valor_bruto / 1000000, 1, ',', '.') . 'M';
    } else {
        $valor_inventario = '$' . number_format($valor_bruto, 0, ',', '.');
    }

    return view('dashboard', compact('total_vehiculos', 'publicados', 'valor_inventario'));
})->middleware(['auth', 'verified'])->name('dashboard');


// Grupo de rutas que requieren que el usuario haya iniciado sesión
Route::middleware('auth')->group(function () {
    
    // Rutas del Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas del Inventario de Vehículos
    Route::resource('vehiculos', VehiculoController::class);
    
});

require __DIR__.'/auth.php';