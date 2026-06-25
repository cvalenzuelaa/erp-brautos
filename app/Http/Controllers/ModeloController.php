<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Models\Marca;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'marca_id' => 'required|exists:marcas,id',
            'nombre'   => 'required|string|max:100',
        ]);

        Modelo::create([
            'marca_id' => $request->marca_id,
            'nombre'   => strtoupper(trim($request->nombre)),
        ]);

        return redirect()->route('marcas.index')
            ->with('success', 'Modelo creado exitosamente');
    }

    public function update(Request $request, Modelo $modelo)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $modelo->update(['nombre' => strtoupper(trim($request->nombre))]);

        return redirect()->route('marcas.index')
            ->with('success', 'Modelo actualizado exitosamente');
    }

    public function destroy(Modelo $modelo)
    {
        if ($modelo->vehiculos()->count() > 0) {
            return redirect()->route('marcas.index')
                ->with('error', 'No se puede eliminar el modelo porque tiene vehículos asociados');
        }

        $modelo->delete();
        return redirect()->route('marcas.index')
            ->with('success', 'Modelo eliminado exitosamente');
    }
}