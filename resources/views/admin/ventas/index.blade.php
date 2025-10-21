{{-- resources/views/ventas/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-6 bg-gradient-to-br from-amber-100 via-orange-100 to-amber-200 min-h-screen">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8">
                <div class="flex flex-wrap gap-2 mb-6">
                    <button class="btn-categoria px-4 py-2 rounded-lg bg-amber-700 text-white shadow hover:bg-amber-800"
                        data-categoria="all">Todo</button>
                    @foreach ($categorias as $categoria)
                        <button class="btn-categoria px-4 py-2 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200"
                            data-categoria="{{ $categoria->idCategoria }}">
                            {{ $categoria->nombreCategoria }}
                        </button>
                    @endforeach
                </div>

                <div id="catalogo" class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                    @foreach ($productos as $producto)
                        <div class="producto-card bg-white rounded-2xl shadow-lg overflow-hidden border border-amber-200"
                            data-categoria="{{ $producto->categoriaId }}">
                            <div
                                class="h-24 sm:h-32 bg-gradient-to-tr from-amber-200 to-amber-400 flex items-center justify-center overflow-hidden">
                                <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('images/default.png') }}"
                                    alt="{{ $producto->nombre }}" class="h-full w-full object-cover">
                            </div>

                            <div class="p-4">
                                <h3 class="font-semibold text-base sm:text-lg text-amber-900">{{ $producto->nombre }}</h3>
                                <p class="text-sm text-amber-700">Bs. {{ number_format($producto->precio, 2) }}</p>
                                <button
                                    class="btn-agregar w-full mt-3 flex justify-center items-center gap-2 bg-amber-700 text-white py-2 rounded-lg hover:bg-amber-800"
                                    data-id="{{ $producto->idProducto }}" data-nombre="{{ $producto->nombre }}"
                                    data-precio="{{ $producto->precio }}">
                                    Agregar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-amber-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                        <h2 class="font-bold text-lg text-amber-900">Pedido Actual</h2>

                        <select id="select-mesa" class="border rounded-lg px-2 py-1 text-sm text-amber-800">
                            @for ($i = 1; $i <= 10; $i++)
                                <option>Mesa: {{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div id="pedido-items"></div>

                    <div class="flex justify-between border-t pt-2 mb-4">
                        <span class="font-semibold text-amber-900">Total</span>
                        <span id="pedido-total" class="font-bold text-amber-800">Bs. 0.00</span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-amber-700">Comentarios</label>
                        <textarea id="comentario-text" class="w-full border rounded-lg p-2 mt-1 text-sm"
                            placeholder="Ej: sin picante, poca sal..."></textarea>
                    </div>

                    <div class="space-y-3">
                        <button type="button" id="btn-enviar-pedido"
                            class="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                            Enviar a Cocina
                        </button>
                        <button type="button" id="btn-cancelar-pedido"
                            class="w-full flex items-center justify-center gap-2 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                            Cancelar Pedido
                        </button>
                    </div>

                    <form id="form-enviar" action="{{ route('ventas.enviarACocina') }}" method="POST" class="hidden">
                        @csrf
                        <input type="hidden" name="mesa" id="mesa">
                        <input type="hidden" name="comentarios" id="comentarios-hidden">
                        <input type="hidden" name="productos" id="productos">
                    </form>
                </div>

                <div class="mt-6 space-y-3">

                    <a href="{{ route('ventas.historial') }}"
                        class="block w-full text-center bg-amber-500 text-white py-2 rounded-lg hover:bg-amber-600 shadow">
                        Ver historial
                    </a>
                    @php
                        $rol = Auth::user()->rol?->nombre;
                    @endphp

                    @if ($rol === 'Cajero' || $rol === 'Dueno')
                        <a href="{{ route('ventas.caja') }}"
                            class="block w-full text-center bg-amber-800 text-white py-2 rounded-lg hover:bg-amber-900 shadow">
                            Cobrar del pedido
                        </a>
                    @endif


                </div>
            </div>

        </div>

        <div class="mt-10 bg-white shadow rounded-2xl p-4 sm:p-6 border border-amber-200">
            <h2 class="font-bold text-lg text-amber-900 mb-4">📋 Pedidos Listos en Cocina</h2>
            <div id="pedidos-listos" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($pedidos as $pedido)
                    <div class="border rounded-lg p-4 shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-semibold text-amber-800">Mesa: {{ $pedido->mesa }}</span>
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Listo</span>
                        </div>
                        <ul class="text-sm text-amber-700 mb-3">
                            @foreach ($pedido->detalles as $detalle)
                                <li>- {{ $detalle->cantidad }} x {{ $detalle->producto->nombre }}</li>
                            @endforeach
                        </ul>

                    </div>
                @empty
                    <p class="text-gray-500">No hay pedidos listos todavía.</p>
                @endforelse
            </div>
        </div>

    </div>
@endsection
