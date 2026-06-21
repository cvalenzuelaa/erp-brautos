<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 


class VehiculoController extends Controller
{
    // ELIMINA EL CONSTRUCTOR COMPLETO
    // No necesitas $this->middleware('auth') aquí

    public function index()
    {
        // Usamos paginate() en lugar de get()
        $vehiculos = Vehiculo::with(['modelo.marca'])->paginate(10);
        return view('vehiculos.index', compact('vehiculos'));
    }

    public function create()
    {
        $marcas = Marca::with('modelos')->get();
        return view('vehiculos.create', compact('marcas'));
    }

  public function store(Request $request)
    {
        Log::info('Intentando crear vehículo', $request->all());

        $validated = $request->validate([
            'modelo_id'                  => 'required|exists:modelos,id',
            'version'                    => 'nullable|string|max:100',
            'ano'                        => 'required|integer|min:1990|max:' . date('Y'),
            'kilometraje'                => 'required|integer|min:0',
            'transmision'                => 'required|in:Manual,Automática',
            'combustible'                => 'required|in:Gasolina,Diésel,Eléctrico,Híbrido',
            'traccion'                   => 'nullable|in:2WD,4WD,AWD',
            'categoria'                  => 'nullable|string|max:50',
            'condicion'                  => 'nullable|in:Nuevo,Usado',
            'color'                      => 'nullable|string|max:50',
            'precio_venta'               => 'required|numeric|min:0|max:999999999',
            'estado_publicacion'         => 'required|in:Preparación,Publicado,Vendido',
            'equipamiento'               => 'nullable|array',
            'acepta_financiamiento'      => 'nullable|boolean',
            'entidades'                  => 'nullable|array',
            'condiciones_financiamiento' => 'nullable|string',
            'consignado'                 => 'nullable|boolean',
            'venc_revision_tecnica'      => 'nullable|date',
            'venc_permiso_circulacion'   => 'nullable|date',
            'motor'                      => 'nullable|string|max:50',
            'imagenes'                   => 'nullable|array',
            'imagenes.*'                 => 'image|max:5120',
        ]);

        try {
            // Armar equipamiento: combinar extras del checkbox con el motor si aplica
            $equipamiento = $validated['equipamiento'] ?? [];
            if (!empty($validated['motor'])) {
                // Guardar el motor dentro del equipamiento como objeto motor
                // o puedes guardarlo en un campo separado si tienes columna 'motor'
                array_unshift($equipamiento, 'Motor: ' . $validated['motor']);
            }

            // Armar entidades financieras como string
            $entidades = isset($validated['entidades']) 
                ? implode(', ', $validated['entidades']) 
                : null;

            $vehiculo = Vehiculo::create([
                'modelo_id'                  => $validated['modelo_id'],
                'version'                    => $validated['version'] ?? null,
                'ano'                        => $validated['ano'],
                'kilometraje'                => $validated['kilometraje'],
                'transmision'                => $validated['transmision'],
                'combustible'                => $validated['combustible'],
                'traccion'                   => $validated['traccion'] ?? '2WD',
                'categoria'                  => $validated['categoria'] ?? null,
                'condicion'                  => $validated['condicion'] ?? 'Usado',
                'color'                      => $validated['color'] ?? null,
                'precio_venta'               => $validated['precio_venta'],
                'estado_publicacion'         => $validated['estado_publicacion'],
                'equipamiento'               => $equipamiento,
                'acepta_financiamiento'      => $validated['acepta_financiamiento'] ?? 1,
                'entidades_financieras'      => $entidades,
                'condiciones_financiamiento' => $validated['condiciones_financiamiento'] ?? null,
                'consignado'                 => $validated['consignado'] ?? 0,
                'venc_revision_tecnica'      => $validated['venc_revision_tecnica'] ?? null,
                'venc_permiso_circulacion'   => $validated['venc_permiso_circulacion'] ?? null,
                'usuario_id'                 => auth()->id(),
            ]);

            // Guardar imágenes si se subieron
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $index => $imagen) {
                    $path = $imagen->store('vehiculos/' . $vehiculo->id, 'public');
                    $vehiculo->imagenes()->create([
                        'ruta_imagen' => '/storage/' . $path,
                        'es_principal' => $index === 0 ? 1 : 0,
                    ]);
                }
            }

            Log::info('Vehículo creado exitosamente', ['id' => $vehiculo->id]);

            return redirect()->route('vehiculos.index')
                ->with('success', '✅ Vehículo creado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear vehículo: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Error al crear el vehículo: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function update(Request $request, Vehiculo $vehiculo)
    {
        $validated = $request->validate([
            'modelo_id' => 'required|exists:modelos,id',
            'version' => 'nullable|string|max:100',
            'ano' => 'required|integer|min:1990|max:' . date('Y'),
            'kilometraje' => 'required|integer|min:0',
            'transmision' => 'required|in:Manual,Automática',
            'combustible' => 'required|in:Gasolina,Diésel,Eléctrico,Híbrido',
            'traccion' => 'nullable|in:2WD,4WD,AWD',
            'precio_venta' => 'required|numeric|min:0',
            'estado_publicacion' => 'required|in:Preparación,Publicado,Vendido',
        ]);

        $vehiculo->update($validated);

        return redirect()->route('vehiculos.index')
            ->with('success', '✅ Vehículo actualizado exitosamente');
    }

    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->delete();
        return redirect()->route('vehiculos.index')
            ->with('success', '✅ Vehículo eliminado exitosamente');
    }

    public function show(Vehiculo $vehiculo)
    {
        return view('vehiculos.show', compact('vehiculo'));
    }

}