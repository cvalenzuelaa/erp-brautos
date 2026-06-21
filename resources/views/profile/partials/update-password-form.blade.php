<section>
    <header>
        <h2 class="text-lg font-bold text-[#1a2b4c]">
            Actualizar Contraseña
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerte seguro.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Contraseña Actual --}}
        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Contraseña Actual</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                   class="w-full max-w-md px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
            @error('current_password', 'updatePassword') 
                <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Nueva Contraseña --}}
        <div>
            <label for="update_password_password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nueva Contraseña</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                   class="w-full max-w-md px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
            @error('password', 'updatePassword') 
                <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Confirmar Contraseña --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Confirmar Nueva Contraseña</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="w-full max-w-md px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-[#eb5e10] focus:bg-white transition">
            @error('password_confirmation', 'updatePassword') 
                <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Botón y Mensaje de Éxito --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 bg-[#eb5e10] hover:bg-[#d4540e] text-white rounded-xl text-sm font-extrabold transition shadow-md shadow-orange-500/20">
                Actualizar Contraseña
            </button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-emerald-600 font-bold bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100"
                   x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                    <i class="fa-solid fa-check mr-1"></i> Contraseña Actualizada
                </p>
            @endif
        </div>
    </form>
</section>