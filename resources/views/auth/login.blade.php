<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - BR Autos ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-[#1a2b4c] antialiased h-screen flex overflow-hidden">

    {{-- MITAD IZQUIERDA: Formulario --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center bg-[#f5f6fa] px-8 sm:px-16 lg:px-24">
        <div class="w-full max-w-md mx-auto">
            
            {{-- Logo --}}
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black tracking-tight text-[#1a2b4c]">
                    <span class="text-[#eb5e10]">BR</span>autos
                </h1>
                <p class="text-xs font-bold text-gray-400 mt-2 tracking-widest uppercase">Portal Administrativo</p>
            </div>

            {{-- Estado de sesión (ej. si se reseteó la contraseña) --}}
            @if (session('status'))
                <div class="mb-6 bg-green-50 text-green-700 text-sm font-medium p-4 rounded-xl border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Formulario Principal --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- Campo Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Correo Electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl bg-white text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition shadow-sm @error('email') border-red-400 bg-red-50 @enderror"
                           placeholder="admin@brautos.cl">
                    @error('email')
                        <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Contraseña --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Contraseña</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" tabindex="-1" class="text-xs font-bold text-[#eb5e10] hover:text-[#d4540e] transition">¿Olvidaste tu contraseña?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl bg-white text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] transition shadow-sm @error('password') border-red-400 bg-red-50 @enderror"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Recordarme --}}
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded text-[#eb5e10] focus:ring-[#eb5e10] w-4 h-4 border-gray-300 transition cursor-pointer">
                    <label for="remember_me" class="ml-2 text-sm font-medium text-gray-600 cursor-pointer">Mantener mi sesión iniciada</label>
                </div>

                {{-- Botón Ingresar --}}
                <button type="submit" class="w-full py-3.5 bg-[#1a2b4c] hover:bg-[#233a66] text-white rounded-xl text-sm font-extrabold transition shadow-lg shadow-blue-900/10 tracking-wide mt-2">
                    Ingresar al ERP
                </button>
            </form>
        </div>
    </div>

    {{-- MITAD DERECHA: Imagen Decorativa --}}
    <div class="hidden lg:block lg:w-1/2 bg-[#1a2b4c] relative">
        <img src="https://images.unsplash.com/photo-1560958089-b8a1929cea89?q=80&w=2071&auto=format&fit=crop" 
             alt="BR Autos" class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay">
        
        <div class="absolute inset-0 bg-gradient-to-t from-[#0a1128] via-[#1a2b4c]/80 to-transparent"></div>
        
        <div class="absolute bottom-16 left-16 right-16 text-white">
            <h2 class="text-4xl font-black mb-4 tracking-tight">Gestión Inteligente de Inventario</h2>
            <p class="text-blue-100 text-base leading-relaxed max-w-lg">
                Control total sobre tus vehículos, publicaciones en vitrina web y gestión de usuarios en una sola plataforma centralizada.
            </p>
        </div>
    </div>

</body>
</html>