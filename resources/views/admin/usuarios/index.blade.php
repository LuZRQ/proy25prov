@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <form method="GET" action="{{ route('usuarios.index') }}" class="flex flex-col md:flex-row gap-2">

            <input type="text" name="search" placeholder="Buscar usuario"
                class="w-full md:flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-400"
                value="{{ request('search') }}">

            <select name="estado"
                class="w-full md:w-auto px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-400">
                <option value="">Todos los estados</option>
                <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
            </select>

            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-stone-700 text-white rounded-lg hover:bg-stone-600">
                Buscar
            </button>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="flex justify-between items-center px-4 py-3 border-b">
            <h3 class="text-lg font-semibold">Usuarios del Sistema</h3>
            <a href="{{ route('usuarios.crear') }}"
                class="px-4 py-2 bg-stone-600 text-white rounded-lg hover:bg-stone-500">+ Nuevo Usuario</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-stone-100 text-stone-700 hidden md:table-header-group">
                    <tr>
                        <th class="px-4 py-2">Usuario</th>
                        <th class="px-4 py-2">Rol</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Último Acceso</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr class="border-t block md:table-row">
                            <td
                                class="px-4 py-3 block md:table-cell before:content-['Usuario:'] before:font-semibold before:block md:before:content-none">
                                <p class="font-semibold">{{ $usuario->nombre }} {{ $usuario->apellido }}</p>
                                <p class="text-sm text-gray-500">{{ $usuario->correo }}</p>
                            </td>

                            <td
                                class="px-4 py-3 block md:table-cell before:content-['Rol:'] before:font-semibold before:block md:before:content-none">
                                <span class="px-3 py-1 text-xs rounded-full bg-rose-100 text-rose-800">
                                    {{ $usuario->rol->nombre ?? 'Sin rol' }}
                                </span>
                            </td>

                            <td
                                class="px-4 py-3 block md:table-cell before:content-['Estado:'] before:font-semibold before:block md:before:content-none">
                                @if ($usuario->estado)
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Activo</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Inactivo</span>
                                @endif
                            </td>

                            <td
                                class="px-4 py-3 block md:table-cell before:content-['Último acceso:'] before:font-semibold before:block md:before:content-none">
                                @if ($usuario->ultimo_acceso)
                                    {{ $usuario->ultimo_acceso->diffForHumans() }}
                                    <span
                                        class="text-xs text-gray-500 block">{{ $usuario->ultimo_acceso->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="text-gray-400 italic">Nunca</span>
                                @endif
                            </td>

                            <td
                                class="px-4 py-3 flex space-x-2 block md:table-cell before:content-['Acciones:'] before:font-semibold before:block md:before:content-none">
                                <div class="flex space-x-2">
                                    <a href="{{ route('usuarios.mostrar', $usuario->ciUsuario) }}"
                                        class="text-stone-600 hover:text-stone-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('usuarios.editar', $usuario->ciUsuario) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('usuarios.eliminar', $usuario->ciUsuario) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-800 delete-btn"
                                            data-nombre="{{ $usuario->nombre }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4">Roles del Sistema</h3>
            <div class="space-y-3">
                @foreach ($roles as $rol)
                    <div class="flex justify-between items-center">
                        <p><span class="font-semibold">{{ $rol->nombre }}:</span> {{ $rol->descripcion }}</p>
                        <a href="{{ route('roles.index', $rol->idRol) }}" class="text-stone-600 hover:text-stone-800"
                            title="Ajustes">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4">Módulos y Roles Permitidos</h3>
            <div class="space-y-4">
                @foreach ($modulos as $modulo)
                    <div class="border rounded-lg p-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-semibold text-stone-700">{{ $modulo->nombre }}</span>
                            <span class="text-sm text-gray-500">{{ $modulo->descripcion }}</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($modulo->roles as $rol)
                                <span
                                    class="px-3 py-1 text-xs rounded-full {{ $rol->color ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $rol->nombre }}
                                </span>
                            @empty
                                <span class="text-gray-400 text-xs">Sin roles asignados</span>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
</div 
@endsection
