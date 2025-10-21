@php
    $backRoute = route('usuarios.index');
    $title = 'Control de rol';
@endphp
@extends('layouts.crud')

@section('content')
    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-stone-800">Roles del Sistema</h2>
            <a href="{{ route('roles.crear') }}" class="px-4 py-2 bg-stone-700 text-white rounded-lg hover:bg-stone-600">
                + Nuevo Rol
            </a>
        </div>

        @if (session('exito'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('exito') }}
            </div>
        @endif
        <div class="space-y-3">
            @foreach ($roles as $rol)
                <div class="flex justify-between items-center p-4 bg-stone-50 rounded-lg shadow hover:shadow-md transition">
                    <div>
                        <p class="font-semibold text-stone-800">{{ $rol->nombre }}</p>
                        <p class="text-sm text-stone-600">{{ $rol->descripcion ?? 'Sin descripción' }}</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse ($rol->modulos as $modulo)
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-800">
                                    {{ $modulo->nombre }}
                                </span>
                            @empty
                                <span class="text-gray-400 text-xs">Sin módulos asignados</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="flex space-x-2 items-center">
                        <a href="{{ route('roles.editar', $rol->idRol) }}"
                            class="text-stone-600 hover:text-stone-800 relative group">
                            <i class="fas fa-cog"></i>
                            <span
                                class="absolute bottom-full mb-1 hidden group-hover:block bg-black text-white text-xs rounded px-2 py-1">
                                Ajustes
                            </span>
                        </a>
                        <form action="{{ route('roles.eliminar', $rol->idRol) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="text-red-600 hover:text-red-800 delete-btn"
                                data-nombre="{{ $rol->nombre }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
