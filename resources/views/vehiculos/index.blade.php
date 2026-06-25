@extends('layouts.app')

@section('content')
<div class="p-6 md:p-8 space-y-6">

    {{-- Encabezado y Acción Principal --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-[#1a2b4c]">Inventario de Vehículos</h1>
            <p class="text-sm font-medium text-gray-500 mt-1">
                Gestiona los automóviles, sus especificaciones y estados de publicación.
            </p>
        </div>
        
        <a href="{{ route('vehiculos.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#eb5e10] hover:bg-[#d4540e] text-white rounded-xl text-sm font-bold transition shadow-md shadow-orange-500/20 shrink-0">
            <i class="fa-solid fa-plus"></i> Añadir Nuevo Vehículo
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 font-medium text-sm shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Barra de Búsqueda y Filtros Marcables --}}
        <div class="p-4 border-b border-gray-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4 bg-gray-50/50">
            
            <div class="relative w-full xl:max-w-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="searchInput" placeholder="Buscar por marca, modelo o año..." 
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
            </div>
            
            <div class="flex items-center gap-2 overflow-x-auto custom-scrollbar pb-1 xl:pb-0" id="filtros-estado">
                <button type="button" data-estado="" class="filtro-btn whitespace-nowrap px-4 py-2 rounded-xl text-sm font-bold transition bg-[#1a2b4c] text-white shadow-sm border border-[#1a2b4c]">
                    Todos
                </button>
                <button type="button" data-estado="Publicado" class="filtro-btn whitespace-nowrap px-4 py-2 rounded-xl text-sm font-bold transition bg-white text-gray-600 border border-gray-200 hover:bg-gray-50">
                    Publicados
                </button>
                <button type="button" data-estado="Vendido" class="filtro-btn whitespace-nowrap px-4 py-2 rounded-xl text-sm font-bold transition bg-white text-gray-600 border border-gray-200 hover:bg-gray-50">
                    Vendidos
                </button>
                <button type="button" data-estado="Preparación" class="filtro-btn whitespace-nowrap px-4 py-2 rounded-xl text-sm font-bold transition bg-white text-gray-600 border border-gray-200 hover:bg-gray-50">
                    En Preparación
                </button>
            </div>
        </div>

        {{-- INICIO CONTENEDOR DINÁMICO (Aquí se inyectan los resultados) --}}
        <div id="tabla-vehiculos-container">
            
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-500 text-[10px] uppercase tracking-widest font-bold border-b border-gray-200">
                            <th class="px-6 py-4 rounded-tl-xl">Vehículo</th>
                            <th class="px-6 py-4">Especificaciones</th>
                            <th class="px-6 py-4">Precio Venta</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-center rounded-tr-xl">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($vehiculos as $vehiculo)
                            <tr class="hover:bg-gray-50/80 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-12 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-200 flex items-center justify-center">
                                            @php
                                                $portada = $vehiculo->imagenes ? $vehiculo->imagenes->where('es_principal', 1)->first() : null;
                                            @endphp
                                            
                                            @if($portada)
                                                <img src="{{ $portada->ruta_imagen }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fa-solid fa-car text-gray-300 text-xl"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-black text-[#1a2b4c] uppercase leading-tight text-base">
                                                {{ $vehiculo->modelo->marca->nombre ?? 'Marca' }} {{ $vehiculo->modelo->nombre ?? 'Modelo' }}
                                            </p>
                                            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $vehiculo->version }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1 text-xs text-gray-600 font-bold">
                                        <span class="flex items-center gap-2">
                                            <i class="fa-regular fa-calendar text-gray-400 w-3"></i> {{ $vehiculo->ano }}
                                        </span>
                                        <span class="flex items-center gap-2">
                                            <i class="fa-solid fa-gauge-high text-gray-400 w-3"></i> {{ number_format($vehiculo->kilometraje, 0, ',', '.') }} km
                                        </span>
                                        <span class="flex items-center gap-2">
                                            <i class="fa-solid fa-gas-pump text-gray-400 w-3"></i> {{ $vehiculo->combustible }} • {{ $vehiculo->transmision }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="font-black text-[#eb5e10] text-base bg-orange-50 px-3 py-1.5 rounded-lg border border-orange-100">
                                        ${{ number_format($vehiculo->precio_venta, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if($vehiculo->estado_publicacion == 'Publicado')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_4px_#10b981]"></span> Publicado
                                        </span>
                                    @elseif($vehiculo->estado_publicacion == 'Vendido')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-[11px] font-bold bg-gray-100 text-gray-600 border border-gray-200 uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Vendido
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse shadow-[0_0_4px_#3b82f6]"></span> En Preparación
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="w-9 h-9 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-[#1a2b4c] hover:border-[#1a2b4c] hover:bg-gray-50 flex items-center justify-center transition shadow-sm" title="Editar Vehículo">
                                            <i class="fa-solid fa-pen text-sm"></i>
                                        </a>
                                        <form action="{{ route('vehiculos.destroy', $vehiculo->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('¿Eliminar este vehículo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-red-500 hover:border-red-500 hover:bg-red-50 flex items-center justify-center transition shadow-sm" title="Eliminar Vehículo">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 border border-gray-100">
                                            <i class="fa-solid fa-car-side text-2xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-lg font-black text-[#1a2b4c] mb-1">No hay resultados</h3>
                                        <p class="text-sm font-medium text-gray-500 mb-5">No se encontraron vehículos que coincidan con tu búsqueda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($vehiculos, 'links') && $vehiculos->hasPages())
                <div class="p-4 border-t border-gray-100 bg-white">
                    {{ $vehiculos->links() }}
                </div>
            @endif
            
        </div>
        {{-- FIN CONTENEDOR DINÁMICO --}}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const filterBtns = document.querySelectorAll('.filtro-btn');
    const tableContainer = document.getElementById('tabla-vehiculos-container');
    
    let currentEstado = ''; 

    function fetchVehiculos(url = null) {
        const search = searchInput.value;
        const fetchUrl = new URL(url || window.location.origin + window.location.pathname);
        
        if (search) fetchUrl.searchParams.set('search', search);
        if (currentEstado) fetchUrl.searchParams.set('estado', currentEstado);

        fetch(fetchUrl, {
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en el servidor (' + response.status + ')');
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('tabla-vehiculos-container');
            
            if (newContent) {
                tableContainer.innerHTML = newContent.innerHTML;
            } else {
                console.error("No se encontró la tabla en la respuesta. Puede haber un error PHP oculto.");
            }
        })
        .catch(error => {
            console.error('Fallo la petición AJAX:', error);
        });
    }

    let typingTimer;
    searchInput.addEventListener('input', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => fetchVehiculos(), 300);
    });

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => {
                b.classList.remove('bg-[#1a2b4c]', 'text-white', 'shadow-sm', 'border-[#1a2b4c]');
                b.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
            });
            
            this.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
            this.classList.add('bg-[#1a2b4c]', 'text-white', 'shadow-sm', 'border-[#1a2b4c]');
            
            currentEstado = this.dataset.estado;
            fetchVehiculos();
        });
    });

    document.addEventListener('click', function (e) {
        const paginationLink = e.target.closest('nav[role="navigation"] a');
        if (paginationLink && tableContainer.contains(paginationLink)) {
            e.preventDefault();
            fetchVehiculos(paginationLink.href);
        }
    });
});
</script>
@endsection