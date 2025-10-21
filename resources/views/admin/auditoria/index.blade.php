{{-- resources/views/auditoria/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 min-h-screen">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            <div class="bg-white border border-gray-200 rounded-xl shadow p-6">
                <h2 class="font-semibold text-lg text-gray-800 mb-4">Gestión de Contraseñas</h2>

                @if (session('success'))
                    <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('auditoria.cambiarContrasena') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Contraseña Actual</label>
                        <input type="password" name="contrasena_actual"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('contrasena_actual')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                        <input type="password" name="nueva_contrasena"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('nueva_contrasena')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                        <input type="password" name="nueva_contrasena_confirmation"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                        Actualizar Contraseña
                    </button>
                </form>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl shadow p-6">
                <h2 class="font-semibold text-lg text-gray-800 mb-4">Requisitos de Contraseña</h2>
                <ul class="list-disc pl-5 space-y-2 text-gray-700">
                    <li>Mínimo 8 caracteres</li>
                    <li>Al menos una letra mayúscula</li>
                    <li>Al menos un número</li>
                    <li>Al menos un símbolo</li>
                </ul>
            </div>
        </div>

        @if (auth()->user()->esDueno())
            <div class="bg-white border border-gray-200 rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-lg text-gray-800">Registro de Actividad</h2>
                    <a href="{{ route('auditoria.pdf') }}"
                        class="flex items-center space-x-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Exportar</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">Usuario</th>
                                <th class="px-4 py-2 border">Último Acceso</th>
                                <th class="px-4 py-2 border">Módulo</th>
                                <th class="px-4 py-2 border">IP</th>
                                <th class="px-4 py-2 border">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr class="text-gray-800">
                                    <td class="px-4 py-2 border">
                                        <div>
                                            <p class="font-medium">{{ optional($log->causer)->nombre }}
                                                {{ optional($log->causer)->apellido }}</p>
                                            <p class="text-sm text-gray-500">{{ optional($log->causer)->correo }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 border">{{ $log->created_at }}</td>
                                    <td class="px-4 py-2 border">{{ $log->properties['modulo'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 border">{{ $log->properties['ip_origen'] ?? 'N/A' }}</td>
                                    <td
                                        class="px-4 py-2 border {{ ($log->properties['estado'] ?? '') === 'Exitoso' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $log->properties['estado'] ?? 'Desconocido' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 border text-center text-gray-500">No hay registros
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
