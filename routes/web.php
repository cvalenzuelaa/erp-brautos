<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// ✅ Solo UNA definición del resource, con los middlewares correctos
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::resource('vehiculos', VehiculoController::class);
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