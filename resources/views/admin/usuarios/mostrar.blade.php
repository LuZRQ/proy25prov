@php
    $backRoute = route('usuarios.index'); 
    $title = 'Datos de usuario'; 
@endphp
@extends('layouts.crud')

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="font-bold">CI:</p>
                <p>{{ $usuario->ciUsuario }}</p>
            </div>
            <div>
                <p class="font-bold">Usuario:</p>
                <p>{{ $usuario->usuario }}</p>
            </div>
            <div>
                <p class="font-bold">Nombre:</p>
                <p>{{ $usuario->nombre }}</p>
            </div>
            <div>
                <p class="font-bold">Apellido:</p>
                <p>{{ $usuario->apellido }}</p>
            </div>
            <div>
                <p class="font-bold">Correo:</p>
                <p>{{ $usuario->correo }}</p>
            </div>
            <div>
                <p class="font-bold">Teléfono:</p>
                <p>{{ $usuario->telefono ?? '-' }}</p>
            </div>
            <div>
                <p class="font-bold">Rol:</p>
                <p>{{ $usuario->rol->nombre ?? 'Sin rol' }}</p>
            </div>
            <div>
                <p class="font-bold">Estado:</p>
                @if ($usuario->estado)
                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Activo</span>
                @else
                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Inactivo</span>
                @endif
            </div>

            <div class="md:col-span-2">
                <p class="font-bold">Último acceso:</p>
                <p>{{ $usuario->ultimo_acceso ?? 'Nunca' }}</p>
            </div>
        </div>
    </div>
@endsection
