@php
    $backRoute = route('roles.index');
    $title = 'Nuevo rol';
@endphp
@extends('layouts.crud')

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-rose-100 text-rose-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('roles.guardar') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4">

                <div>
                    <label for="nombre" class="block text-sm font-medium text-stone-700">Nombre del Rol</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200">
                    @error('nombre')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-stone-700">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-2">Asignar Módulos</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($modulos as $modulo)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="modulos[]" value="{{ $modulo->idModulo }}"
                                    class="h-4 w-4 text-amber-400 border-gray-300 rounded focus:ring-amber-200"
                                    {{ in_array($modulo->idModulo, old('modulos', [])) ? 'checked' : '' }}>
                                <span class="text-gray-700">{{ $modulo->nombre }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('modulos')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-amber-400 hover:bg-amber-300 text-white font-semibold rounded-lg shadow">
                    Guardar Rol
                </button>
            </div>

        </form>
    </div>
@endsection
