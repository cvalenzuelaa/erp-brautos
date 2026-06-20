@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🚗 Vehículos</h1>
        <a href="{{ route('vehiculos.create') }}" 
           class="bg-[#eb5e10] text-white px-4 py-2 rounded-lg hover:bg-[#d4540e] transition">
            + Nuevo Vehículo
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehículo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Año</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($vehiculos as $vehiculo)
                <tr>
                    <td class="px-6 py-4">#{{ $vehiculo->id }}</td>
                    <td class="px-6 py-4 font-medium">
                        {{ $vehiculo->modelo->marca->nombre ?? 'Sin marca' }}
                        {{ $vehiculo->modelo->nombre ?? '' }}
                        {{ $vehiculo->version ?? '' }}
                    </td>
                    <td class="px-6 py-4">{{ $vehiculo->ano }}</td>
                    <td class="px-6 py-4 font-bold text-[#eb5e10]">
                        ${{ number_format($vehiculo->precio_venta, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($vehiculo->estado_publicacion == 'Publicado') bg-green-100 text-green-700
                            @elseif($vehiculo->estado_publicacion == 'Vendido') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $vehiculo->estado_publicacion }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('vehiculos.edit', $vehiculo) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('vehiculos.destroy', $vehiculo) }}" 
                              method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('¿Eliminar este vehículo?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No hay vehículos registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@can('publicar_redes')
    <form action="{{ route('vehiculos.publicar', $vehiculo) }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="text-green-600 hover:text-green-800">
            <i class="fab fa-facebook"></i>
            <i class="fab fa-instagram"></i>
        </button>
    </form>
@endcan
@endsection