@extends('layouts.app')

@section('content')
<div class="p-6 md:p-8 space-y-6">

    {{-- Encabezado de Bienvenida --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-[#1a2b4c]">Resumen Comercial</h1>
            <p class="text-sm font-medium text-gray-500 mt-1">
                Bienvenido de vuelta, {{ Auth::user()->name }}. Aquí tienes el estado actual del negocio.
            </p>
        </div>
        
        {{-- Botón de acceso rápido según tu web.php --}}
        <a href="{{ route('vehiculos.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#eb5e10] hover:bg-[#d4540e] text-white rounded-xl text-sm font-bold transition shadow-md shadow-orange-500/20 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Nuevo Vehículo
        </a>
    </div>

    {{-- Tarjetas de Indicadores Clave (KPIs) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        
        {{-- Tarjeta 1: Total Vehículos --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-car text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Stock Total</p>
                    <h3 class="text-2xl font-black text-[#1a2b4c]">{{ $total_vehiculos }}</h3>
                </div>
            </div>
        </div>

        {{-- Tarjeta 2: Publicados --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-globe text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">En Vitrina Web</p>
                    <h3 class="text-2xl font-black text-[#1a2b4c]">{{ $publicados }}</h3>
                </div>
            </div>
        </div>

        {{-- Tarjeta 3: Valor Inventario --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#eb5e10] flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-sack-dollar text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Valor Inventario</p>
                    <h3 class="text-2xl font-black text-[#1a2b4c] truncate w-32">{{ $valor_inventario }}</h3>
                </div>
            </div>
        </div>

        {{-- Tarjeta 4: Accesos Rápidos (Usuarios) --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-16 h-16 bg-gray-50 rounded-bl-full -z-10"></div>
            <div class="flex flex-col h-full justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Administración</p>
                    <h3 class="text-lg font-bold text-[#1a2b4c]">Gestión de Usuarios</h3>
                </div>
                <a href="{{ route('usuarios.index') }}" class="text-sm font-bold text-[#eb5e10] hover:text-[#d4540e] flex items-center gap-1 mt-2 transition">
                    Ir al panel <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
        
    </div>

    {{-- Banner de Acción Secundaria --}}
    <div class="mt-8 bg-gradient-to-r from-[#1a2b4c] to-[#2a3b5c] rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-lg shadow-blue-900/10 border border-blue-800/30">
        <div class="text-white">
            <h3 class="text-xl font-bold mb-2">Visita tu Vitrina Web</h3>
            <p class="text-blue-200 text-sm max-w-xl">
                Revisa cómo se ven tus vehículos publicados desde la perspectiva de tus clientes. Los autos marcados como "Publicados" aparecerán inmediatamente aquí.
            </p>
        </div>
        <a href="{{ route('vitrina') }}" target="_blank" class="px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-sm font-bold transition whitespace-nowrap flex items-center gap-2">
            Ver Vitrina Pública
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>

</div>
@endsection