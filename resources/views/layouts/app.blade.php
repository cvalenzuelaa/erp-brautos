<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BR Autos ERP') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800 bg-[#f5f6fa] overflow-hidden">
    
    <div class="flex h-screen w-full relative">
        
        {{-- ========================================= --}}
        {{-- SIDEBAR LATERAL (Colapsable)              --}}
        {{-- ========================================= --}}
        <aside id="main-sidebar" class="w-64 bg-[#1a2b4c] text-white flex flex-col transition-all duration-300 shadow-xl z-30 shrink-0 absolute md:relative h-full overflow-hidden">
            {{-- Logo --}}
            <div class="h-[60px] flex items-center px-6 border-b border-white/5 shrink-0">
                <h2 class="text-2xl font-black tracking-tight whitespace-nowrap">
                    <span class="text-[#eb5e10]">BR</span>autos
                </h2>
            </div>

            {{-- Menú de Navegación (Solo vistas desarrolladas) --}}
            <nav class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto custom-scrollbar">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition whitespace-nowrap {{ request()->routeIs('dashboard') ? 'bg-[#2a3b5c] text-white shadow-sm border border-white/5' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center {{ request()->routeIs('dashboard') ? 'text-[#eb5e10]' : 'opacity-70' }}"></i>
                    <span class="font-bold text-sm">Resumen Comercial</span>
                </a>

                <a href="{{ route('vehiculos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition whitespace-nowrap {{ request()->routeIs('vehiculos.*') ? 'bg-[#2a3b5c] text-white shadow-sm border border-white/5' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-car w-5 text-center {{ request()->routeIs('vehiculos.*') ? 'text-[#eb5e10]' : 'opacity-70' }}"></i>
                    <span class="font-bold text-sm">Inventario</span>
                </a>

                <a href="{{ route('marcas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition whitespace-nowrap {{ request()->routeIs('marcas.*') || request()->routeIs('modelos.*') ? 'bg-[#2a3b5c] text-white shadow-sm border border-white/5' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-tags w-5 text-center {{ request()->routeIs('marcas.*') || request()->routeIs('modelos.*') ? 'text-[#eb5e10]' : 'opacity-70' }}"></i>
                    <span class="font-bold text-sm">Marcas y Modelos</span>
                </a>

                <a href="{{ route('vitrina') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl transition whitespace-nowrap text-gray-300 hover:text-white hover:bg-white/5">
                    <i class="fa-solid fa-globe w-5 text-center opacity-70"></i>
                    <span class="font-bold text-sm">Vitrina Web</span>
                </a>
            </nav>

            {{-- Perfil de Usuario y Opciones --}}
            <div class="relative border-t border-white/5 bg-[#142340] shrink-0">
                {{-- Menú Desplegable de Usuario --}}
                <div id="user-dropdown" class="hidden absolute bottom-full left-0 w-full bg-[#1a2b4c] border-t border-gray-700/50 shadow-lg">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-6 py-3 text-sm text-gray-300 hover:text-white hover:bg-white/5 transition border-b border-gray-700/50">
                        <i class="fa-solid fa-user-pen w-4 text-center"></i> Mi Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full m-0">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-6 py-3 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition text-left">
                            <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>

                {{-- Botón para abrir menú de usuario --}}
                <button onclick="document.getElementById('user-dropdown').classList.toggle('hidden')" class="w-full p-4 flex items-center justify-between hover:bg-white/5 transition cursor-pointer focus:outline-none">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#eb5e10] flex items-center justify-center text-white font-bold text-sm shadow-inner shrink-0">
                            {{ substr(Auth::user()->name ?? 'BR', 0, 2) }}
                        </div>
                        <div class="text-left overflow-hidden">
                            <p class="text-sm font-bold text-white truncate w-28">{{ Auth::user()->name ?? 'Usuario' }}</p>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">Administrador</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-up text-gray-500 text-xs"></i>
                </button>
            </div>
        </aside>

        {{-- ========================================= --}}
        {{-- CONTENEDOR PRINCIPAL                      --}}
        {{-- ========================================= --}}
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#f5f6fa] relative w-full">
            
            {{-- Barra superior global (Para el botón colapsar) --}}
            <header class="bg-white border-b border-gray-200 h-[60px] flex items-center justify-between px-4 shrink-0 shadow-sm z-20">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-[#eb5e10] focus:outline-none p-2 rounded-lg hover:bg-gray-50 transition border border-transparent hover:border-gray-200">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </header>

            {{-- Contenido inyectado por las vistas --}}
            <div class="flex-1 overflow-y-auto">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-3 text-sm font-medium">
    {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-3 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
        
        {{-- Overlay para móviles (Cierra el menú al hacer clic fuera en celulares) --}}
        <div id="mobile-overlay" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-black/50 z-20 md:hidden"></div>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            // Alternar clases de Tailwind para ocultar/mostrar
            if (sidebar.classList.contains('w-64')) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-0', '-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.remove('w-0', '-translate-x-full');
                sidebar.classList.add('w-64');
                overlay.classList.remove('hidden');
            }
        }

        // Cierra el menú desplegable del usuario si se hace clic fuera de él
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-dropdown');
            const userBtn = userMenu.nextElementSibling;
            if (!userBtn.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>