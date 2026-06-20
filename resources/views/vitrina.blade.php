@php
    // Obtenemos los vehículos publicados directo desde la base de datos de Laravel
    $vehiculos = \Illuminate\Support\Facades\DB::table('vehiculos as v')
        ->join('modelos as mo', 'v.modelo_id', '=', 'mo.id')
        ->join('marcas as ma', 'mo.marca_id', '=', 'ma.id')
        ->select(
            'v.id', 'v.version', 'v.ano', 'v.kilometraje', 'v.transmision',
            'v.combustible', 'v.precio_venta', 'v.traccion',
            'ma.nombre as marca', 'mo.nombre as modelo',
            \Illuminate\Support\Facades\DB::raw('(SELECT ruta_imagen FROM imagenes_vehiculos WHERE vehiculo_id = v.id AND es_principal = 1 LIMIT 1) as imagen_principal')
        )
        ->where('v.estado_publicacion', 'Publicado')
        ->get();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo de Vehículos - BR Autos</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .filter-content { display: none; } 
        .filter-content.active { display: block; }
        .ribbon-wrapper {
            position: absolute; top: 0; left: 0;
            width: 100px; height: 100px;
            overflow: hidden; z-index: 10; pointer-events: none;
        }
        .ribbon {
            position: absolute; top: 18px; left: -38px; width: 140px;
            text-align: center; transform: rotate(-45deg);
            background-color: #eb5e10; color: white; font-size: 11px;
            font-weight: 900; padding: 4px 0; text-transform: uppercase;
            letter-spacing: 1px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .search-container { position: relative; width: 100%; max-width: 280px; }
        .search-container i {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 14px; z-index: 2;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

    {{-- Navegación Pública Superior --}}
    <nav class="bg-[#1a2b4c] text-white p-4 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-black tracking-tight">
                <span class="text-[#eb5e10]">BR</span>autos
            </h1>
            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-300 hover:text-white transition">Acceso ERP</a>
        </div>
    </nav>

    <div id="brautos-app" class="max-w-7xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8">
        
        {{-- ASIDE FILTROS --}}
        <aside class="w-full lg:w-1/4 xl:w-1/5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                <div class="p-4 bg-[#1a2b4c] text-white font-black uppercase tracking-widest text-sm flex items-center gap-3">
                    <i class="fa-solid fa-sliders text-[#eb5e10]"></i> Filtros
                </div>

                <ul class="divide-y divide-gray-100 max-h-[75vh] overflow-y-auto">
                    {{-- Filtro Marca --}}
                    <li class="filter-group">
                        <div class="filter-header p-4 flex justify-between items-center cursor-pointer hover:bg-gray-50 transition">
                            <span class="text-[#1a2b4c] font-bold">Marca</span> <i class="fa-solid fa-chevron-down text-gray-400"></i>
                        </div>
                        <div class="filter-content px-4 pb-4 text-sm text-gray-600">
                            @php 
                                $marcas = collect($vehiculos)->pluck('marca')->unique()->sort();
                            @endphp
                            @foreach($marcas as $marca)
                                <label class="flex items-center space-x-3 mt-3 cursor-pointer hover:text-[#eb5e10] transition">
                                    <input type="checkbox" value="{{ $marca }}" class="filter-checkbox filter-brand rounded border-gray-300 text-[#eb5e10] focus:ring-[#eb5e10] w-4 h-4"> 
                                    <span class="font-medium">{{ $marca }}</span>
                                </label>
                            @endforeach
                        </div>
                    </li>
                    {{-- Filtro Año --}}
                    <li class="filter-group">
                        <div class="filter-header p-4 flex justify-between items-center cursor-pointer hover:bg-gray-50 transition">
                            <span class="text-[#1a2b4c] font-bold">Año</span> <i class="fa-solid fa-chevron-down text-gray-400"></i>
                        </div>
                        <div class="filter-content px-4 pb-4 text-sm text-gray-600">
                            @php 
                                $anos = collect($vehiculos)->pluck('ano')->unique()->sortDesc();
                            @endphp
                            @foreach($anos as $ano)
                                <label class="flex items-center space-x-3 mt-3 cursor-pointer hover:text-[#eb5e10] transition">
                                    <input type="checkbox" value="{{ $ano }}" class="filter-checkbox filter-year rounded border-gray-300 text-[#eb5e10] focus:ring-[#eb5e10] w-4 h-4"> 
                                    <span class="font-medium">{{ $ano }}</span>
                                </label>
                            @endforeach
                        </div>
                    </li>
                    {{-- Filtro Transmisión --}}
                    <li class="filter-group">
                        <div class="filter-header p-4 flex justify-between items-center cursor-pointer hover:bg-gray-50 transition">
                            <span class="text-[#1a2b4c] font-bold">Transmisión</span> <i class="fa-solid fa-chevron-down text-gray-400"></i>
                        </div>
                        <div class="filter-content px-4 pb-4 text-sm text-gray-600">
                            <label class="flex items-center space-x-3 mt-3 cursor-pointer hover:text-[#eb5e10] transition"><input type="checkbox" value="Automática" class="filter-checkbox filter-trans rounded border-gray-300 text-[#eb5e10] focus:ring-[#eb5e10] w-4 h-4"> <span class="font-medium">Automática</span></label>
                            <label class="flex items-center space-x-3 mt-3 cursor-pointer hover:text-[#eb5e10] transition"><input type="checkbox" value="Manual" class="filter-checkbox filter-trans rounded border-gray-300 text-[#eb5e10] focus:ring-[#eb5e10] w-4 h-4"> <span class="font-medium">Manual</span></label>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>

        {{-- GRILLA DE VEHICULOS --}}
        <main class="w-full lg:w-3/4 xl:w-4/5">
            
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100 gap-4">
                <span class="text-gray-600 font-medium whitespace-nowrap text-sm sm:text-lg">Catálogo: <span id="count-number" class="font-black text-[#eb5e10] text-xl">{{ count($vehiculos) }}</span> vehículos</span>
                
                <div class="search-container relative">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="search-input" placeholder="Buscar modelo o marca..." autocomplete="off"
                           class="w-full h-11 pl-10 pr-4 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-[#eb5e10] transition">
                </div>
            </div>

            <div id="car-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse ($vehiculos as $auto)
                @php
                    $img = $auto->imagen_principal ? $auto->imagen_principal : 'https://placehold.co/400x300?text=Sin+Foto';
                    $combustible = ($auto->combustible == 'Gasolina') ? 'Bencina' : $auto->combustible;
                    $search_string = strtolower($auto->marca . ' ' . $auto->modelo . ' ' . $auto->version . ' ' . $auto->ano);
                @endphp
                
                <div class="car-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 group" 
                     data-search="{{ $search_string }}"
                     data-brand="{{ $auto->marca }}"
                     data-year="{{ $auto->ano }}"
                     data-fuel="{{ $auto->combustible }}"
                     data-trans="{{ $auto->transmision }}">
                    
                    <div class="relative h-52 bg-gray-100 overflow-hidden">
                        <div class="ribbon-wrapper">
                            <div class="ribbon">{{ $auto->transmision }}</div>
                        </div>
                        <img src="{{ $img }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div class="mb-4">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 block">{{ $auto->marca }}</span>
                            <h3 class="font-black text-[#1a2b4c] text-lg leading-tight mb-2 uppercase truncate" title="{{ $auto->modelo }} {{ $auto->version }}">
                                {{ $auto->modelo }} {{ $auto->version }}
                            </h3>
                            <span class="font-black text-[#eb5e10] text-2xl block border-b border-gray-100 pb-4">${{ number_format($auto->precio_venta, 0, ',', '.') }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-y-3 gap-x-2 text-xs text-gray-600 font-bold mb-6">
                            <div class="flex items-center gap-2"><i class="fa-regular fa-calendar text-gray-400 text-sm"></i> {{ $auto->ano }}</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-gas-pump text-gray-400 text-sm"></i> {{ $combustible }}</div>
                            <div class="flex items-center gap-2 col-span-2"><i class="fa-solid fa-gauge-high text-gray-400 text-sm"></i> {{ number_format($auto->kilometraje, 0, ',', '.') }} Km.</div>
                        </div>
                        
                        <a href="#" class="w-full text-center bg-gray-50 group-hover:bg-[#1a2b4c] text-gray-600 group-hover:text-white border border-gray-200 group-hover:border-[#1a2b4c] font-bold py-2.5 rounded-xl uppercase tracking-wider text-xs transition-all duration-300">
                            Ver Detalles
                        </a>
                    </div>
                </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-2xl border border-gray-100">
                        <i class="fa-solid fa-car-side text-4xl text-gray-300 mb-3"></i>
                        <h3 class="text-xl font-bold text-gray-500">No hay vehículos publicados en este momento.</h3>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const grid = document.getElementById('car-grid');
            const cards = Array.from(grid.getElementsByClassName('car-card'));
            const checkboxes = document.querySelectorAll('.filter-checkbox');
            const searchInput = document.getElementById('search-input');
            
            function filterCards() {
                let active = { brand: [], year: [], fuel: [], trans: [] };
                document.querySelectorAll('.filter-brand:checked').forEach(cb => active.brand.push(cb.value));
                document.querySelectorAll('.filter-year:checked').forEach(cb => active.year.push(cb.value));
                document.querySelectorAll('.filter-fuel:checked').forEach(cb => active.fuel.push(cb.value));
                document.querySelectorAll('.filter-trans:checked').forEach(cb => active.trans.push(cb.value));

                let searchTerm = searchInput.value.toLowerCase().trim();
                let count = 0;

                cards.forEach(card => {
                    let showB = active.brand.length === 0 || active.brand.includes(card.getAttribute('data-brand'));
                    let showY = active.year.length === 0 || active.year.includes(card.getAttribute('data-year'));
                    let showF = active.fuel.length === 0 || active.fuel.includes(card.getAttribute('data-fuel'));
                    let showT = active.trans.length === 0 || active.trans.includes(card.getAttribute('data-trans'));
                    let cardText = card.getAttribute('data-search');
                    let showSearch = searchTerm === '' || cardText.includes(searchTerm);
                    
                    if (showB && showY && showF && showT && showSearch) { 
                        card.style.display = 'flex'; 
                        count++; 
                    } else { 
                        card.style.display = 'none'; 
                    }
                });
                document.getElementById('count-number').textContent = count;
            }
            
            checkboxes.forEach(cb => cb.addEventListener('change', filterCards));
            searchInput.addEventListener('input', filterCards);

            document.querySelectorAll('.filter-header').forEach(header => {
                header.addEventListener('click', () => {
                    const icon = header.querySelector('i');
                    icon.classList.toggle('fa-chevron-down');
                    icon.classList.toggle('fa-chevron-up');
                    header.nextElementSibling.classList.toggle('active');
                });
            });
        });
    </script>
</body>
</html>