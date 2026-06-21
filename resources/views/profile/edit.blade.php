@extends('layouts.app')

@section('content')
<div class="p-6 md:p-10 w-full">

    {{-- Contenedor Centrado --}}
    <div class="max-w-4xl mx-auto">
        
        {{-- Encabezado --}}
        <div class="mb-8 border-b border-gray-200 pb-4">
            <h1 class="text-3xl font-black tracking-tight text-[#1a2b4c]">Mi Perfil</h1>
            <p class="text-sm font-medium text-gray-500 mt-2">
                Administra tu información personal y la seguridad de tu cuenta.
            </p>
        </div>

        {{-- Tarjetas de Configuración --}}
        <div class="space-y-8">
            
            {{-- Tarjeta 1: Información del Perfil --}}
            <div class="p-8 sm:p-10 bg-white shadow-sm border border-gray-100 rounded-3xl">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Tarjeta 2: Actualizar Contraseña --}}
            <div class="p-8 sm:p-10 bg-white shadow-sm border border-gray-100 rounded-3xl">
                @include('profile.partials.update-password-form')
            </div>
            
        </div>

    </div>
    
</div>
@endsection