{{-- resources/views/cocina/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-gradient-to-br from-amber-50 via-orange-100 to-amber-200 min-h-screen">

        <div class="flex space-x-3 mb-8 justify-center">
            <a href="{{ route('pedidos.index') }}"
                class="px-4 py-2 rounded-lg {{ request('estado') ? 'bg-gray-100 text-amber-800 hover:bg-amber-200' : 'bg-amber-700 text-white shadow hover:bg-amber-800' }}">
                Todos
            </a>
            <a href="{{ route('pedidos.index', ['estado' => 'pendiente']) }}"
                class="px-4 py-2 rounded-lg {{ request('estado') == 'pendiente' ? 'bg-amber-700 text-white shadow hover:bg-amber-800' : 'bg-gray-100 text-amber-800 hover:bg-amber-200' }}">
                Pendientes
            </a>
            <a href="{{ route('pedidos.index', ['estado' => 'en preparación']) }}"
                class="px-4 py-2 rounded-lg {{ request('estado') == 'en preparación' ? 'bg-amber-700 text-white shadow hover:bg-amber-800' : 'bg-gray-100 text-amber-800 hover:bg-amber-200' }}">
                En preparación
            </a>
            <a href="{{ route('pedidos.index', ['estado' => 'listo']) }}"
                class="px-4 py-2 rounded-lg {{ request('estado') == 'listo' ? 'bg-amber-700 text-white shadow hover:bg-amber-800' : 'bg-gray-100 text-amber-800 hover:bg-amber-200' }}">
                Completado
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse($pedidos as $pedido)
                @php
                    $estado = $pedido->estado;
                    $bgColor = match ($estado) {
                        'pendiente' => 'bg-red-50 border-red-200',
                        'en preparación' => 'bg-blue-50 border-blue-200',
                        'listo' => 'bg-green-50 border-green-200',
                        default => 'bg-gray-100 border-gray-300',
                    };

                    $estadoColor = match ($estado) {
                        'pendiente' => 'text-red-600 bg-yellow-100',
                        'en preparación' => 'text-blue-600 bg-blue-100',
                        'listo' => 'text-green-600 bg-green-100',
                        default => 'text-gray-600 bg-gray-200',
                    };

                    $botonColor = match ($estado) {
                        'pendiente' => 'bg-blue-600 hover:bg-blue-700',
                        'en preparación' => 'bg-green-600 hover:bg-green-700',
                        default => 'bg-gray-400 cursor-not-allowed',
                    };

                    $siguienteEstado =
                        $estado === 'pendiente' ? 'en preparación' : ($estado === 'en preparación' ? 'listo' : null);
                    $accionTexto = $estado === 'pendiente' ? 'Iniciar' : ($estado === 'en preparación' ? 'Listo' : '');
                @endphp

                <div class="border rounded-xl p-4 shadow-sm {{ $bgColor }}">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-bold text-lg text-gray-800">#{{ $pedido->idPedido }}</p>
                            <p class="text-sm text-gray-600">{{ $pedido->direccion ?? 'Mesa ' . ($pedido->mesa ?? 'N/A') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">{{ $pedido->fechaCreacion->format('H:i') }}</p>
                            <p class="text-xs text-red-500 mt-1">
                                @php
                                    $diferenciaMinutos = now()->diffInMinutes($pedido->fechaCreacion);
                                    echo $diferenciaMinutos > 0 ? $diferenciaMinutos . ' min' : 'Recién';
                                @endphp
                            </p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mb-2">👤 Mesero: {{ $pedido->usuario->nombre ?? 'Desconocido' }}</p>

                    <ul class="text-sm text-gray-800 mb-2 space-y-1">
                        @foreach ($pedido->detalles as $detalle)
                            <li>
                                {{ $detalle->cantidad }}x {{ $detalle->producto->nombre }}
                                <span class="float-right">Bs. {{ number_format($detalle->subtotal, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if ($pedido->comentarios)
                        <div
                            class="text-xs text-gray-800 bg-amber-100 rounded-md p-2 mb-3 shadow-inner flex items-center space-x-2">
                            <i class="fas fa-comment-dots text-amber-700"></i>
                            <span class="font-semibold">{{ $pedido->comentarios }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ match ($estado) {
                                'pendiente' => 'bg-red-100 text-red-800',
                                'en preparación' => 'bg-yellow-100 text-yellow-800',
                                'listo' => 'bg-green-100 text-green-800',
                                default => 'bg-gray-200 text-gray-700',
                            } }}">
                            {{ ucfirst($estado) }}
                        </span>

                        <div class="flex space-x-2">

                            @if ($siguienteEstado)
                                <form action="{{ route('pedidos.cambiarEstado', $pedido->idPedido) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="estado" value="{{ $siguienteEstado }}">
                                    <button
                                        class="px-3 py-1 text-xs text-white rounded-lg
                                        {{ match ($estado) {
                                            'pendiente' => 'bg-yellow-400 hover:bg-yellow-500',
                                            'en preparación' => 'bg-green-400 hover:bg-green-500',
                                            default => 'bg-gray-300 cursor-not-allowed',
                                        } }}">
                                        {{ $accionTexto }}
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('pedidos.recibo', $pedido->idPedido) }}" target="_blank"
                                class="px-3 py-1 text-xs rounded-lg bg-amber-700 hover:bg-amber-800 text-white flex items-center space-x-1">
                                <i class="fas fa-print"></i>
                                <span>Imprimir</span>
                            </a>

                            @if ($pedido->estado === 'listo')
                                <form action="{{ route('pedidos.cambiarEstado', $pedido->idPedido) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="estado" value="cancelado">
                                    <button class="px-3 py-1 text-xs rounded-lg bg-red-500 hover:bg-red-600 text-white">
                                        Cancelar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No hay pedidos en curso.</p>
            @endforelse
        </div>


    </div>
@endsection
