@extends('layouts.app')

@section('content')
<div class="p-6 md:p-8 space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-[#1a2b4c]">Marcas y Modelos</h1>
            <p class="text-sm font-medium text-gray-500 mt-1">Gestiona las marcas y sus modelos disponibles en el sistema.</p>
        </div>
        <button onclick="document.getElementById('modal-nueva-marca').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#eb5e10] hover:bg-[#d4540e] text-white rounded-xl text-sm font-bold transition shadow-md shrink-0">
            <i class="fa-solid fa-plus"></i> Nueva Marca
        </button>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 font-medium text-sm shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3 font-medium text-sm shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-lg"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Lista de Marcas --}}
    <div class="space-y-4">
        @forelse($marcas as $marca)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            
            {{-- Header de la Marca --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#1a2b4c] flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-car text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-black text-[#1a2b4c] text-base">{{ $marca->nombre }}</p>
                        <p class="text-xs text-gray-400 font-medium">{{ $marca->modelos_count }} {{ $marca->modelos_count == 1 ? 'modelo' : 'modelos' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Botón editar marca --}}
                    <button onclick="abrirEditarMarca({{ $marca->id }}, '{{ $marca->nombre }}')"
                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-[#1a2b4c] hover:border-[#1a2b4c] flex items-center justify-center transition text-sm">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    {{-- Botón eliminar marca --}}
                    <form action="{{ route('marcas.destroy', $marca->id) }}" method="POST" 
                          onsubmit="return confirm('¿Eliminar la marca {{ $marca->nombre }}? Solo es posible si no tiene modelos.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-300 flex items-center justify-center transition text-sm">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                    {{-- Botón agregar modelo --}}
                    <button onclick="abrirNuevoModelo({{ $marca->id }}, '{{ $marca->nombre }}')"
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-[#eb5e10] hover:bg-[#d4540e] text-white rounded-lg text-xs font-bold transition">
                        <i class="fa-solid fa-plus"></i> Modelo
                    </button>
                </div>
            </div>

            {{-- Modelos de la marca --}}
            @if($marca->modelos->count() > 0)
            <div class="px-6 py-3">
                <div class="flex flex-wrap gap-2">
                    @foreach($marca->modelos->sortBy('nombre') as $modelo)
                    <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5">
                        <span class="text-sm font-semibold text-gray-700">{{ $modelo->nombre }}</span>
                        <button onclick="abrirEditarModelo({{ $modelo->id }}, '{{ $modelo->nombre }}')"
                                class="text-gray-400 hover:text-[#1a2b4c] transition text-xs">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('modelos.destroy', $modelo->id) }}" method="POST" class="inline m-0"
                              onsubmit="return confirm('¿Eliminar el modelo {{ $modelo->nombre }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition text-xs">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="px-6 py-4 text-sm text-gray-400 font-medium italic">
                Sin modelos registrados. Agrega el primero con el botón "+ Modelo".
            </div>
            @endif

        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <i class="fa-solid fa-car text-4xl text-gray-200 mb-4"></i>
            <p class="text-gray-500 font-medium">No hay marcas registradas aún.</p>
        </div>
        @endforelse
    </div>

</div>

{{-- MODAL: Nueva Marca --}}
<div id="modal-nueva-marca" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h2 class="text-lg font-black text-[#1a2b4c] mb-4">Nueva Marca</h2>
        <form action="{{ route('marcas.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre de la Marca</label>
                <input type="text" name="nombre" required autofocus
                       placeholder="Ej: TOYOTA, HONDA, BMW"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modal-nueva-marca').classList.add('hidden')"
                        class="flex-1 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl text-sm font-bold transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-[#eb5e10] hover:bg-[#d4540e] text-white rounded-xl text-sm font-bold transition">
                    Crear Marca
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Editar Marca --}}
<div id="modal-editar-marca" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h2 class="text-lg font-black text-[#1a2b4c] mb-4">Editar Marca</h2>
        <form id="form-editar-marca" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre de la Marca</label>
                <input type="text" name="nombre" id="input-editar-marca" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modal-editar-marca').classList.add('hidden')"
                        class="flex-1 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl text-sm font-bold transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-[#1a2b4c] hover:bg-[#233a66] text-white rounded-xl text-sm font-bold transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Nuevo Modelo --}}
<div id="modal-nuevo-modelo" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h2 class="text-lg font-black text-[#1a2b4c] mb-1">Nuevo Modelo</h2>
        <p class="text-sm text-gray-400 mb-4">Marca: <span id="label-marca-modelo" class="font-bold text-[#1a2b4c]"></span></p>
        <form action="{{ route('modelos.store') }}" method="POST">
            @csrf
            <input type="hidden" name="marca_id" id="input-marca-id-modelo">
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre del Modelo</label>
                <input type="text" name="nombre" required autofocus
                       placeholder="Ej: COROLLA, CIVIC, SERIE 3"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modal-nuevo-modelo').classList.add('hidden')"
                        class="flex-1 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl text-sm font-bold transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-[#eb5e10] hover:bg-[#d4540e] text-white rounded-xl text-sm font-bold transition">
                    Crear Modelo
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Editar Modelo --}}
<div id="modal-editar-modelo" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h2 class="text-lg font-black text-[#1a2b4c] mb-4">Editar Modelo</h2>
        <form id="form-editar-modelo" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre del Modelo</label>
                <input type="text" name="nombre" id="input-editar-modelo" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modal-editar-modelo').classList.add('hidden')"
                        class="flex-1 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl text-sm font-bold transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-[#1a2b4c] hover:bg-[#233a66] text-white rounded-xl text-sm font-bold transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirEditarMarca(id, nombre) {
    document.getElementById('input-editar-marca').value = nombre;
    document.getElementById('form-editar-marca').action = '/marcas/' + id;
    document.getElementById('modal-editar-marca').classList.remove('hidden');
}

function abrirNuevoModelo(marcaId, marcaNombre) {
    document.getElementById('input-marca-id-modelo').value = marcaId;
    document.getElementById('label-marca-modelo').textContent = marcaNombre;
    document.getElementById('modal-nuevo-modelo').classList.remove('hidden');
}

function abrirEditarModelo(id, nombre) {
    document.getElementById('input-editar-modelo').value = nombre;
    document.getElementById('form-editar-modelo').action = '/modelos/' + id;
    document.getElementById('modal-editar-modelo').classList.remove('hidden');
}

// Cerrar modales al hacer clic fuera
['modal-nueva-marca','modal-editar-marca','modal-nuevo-modelo','modal-editar-modelo'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
@endsection