@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f8f9fa] text-[#1a2b4c] antialiased">

    {{-- HEADER SUPERIOR --}}
    <div class="bg-white border-b border-gray-100 px-6 py-4 sticky top-0 z-10 shadow-sm">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('vehiculos.index') }}" 
                   class="w-9 h-9 flex items-center justify-center bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">Gestión de Inventario</p>
                    <h1 class="text-xl font-extrabold tracking-tight">Añadir Nuevo Vehículo</h1>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-2 bg-orange-50 border border-orange-100 px-3 py-1.5 rounded-lg text-xs text-orange-700 font-medium">
                <span class="text-orange-500 font-bold">*</span> Campos obligatorios
            </div>
        </div>
    </div>

    {{-- ALERTAS DE ERROR --}}
    @if($errors->any())
    <div class="max-w-6xl mx-auto mt-6 px-6">
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-bold text-red-800">Por favor, corrige los siguientes campos:</p>
                <ul class="text-sm text-red-600 mt-1 space-y-0.5 list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- FORMULARIO PRINCIPAL --}}
    <form method="POST" action="{{ route('vehiculos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="max-w-6xl mx-auto px-6 py-6 space-y-6">

            {{-- SECCIÓN 1: DATOS BÁSICOS (Tarjeta Blanca Estilo ERP) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400">Información General</h2>
                </div>

                {{-- Fila 1: Marca, Modelo, Versión --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Marca <span class="text-red-400">*</span></label>
                        <select id="marca_id" name="marca_id" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
                            <option value="">Seleccionar</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Modelo <span class="text-red-400">*</span></label>
                        <select name="modelo_id" id="modelo_id" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition @error('modelo_id') border-red-400 bg-red-50/30 @enderror">
                            <option value="">Seleccionar marca primero</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Versión</label>
                        <input type="text" name="version" value="{{ old('version') }}"
                               placeholder="Ej: HB 1.6 Turbo"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
                    </div>
                </div>

                {{-- Fila 2: Año, Kilometraje, Transmisión --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Año <span class="text-red-400">*</span></label>
                        <input type="number" name="ano" value="{{ old('ano') }}" required
                               min="1990" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition @error('ano') border-red-400 bg-red-50/30 @enderror">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kilometraje <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="number" name="kilometraje" value="{{ old('kilometraje', 0) }}" required min="0"
                                   placeholder="0"
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition @error('kilometraje') border-red-400 bg-red-50/30 @enderror">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 bg-gray-200/50 px-2 py-0.5 rounded-md">KM</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Transmisión <span class="text-red-400">*</span></label>
                        <div class="flex p-1 bg-gray-100 rounded-xl gap-1">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="transmision" value="Manual" 
                                       {{ old('transmision') == 'Manual' ? 'checked' : '' }}
                                       class="peer hidden">
                                <span class="block text-center text-sm font-semibold py-2 rounded-lg text-gray-500 peer-checked:bg-white peer-checked:text-[#eb5e10] peer-checked:shadow-sm transition duration-200">Manual</span>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="transmision" value="Automática"
                                       {{ old('transmision','Automática') == 'Automática' ? 'checked' : '' }}
                                       class="peer hidden">
                                <span class="block text-center text-sm font-semibold py-2 rounded-lg text-gray-500 peer-checked:bg-white peer-checked:text-[#eb5e10] peer-checked:shadow-sm transition duration-200">Automática</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Fila 3: Combustible, Tracción, Motor --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Combustible <span class="text-red-400">*</span></label>
                        <select name="combustible" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
                            <option value="Gasolina" {{ old('combustible','Gasolina') == 'Gasolina' ? 'selected' : '' }}>Gasolina</option>
                            <option value="Diésel" {{ old('combustible') == 'Diésel' ? 'selected' : '' }}>Diésel</option>
                            <option value="Eléctrico" {{ old('combustible') == 'Eléctrico' ? 'selected' : '' }}>Eléctrico</option>
                            <option value="Híbrido" {{ old('combustible') == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tracción</label>
                        <select name="traccion"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
                            <option value="2WD" {{ old('traccion','2WD') == '2WD' ? 'selected' : '' }}>2WD</option>
                            <option value="4WD" {{ old('traccion') == '4WD' ? 'selected' : '' }}>4WD</option>
                            <option value="AWD" {{ old('traccion') == 'AWD' ? 'selected' : '' }}>AWD</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Motor</label>
                        <input type="text" name="motor" value="{{ old('motor') }}"
                               placeholder="Ej: 1.6L Turbo, 2.0L"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
                    </div>
                </div>

                {{-- Fila 4: Precio, Color, Condición --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Precio de Venta <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-extrabold text-base">$</span>
                            <input type="number" name="precio_venta" value="{{ old('precio_venta') }}" required
                                   min="0" placeholder="16790000"
                                   class="w-full pl-8 pr-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition @error('precio_venta') border-red-400 bg-red-50/30 @enderror">
                        </div>
                        <p class="text-xs text-emerald-600 font-bold mt-1.5 min-h-[16px]" id="precio-preview"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Color</label>
                        <input type="text" name="color" value="{{ old('color') }}"
                               placeholder="Ej: Blanco, Negro Metálico"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Condición</label>
                        <select name="condicion"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
                            <option value="Usado" {{ old('condicion','Usado') == 'Usado' ? 'selected' : '' }}>Usado</option>
                            <option value="Nuevo" {{ old('condicion') == 'Nuevo' ? 'selected' : '' }}>Nuevo</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: EQUIPAMIENTO --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400">Equipamiento y Extras</h2>
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5" id="equipamiento-grid">
                    @php
                    $extras = [
                        'Aire acondicionado', 'Cierre centralizado', 'Airbags', 'Alza vidrios eléctricos',
                        'Espejos eléctricos', 'Control al volante', 'Velocidad crucero', 'Radio touch',
                        'Cámara y sensores', 'Neblineros', 'Techo panorámico', 'Asientos calefaccionados',
                        'ECO Mode', 'Control tracción', 'Start-stop', 'Paddle shift',
                        'Volante multifuncional', 'Espejos abatibles', 'Lona marítima',
                    ];
                    $oldEquipamiento = old('equipamiento', []);
                    @endphp

                    @foreach($extras as $extra)
                    <label class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-orange-50/30 hover:border-orange-200 cursor-pointer transition text-sm font-medium">
                        <input type="checkbox" name="equipamiento[]" value="{{ $extra }}"
                               {{ in_array($extra, $oldEquipamiento) ? 'checked' : '' }}
                               class="rounded text-[#eb5e10] focus:ring-[#eb5e10] w-4 h-4 border-gray-300">
                        <span class="text-gray-600">{{ $extra }}</span>
                    </label>
                    @endforeach
                </div>

                {{-- Agregar Extra Personalizado --}}
                <div class="mt-4 pt-4 border-t border-gray-100 flex gap-3 max-w-md">
                    <input type="text" id="extra-custom" placeholder="Escribe un accesorio personalizado..."
                           class="flex-1 px-3 py-2 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
                    <button type="button" onclick="agregarExtra()"
                            class="px-4 py-2 bg-[#1a2b4c] hover:bg-[#233a66] text-white rounded-xl text-sm font-bold transition shadow-sm">
                        Agregar
                    </button>
                </div>
                <div id="extras-custom-list" class="mt-3 flex flex-wrap gap-2"></div>
            </div>

            {{-- SECCIÓN 3: ESTADO + IMÁGENES (Dos columnas de tarjetas) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Columna Izquierda: Estado de Publicación --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <div class="border-b border-gray-100 pb-3 mb-4">
                            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400">Estado Operativo</h2>
                        </div>
                        
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Flujo de Publicación</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['Preparación' => '🔧 En Prep.', 'Publicado' => '✅ Publicar', 'Vendido' => '🏷️ Vendido'] as $value => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="estado_publicacion" value="{{ $value }}"
                                       {{ old('estado_publicacion','Preparación') == $value ? 'checked' : '' }}
                                       class="peer hidden">
                                <span class="block text-center text-xs sm:text-sm font-bold py-3 border border-gray-100 rounded-xl bg-gray-50/50 text-gray-600 peer-checked:border-orange-500 peer-checked:bg-orange-50/40 peer-checked:text-[#eb5e10] transition duration-200">
                                    {{ $label }}
                                </span>
                            </label>
                            @endforeach
                        </div>

                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mt-5 mb-2.5">Origen del Vehículo</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="consignado" value="0"
                                       {{ old('consignado','0') == '0' ? 'checked' : '' }}
                                       class="peer hidden">
                                <span class="block text-center text-sm font-bold py-3 border border-gray-100 rounded-xl bg-gray-50/50 text-gray-600 peer-checked:border-[#1a2b4c] peer-checked:bg-[#1a2b4c]/5 peer-checked:text-[#1a2b4c] transition duration-200">
                                    Stock Propio
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="consignado" value="1"
                                       {{ old('consignado') == '1' ? 'checked' : '' }}
                                       class="peer hidden">
                                <span class="block text-center text-sm font-bold py-3 border border-gray-100 rounded-xl bg-gray-50/50 text-gray-600 peer-checked:border-[#1a2b4c] peer-checked:bg-[#1a2b4c]/5 peer-checked:text-[#1a2b4c] transition duration-200">
                                    Consignación
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Columna Derecha: Dropzone de Fotos --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="border-b border-gray-100 pb-3 mb-4">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400">Galería de Imágenes</h2>
                    </div>

                    <div id="drop-zone" 
                         class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-[#eb5e10] hover:bg-orange-50/20 transition cursor-pointer group">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-[#eb5e10] group-hover:bg-orange-50 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-700">Arrastra fotos aquí o haz clic</p>
                                <p class="text-xs text-gray-400 mt-0.5">Soporta JPG, PNG, WEBP — Máx 5MB</p>
                            </div>
                        </div>
                        <input type="file" name="imagenes[]" multiple accept="image/*" class="hidden" id="file-input">
                    </div>
                    <div id="preview-container" class="grid grid-cols-4 gap-2 mt-4"></div>
                </div>
            </div>

            {{-- FOOTER DE ACCIONES --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-white rounded-2xl border border-gray-100 p-5 gap-4 shadow-sm">
                <div class="flex items-center gap-2.5 text-xs text-gray-400 font-medium">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                    El vehículo se creará inicialmente bajo el estado operativo asignado.
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('vehiculos.index') }}" 
                       class="flex-1 sm:flex-none text-center px-5 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl text-sm font-bold transition">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="flex-1 sm:flex-none text-center px-6 py-2.5 bg-[#eb5e10] hover:bg-[#d4540e] text-white rounded-xl text-sm font-extrabold transition shadow-md shadow-orange-500/10 tracking-wide">
                        Guardar Vehículo
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
// ── Drag & Drop de Imágenes ──────────────────────────────────────────
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file-input');
const previewContainer = document.getElementById('preview-container');

dropZone.addEventListener('click', () => fileInput.click());

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-[#eb5e10]', 'bg-orange-50/20');
});
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-[#eb5e10]', 'bg-orange-50/20');
});
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-[#eb5e10]', 'bg-orange-50/20');
    fileInput.files = e.dataTransfer.files;
    updatePreview();
});
fileInput.addEventListener('change', updatePreview);

function updatePreview() {
    previewContainer.innerHTML = '';
    Array.from(fileInput.files).forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewContainer.innerHTML += `
                <div class="relative rounded-xl overflow-hidden aspect-square bg-gray-50 border-2 ${idx === 0 ? 'border-[#eb5e10]' : 'border-transparent'} group shadow-inner">
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    ${idx === 0 ? '<span class="absolute bottom-1.5 left-1.5 bg-[#eb5e10] text-white text-[9px] px-1.5 py-0.5 rounded-md font-bold uppercase tracking-wider shadow-sm">Portada</span>' : ''}
                </div>
            `;
        };
        reader.readAsDataURL(file);
    });
}

// ── Carga Dinámica de Modelos (API) ──────────────────────────────────
document.getElementById('marca_id').addEventListener('change', function() {
    const marcaId = this.value;
    const modeloSelect = document.getElementById('modelo_id');

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
                modeloSelect.innerHTML += `<option value="${modelo.id}">${modelo.nombre}</option>`;
            });
        })
        .catch(() => {
            modeloSelect.innerHTML = '<option value="">⚠️ Error al cargar los modelos</option>';
        });
});

// ── Preview Formateado del Precio (CLP) ──────────────────────────────
document.querySelector('[name="precio_venta"]').addEventListener('input', function() {
    const val = parseInt(this.value);
    const preview = document.getElementById('precio-preview');
    if (val && val > 0) {
        preview.innerHTML = `<span class="text-gray-400 font-normal">Valor formateado:</span> $${val.toLocaleString('es-CL')}`;
    } else {
        preview.textContent = '';
    }
});

// ── Gestión de Extras Personalizados ────────────────────────────────
function agregarExtra() {
    const input = document.getElementById('extra-custom');
    const val = input.value.trim();
    if (!val) return;

    const lista = document.getElementById('extras-custom-list');
    const id = 'extra_' + Date.now();

    lista.innerHTML += `
        <div class="flex items-center gap-2 bg-orange-50 border border-orange-200 rounded-xl px-3 py-1.5 shadow-sm text-[#eb5e10] font-medium" id="${id}">
            <input type="checkbox" name="equipamiento[]" value="${val}" checked class="rounded text-[#eb5e10] focus:ring-[#eb5e10] w-3.5 h-3.5">
            <span class="text-sm">${val}</span>
            <button type="button" onclick="document.getElementById('${id}').remove()" 
                    class="ml-1 text-orange-400 hover:text-red-500 transition font-bold text-xs">✕</button>
        </div>
    `;
    input.value = '';
    input.focus();
}

document.getElementById('extra-custom').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); agregarExtra(); }
});
</script>
@endsection