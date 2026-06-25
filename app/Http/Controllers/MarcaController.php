<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::with('modelos')->withCount('modelos')->orderBy('nombre')->get();
        return view('marcas.index', compact('marcas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:marcas,nombre',
        ]);

        Marca::create(['nombre' => strtoupper(trim($request->nombre))]);

        return redirect()->route('marcas.index')
            ->with('success', 'Marca creada exitosamente');
    }

    public function update(Request $request, Marca $marca)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:marcas,nombre,' . $marca->id,
        ]);

        $marca->update(['nombre' => strtoupper(trim($request->nombre))]);

        return redirect()->route('marcas.index')
            ->with('success', 'Marca actualizada exitosamente');
    }

    public function destroy(Marca $marca)
    {
        if ($marca->modelos()->count() > 0) {
            return redirect()->route('marcas.index')
                ->with('error', 'No se puede eliminar la marca porque tiene modelos asociados');
        }

        $marca->delete();
        return redirect()->route('marcas.index')
            ->with('success', 'Marca eliminada exitosamente');
    }

    // Modelos de una marca (para el panel dentro de marcas)
    public function modelos(Marca $marca)
    {
        return response()->json($marca->modelos()->orderBy('nombre')->get());
    }
}