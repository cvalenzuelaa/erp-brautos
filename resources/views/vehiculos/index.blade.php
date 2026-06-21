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

    {{-- Mensaje de Éxito (Si vienes de crear/editar un vehículo) --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 font-medium text-sm shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Contenedor de la Tabla --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Barra de Búsqueda/Filtros Superior --}}
        <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <div class="relative w-full max-w-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" placeholder="Buscar vehículo..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
            </div>
            
            <button class="px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition flex items-center gap-2">
                <i class="fa-solid fa-filter text-gray-400"></i> Filtros
            </button>
        </div>

        {{-- Tabla de Datos --}}
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
                            
                            {{-- Columna: Vehículo (Foto + Marca/Modelo) --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-12 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-200 flex items-center justify-center">
                                        {{-- Intentamos mostrar la foto principal, si no, un ícono por defecto --}}
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

                            {{-- Columna: Especificaciones Técnicas --}}
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

                            {{-- Columna: Precio --}}
                            <td class="px-6 py-4">
                                <span class="font-black text-[#eb5e10] text-base bg-orange-50 px-3 py-1.5 rounded-lg border border-orange-100">
                                    ${{ number_format($vehiculo->precio_venta, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- Columna: Estado Operativo (Badges Corporativos) --}}
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

                            {{-- Columna: Botones de Acción --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="w-9 h-9 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-[#1a2b4c] hover:border-[#1a2b4c] hover:bg-gray-50 flex items-center justify-center transition shadow-sm" title="Editar Vehículo">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    
                                    <form action="{{ route('vehiculos.destroy', $vehiculo->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('⚠️ ¿Estás completamente seguro de eliminar este vehículo del sistema? Esta acción no se puede deshacer.');">
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
                        {{-- Estado Vacío --}}
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 border border-gray-100">
                                        <i class="fa-solid fa-car-side text-2xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-black text-[#1a2b4c] mb-1">Aún no hay vehículos registrados</h3>
                                    <p class="text-sm font-medium text-gray-500 mb-5">El inventario está vacío. Comienza agregando tu primer automóvil al sistema.</p>
                                    <a href="{{ route('vehiculos.create') }}" class="px-5 py-2 bg-[#1a2b4c] hover:bg-[#233a66] text-white rounded-xl text-sm font-bold transition shadow-sm">
                                        Añadir Vehículo
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Paginación (Si tienes paginate() en tu controlador) --}}
        @if(method_exists($vehiculos, 'links') && $vehiculos->hasPages())
            <div class="p-4 border-t border-gray-100 bg-white">
                {{ $vehiculos->links() }}
            </div>
        @endif
        
    </div>
</div>
@endsection