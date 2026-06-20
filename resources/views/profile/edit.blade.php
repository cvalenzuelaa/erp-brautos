@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Editar Perfil</h1>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full mt-1 px-4 py-2 border rounded-lg">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full mt-1 px-4 py-2 border rounded-lg">
                </div>
                
                <button type="submit" 
                        class="bg-[#eb5e10] text-white px-6 py-3 rounded-lg hover:bg-[#d4540e] transition">
                    Actualizar Perfil
                </button>
            </form>
        </div>
    </div>
</div>
@endsection