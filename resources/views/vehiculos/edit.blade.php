@extends('layouts.app')

@section('content')
<div class="p-6 md:p-8 space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('vehiculos.index') }}" class="text-sm font-bold text-gray-400 hover:text-[#eb5e10] transition flex items-center gap-2 mb-2">
                <i class="fa-solid fa-arrow-left"></i> Volver al inventario
            </a>
            <h1 class="text-2xl font-black tracking-tight text-[#1a2b4c]">Editar Vehículo: {{ $vehiculo->modelo->marca->nombre ?? '' }} {{ $vehiculo->modelo->nombre ?? '' }}</h1>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5"></i>
        <div>
            <p class="text-sm font-bold text-red-800">Corrige los siguientes errores:</p>
            <ul class="text-sm text-red-600 mt-1 list-disc pl-4">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('vehiculos.update', $vehiculo->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            
            {{-- DATOS BÁSICOS --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400 border-b border-gray-100 pb-3">Información General</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Marca <span class="text-red-400">*</span></label>
                        <select id="marca_id" name="marca_id" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
                            <option value="">Seleccionar</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}" {{ (old('marca_id') ?? $vehiculo->modelo->marca_id) == $marca->id ? 'selected' : '' }}>
                                    {{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Modelo <span class="text-red-400">*</span></label>
                        <select name="modelo_id" id="modelo_id" required data-selected="{{ old('modelo_id', $vehiculo->modelo_id) }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
                            <option value="">Seleccionar modelo</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Versión</label>
                        <input type="text" name="version" value="{{ old('version', $vehiculo->version) }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Año <span class="text-red-400">*</span></label>
                        <input type="number" name="ano" value="{{ old('ano', $vehiculo->ano) }}" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kilometraje <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="number" name="kilometraje" value="{{ old('kilometraje', $vehiculo->kilometraje) }}" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-semibold focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">KM</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Transmisión</label>
                        <select name="transmision" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
                            <option value="Manual" {{ old('transmision', $vehiculo->transmision) == 'Manual' ? 'selected' : '' }}>Manual</option>
                            <option value="Automática" {{ old('transmision', $vehiculo->transmision) == 'Automática' ? 'selected' : '' }}>Automática</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Combustible</label>
                        <select name="combustible" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
                            <option value="Gasolina" {{ old('combustible', $vehiculo->combustible) == 'Gasolina' ? 'selected' : '' }}>Gasolina</option>
                            <option value="Diésel" {{ old('combustible', $vehiculo->combustible) == 'Diésel' ? 'selected' : '' }}>Diésel</option>
                            <option value="Eléctrico" {{ old('combustible', $vehiculo->combustible) == 'Eléctrico' ? 'selected' : '' }}>Eléctrico</option>
                            <option value="Híbrido" {{ old('combustible', $vehiculo->combustible) == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tracción</label>
                        <select name="traccion" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
                            <option value="2WD" {{ old('traccion', $vehiculo->traccion) == '2WD' ? 'selected' : '' }}>2WD</option>
                            <option value="4WD" {{ old('traccion', $vehiculo->traccion) == '4WD' ? 'selected' : '' }}>4WD</option>
                            <option value="AWD" {{ old('traccion', $vehiculo->traccion) == 'AWD' ? 'selected' : '' }}>AWD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Precio Venta <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-extrabold">$</span>
                            <input type="number" name="precio_venta" value="{{ old('precio_venta', $vehiculo->precio_venta) }}" required class="w-full pl-8 pr-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
                        </div>
                    </div>
                </div>
            </div>

            {{-- EQUIPAMIENTO --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400 border-b border-gray-100 pb-3 mb-4">Equipamiento</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @php
                        $extras_base = ['Aire acondicionado', 'Cierre centralizado', 'Airbags', 'Alza vidrios eléctricos', 'Espejos eléctricos', 'Control al volante', 'Velocidad crucero', 'Radio Touch', 'Cámara de Retroceso', 'Neblineros', 'Techo panorámico', 'Asientos de Cuero', 'Llantas de aleación', 'Botón de encendido'];
                        $equipo_actual = is_string($vehiculo->equipamiento) ? json_decode($vehiculo->equipamiento, true) ?? [] : (is_array($vehiculo->equipamiento) ? $vehiculo->equipamiento : []);
                        $all_extras = array_unique(array_merge($extras_base, $equipo_actual));
                    @endphp

                    @foreach($all_extras as $extra)
                    <label class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-gray-100 bg-gray-50/50 hover:border-orange-200 cursor-pointer transition text-sm font-medium">
                        <input type="checkbox" name="equipamiento[]" value="{{ $extra }}" {{ in_array($extra, old('equipamiento', $equipo_actual)) ? 'checked' : '' }} class="rounded text-[#eb5e10] focus:ring-[#eb5e10] w-4 h-4 border-gray-300">
                        <span class="text-gray-600 truncate" title="{{ $extra }}">{{ $extra }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- ESTADO Y ACCIONES --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="w-full sm:w-1/2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Estado de Publicación</label>
                        <select name="estado_publicacion" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-sm font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
                            <option value="Preparación" {{ old('estado_publicacion', $vehiculo->estado_publicacion) == 'Preparación' ? 'selected' : '' }}>🔧 En Preparación</option>
                            <option value="Publicado" {{ old('estado_publicacion', $vehiculo->estado_publicacion) == 'Publicado' ? 'selected' : '' }}>✅ Publicado en Vitrina</option>
                            <option value="Vendido" {{ old('estado_publicacion', $vehiculo->estado_publicacion) == 'Vendido' ? 'selected' : '' }}>🏷️ Vendido</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end w-full sm:w-auto">
                        <button type="submit" class="px-8 py-3.5 bg-[#eb5e10] hover:bg-[#d4540e] text-white rounded-xl text-sm font-extrabold transition shadow-md shadow-orange-500/20 tracking-wide w-full sm:w-auto">
                            Actualizar Vehículo
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const marcaSelect = document.getElementById('marca_id');
    const modeloSelect = document.getElementById('modelo_id');
    
    // Función para cargar los modelos dependiendo de la marca
    function cargarModelos(marcaId, modeloSeleccionado = null) {
        if (!marcaId) {
            modeloSelect.innerHTML = '<option value="">Seleccionar marca primero</option>';
            return;
        }

        modeloSelect.innerHTML = '<option value="">Cargando modelos...</option>';

        fetch(`/api/modelos?marca_id=${marcaId}`)
            .then(res => res.json())
            .then(data => {
                modeloSelect.innerHTML = '<option value="">Seleccionar modelo</option>';
                data.forEach(modelo => {
                    let selected = (modeloSeleccionado == modelo.id) ? 'selected' : '';
                    modeloSelect.innerHTML += `<option value="${modelo.id}" ${selected}>${modelo.nombre}</option>`;
                });
            })
            .catch(() => {
                modeloSelect.innerHTML = '<option value="">⚠️ Error al cargar</option>';
            });
    }

    // Cargar modelos automáticamente al entrar a Editar
    const marcaActual = marcaSelect.value;
    const modeloActual = modeloSelect.getAttribute('data-selected');
    if (marcaActual) {
        cargarModelos(marcaActual, modeloActual);
    }

    // Cambiar modelos cuando el usuario cambia de marca
    marcaSelect.addEventListener('change', function() {
        cargarModelos(this.value);
    });
});
</script>
@endsection