<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehiculoController extends Controller
{
    /**
     * Convierte una imagen subida a WebP y la guarda en la ruta correcta.
     * Retorna la ruta relativa para guardar en BD.
     */
    private function procesarImagen($imagen, $carpeta, $nombreOriginal): array
{
    $esProduccion = app()->environment('production');

    $nombreBase = pathinfo($nombreOriginal, PATHINFO_FILENAME);
    $nombreWebp = $nombreBase . '.webp';

    if ($esProduccion) {
        $rutaBase = base_path('../../../../images/autos/');
        $urlBase  = 'https://brautos.cl/images/autos/';
    } else {
        $rutaBase = storage_path('app/public/' . $carpeta . '/');
        $urlBase  = '/storage/' . $carpeta . '/';
    }

    if (!file_exists($rutaBase)) {
        mkdir($rutaBase, 0755, true);
    }

    // Conversión a WebP con PHP GD nativo (sin librerías externas)
    $rutaOriginal = $imagen->getRealPath();
    $mime = mime_content_type($rutaOriginal);

    $imgResource = match($mime) {
        'image/jpeg' => imagecreatefromjpeg($rutaOriginal),
        'image/png'  => imagecreatefrompng($rutaOriginal),
        'image/webp' => imagecreatefromwebp($rutaOriginal),
        'image/gif'  => imagecreatefromgif($rutaOriginal),
        default      => imagecreatefromjpeg($rutaOriginal),
    };

    imagewebp($imgResource, $rutaBase . $nombreWebp, 85);
    imagedestroy($imgResource);

    return ['ruta' => $urlBase . $nombreWebp];
}

    public function index(Request $request)
    {
        $query = Vehiculo::with(['modelo.marca', 'imagenes']);

        // 1. Aplicar Búsqueda por texto (Agrupada para no romper otros filtros)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('modelo', function($q2) use ($search) {
                    $q2->where('nombre', 'like', "%{$search}%")
                      ->orWhereHas('marca', function($q3) use ($search) {
                          $q3->where('nombre', 'like', "%{$search}%");
                      });
                })
                ->orWhere('ano', 'like', "%{$search}%")
                ->orWhere('version', 'like', "%{$search}%");
            });
        }

        // 2. Aplicar Filtro por Estado
        if ($request->filled('estado')) {
            $query->where('estado_publicacion', $request->estado);
        }

        $vehiculos = $query->paginate(10)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return view('vehiculos.index', compact('vehiculos')); 
        }

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
            'ano'                        => 'required|integer|min:1990|max:' . (date('Y') + 1),
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
            $equipamiento = $validated['equipamiento'] ?? [];
            if (!empty($validated['motor'])) {
                array_unshift($equipamiento, 'Motor: ' . $validated['motor']);
            }

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

            // Guardar imágenes convertidas a WebP
            if ($request->hasFile('imagenes')) {
                $archivos = $request->file('imagenes');
                $carpeta  = 'vehiculos/' . $vehiculo->id;

                // Detectar cuál imagen tiene "002" en el nombre → será portada
                $indicePrincipal = 0;
                foreach ($archivos as $i => $imagen) {
                    if (strpos($imagen->getClientOriginalName(), '002') !== false) {
                        $indicePrincipal = $i;
                        break;
                    }
                }

                foreach ($archivos as $index => $imagen) {
                    $resultado = $this->procesarImagen(
                        $imagen,
                        $carpeta,
                        $imagen->getClientOriginalName()
                    );

                    $vehiculo->imagenes()->create([
                        'ruta_imagen'  => $resultado['ruta'],
                        'es_principal' => $index === $indicePrincipal ? 1 : 0,
                    ]);
                }
            }

            Log::info('Vehículo creado exitosamente', ['id' => $vehiculo->id]);

            return redirect()->route('vehiculos.index')
                ->with('success', 'Vehículo creado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear vehículo: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al crear el vehículo: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Vehiculo $vehiculo)
    {
        $marcas = Marca::with('modelos')->get();

        $equipamientoDecoded = is_string($vehiculo->equipamiento)
            ? json_decode($vehiculo->equipamiento, true)
            : $vehiculo->equipamiento;

        $equipamientoArray = [];
        $motor = '';

        if (is_array($equipamientoDecoded)) {
            foreach ($equipamientoDecoded as $item) {
                if (strpos($item, 'Motor:') !== false) {
                    $motor = trim(str_replace('Motor:', '', $item));
                } else {
                    $equipamientoArray[] = $item;
                }
            }
        }

        return view('vehiculos.edit', compact('vehiculo', 'marcas', 'equipamientoArray', 'motor'));
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        $validated = $request->validate([
            'modelo_id'                  => 'required|exists:modelos,id',
            'version'                    => 'nullable|string|max:100',
            'ano'                        => 'required|integer|min:1990|max:' . (date('Y') + 1),
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

        $equipamiento = $validated['equipamiento'] ?? [];
        if (!empty($validated['motor'])) {
            array_unshift($equipamiento, 'Motor: ' . $validated['motor']);
        }

        $entidades = isset($validated['entidades']) ? implode(', ', $validated['entidades']) : null;

        $vehiculo->update(array_merge($validated, [
            'equipamiento'          => $equipamiento,
            'entidades_financieras' => $entidades,
            'acepta_financiamiento' => $request->has('acepta_financiamiento') ? 1 : 0,
            'consignado'            => $request->has('consignado') ? 1 : 0,
        ]));

        // Guardar nuevas imágenes convertidas a WebP
        if ($request->hasFile('imagenes')) {
            $archivos = $request->file('imagenes');
            $carpeta  = 'vehiculos/' . $vehiculo->id;
            $tienePrincipal = $vehiculo->imagenes()->where('es_principal', 1)->exists();

            $indicePrincipal = 0;
            foreach ($archivos as $i => $imagen) {
                if (strpos($imagen->getClientOriginalName(), '002') !== false) {
                    $indicePrincipal = $i;
                    break;
                }
            }

            foreach ($archivos as $index => $imagen) {
                $resultado = $this->procesarImagen(
                    $imagen,
                    $carpeta,
                    $imagen->getClientOriginalName()
                );

                $vehiculo->imagenes()->create([
                    'ruta_imagen'  => $resultado['ruta'],
                    'es_principal' => (!$tienePrincipal && $index === $indicePrincipal) ? 1 : 0,
                ]);
            }
        }

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado exitosamente');
    }

    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->delete();
        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado exitosamente');
    }

    public function show(Vehiculo $vehiculo)
    {
        return view('vehiculos.show', compact('vehiculo'));
    }
}