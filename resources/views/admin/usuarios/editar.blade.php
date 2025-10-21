@php
    $backRoute = route('usuarios.index');
    $title = 'Editar usuario';
@endphp
@extends('layouts.crud')

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">

        <h2 class="text-xl font-semibold text-stone-800 mb-4">{{ $title }}</h2>

        <form action="{{ route('usuarios.actualizar', $usuario->ciUsuario) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label for="ciUsuario" class="block text-sm font-medium text-stone-700">CI del Usuario</label>
                    <input type="text" name="ciUsuario" id="ciUsuario" value="{{ old('ciUsuario', $usuario->ciUsuario) }}"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed"
                        readonly>
                </div>

                <div>
                    <label for="usuario" class="block text-sm font-medium text-stone-700">Usuario</label>
                    <input type="text" name="usuario" id="usuario" value="{{ old('usuario', $usuario->usuario) }}"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                    @error('usuario')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nombre" class="block text-sm font-medium text-stone-700">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $usuario->nombre) }}"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                    @error('nombre')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="apellido" class="block text-sm font-medium text-stone-700">Apellido</label>
                    <input type="text" name="apellido" id="apellido" value="{{ old('apellido', $usuario->apellido) }}"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                    @error('apellido')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="correo" class="block text-sm font-medium text-stone-700">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" value="{{ old('correo', $usuario->correo) }}"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                    @error('correo')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-medium text-stone-700">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                    @error('telefono')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contrasena" class="block text-sm font-medium text-stone-700">Nueva Contraseña</label>
                    <input type="password" name="contrasena" id="contrasena"
                        placeholder="Dejar en blanco para mantener la actual"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                    @error('contrasena')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-stone-700">Estado</label>
                    <select name="estado" id="estado"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                        <option value="1" {{ old('estado', $usuario->estado ?? 1) == 1 ? 'selected' : '' }}>Activo
                        </option>
                        <option value="0" {{ old('estado', $usuario->estado ?? 1) == 0 ? 'selected' : '' }}>Inactivo
                        </option>
                    </select>
                    @error('estado')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rolId" class="block text-sm font-medium text-stone-700">Rol</label>
                    <select name="rolId" id="rolId"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                        <option value="">-- Seleccione un rol --</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->idRol }}"
                                {{ old('rolId', $usuario->rolId) == $rol->idRol ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('rolId')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ $backRoute }}"
                    class="px-6 py-2 bg-stone-200 hover:bg-stone-300 text-stone-700 font-medium rounded-lg shadow">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-amber-500 hover:bg-amber-400 text-white font-semibold rounded-lg shadow">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
@endsection
