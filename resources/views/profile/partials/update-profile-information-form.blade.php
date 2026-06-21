<section>
    <header>
        <h2 class="text-lg font-bold text-[#1a2b4c]">
            Información del Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Actualiza el nombre y la dirección de correo electrónico de tu cuenta.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Input Nombre --}}
        <div>
            <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre Completo</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="w-full max-w-md px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
            @error('name') 
                <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Input Email --}}
        <div>
            <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Correo Electrónico</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="w-full max-w-md px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
            @error('email') 
                <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Botón y Mensaje de Éxito --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 bg-[#1a2b4c] hover:bg-[#233a66] text-white rounded-xl text-sm font-extrabold transition shadow-md shadow-blue-900/10">
                Guardar Cambios
            </button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-emerald-600 font-bold bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100"
                   x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                    <i class="fa-solid fa-check mr-1"></i> Guardado exitosamente
                </p>
            @endif
        </div>
    </form>
</section>