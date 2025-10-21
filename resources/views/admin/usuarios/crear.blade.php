@php
    $backRoute = route('usuarios.index');
    $title = 'Nuevo usuario';
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

        <form action="{{ route('usuarios.guardar') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label for="ciUsuario" class="block text-sm font-medium text-stone-700">CI del Usuario</label>
                    <input type="text" name="ciUsuario" id="ciUsuario" value="{{ old('ciUsuario') }}" maxlength="8"
                        pattern="[0-9]{7,8}"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200"
                        required>
                </div>

                <div>
                    <label for="usuario" class="block text-sm font-medium text-stone-700">Usuario</label>
                    <input type="text" name="usuario" id="usuario" value="{{ old('usuario') }}" maxlength="50"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200"
                        required>
                </div>

                <div>
                    <label for="nombre" class="block text-sm font-medium text-stone-700">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" maxlength="50"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200"
                        required>
                </div>

                <div>
                    <label for="apellido" class="block text-sm font-medium text-stone-700">Apellido</label>
                    <input type="text" name="apellido" id="apellido" value="{{ old('apellido') }}" maxlength="60"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200"
                        required>
                </div>

                <div>
                    <label for="correo" class="block text-sm font-medium text-stone-700">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" value="{{ old('correo') }}" maxlength="100"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200"
                        required>
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-medium text-stone-700">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" maxlength="8"
                        pattern="[0-9]{7,8}"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200">
                </div>

                <div>
                    <label for="contrasena" class="block text-sm font-medium text-stone-700">Contraseña</label>
                    <input type="password" name="contrasena" id="contrasena" minlength="8"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200"
                        required>
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-stone-700">Estado</label>
                    <select name="estado" id="estado"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200"
                        required>
                        <option value="1" {{ old('estado', 1) == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado') == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div>
                    <label for="rolId" class="block text-sm font-medium text-stone-700">Rol</label>
                    <select name="rolId" id="rolId"
                        class="mt-1 block w-full border border-stone-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-200"
                        required>
                        <option value="">-- Seleccione un rol --</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->idRol }}" {{ old('rolId') == $rol->idRol ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-amber-400 hover:bg-amber-300 text-white font-semibold rounded-lg shadow">
                    Guardar Usuario
                </button>
            </div>
        </form>
    </div>
@endsection
