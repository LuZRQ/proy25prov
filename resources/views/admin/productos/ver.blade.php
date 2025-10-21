@php
    $backRoute = route('productos.index');
    $title = 'Detalles del Producto';
@endphp
@extends('layouts.crud')

@section('content')
    <div class="bg-gradient-to-b from-amber-50 to-orange-50 min-h-screen p-6 rounded-lg shadow">


        <div class="bg-white p-6 rounded-lg shadow grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-medium text-stone-700 mb-1">Nombre:</label>
                <p>{{ $producto->nombre }}</p>
            </div>
            <div>
                <label class="block font-medium text-stone-700 mb-1">Precio:</label>
                <p>Bs. {{ number_format($producto->precio, 2) }}</p>
            </div>
            <div>
                <label class="block font-medium text-stone-700 mb-1">Categoría:</label>
                <p>{{ $producto->categoria->nombreCategoria ?? 'Sin categoría' }}</p>
            </div>
            <div>
                <label class="block font-medium text-stone-700 mb-1">Stock:</label>
                <p>{{ $producto->stock }}</p>
            </div>
            <div>
                <label class="block font-medium text-stone-700 mb-1">Estado:</label>
                <p>{{ $producto->estado == 1 ? 'Activo' : 'Inactivo' }}</p>
            </div>
            <div class="mt-4">
                <label class="block font-medium text-stone-700 mb-1">Imagen:</label>
                @if ($producto->imagen)
                    <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('images/default.png') }}"
                        alt="{{ $producto->nombre }}" class="w-32 h-32 rounded shadow border border-stone-300">
                @else
                    <p class="text-stone-500 italic">Sin imagen</p>
                @endif
            </div>


            <div class="md:col-span-2">
                <label class="block font-medium text-stone-700 mb-1">Descripción:</label>
                <p>{{ $producto->descripcion ?? 'Sin descripción' }}</p>
            </div>


        </div>

        <div class="mt-6">
            <a href="{{ route(request('redirect', 'productos.index')) }}"
                class="px-6 py-2 bg-stone-300 text-stone-800 font-medium rounded-lg hover:bg-stone-400 shadow">
                Volver
            </a>
        </div>

    </div>
@endsection
