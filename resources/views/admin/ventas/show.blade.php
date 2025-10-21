@php
    $backRoute = route('ventas.historial');
    $title = 'Visualizar venta';
@endphp
@extends('layouts.crud')

@section('content')
    <div class="p-6 bg-gradient-to-br from-amber-50 via-white to-amber-100 min-h-screen">

        <div class="bg-white shadow-lg rounded-2xl p-6 border border-amber-200">
            <p><strong>Pedido:</strong> {{ $venta->pedido->idPedido }} - Mesa {{ $venta->pedido->mesa }}</p>
            <p><strong>Fecha de pago:</strong> {{ $venta->fechaPago }}</p>
            <p><strong>Monto total:</strong> Bs {{ number_format($venta->montoTotal, 2) }}</p>
            <p><strong>Método de pago:</strong> {{ $venta->metodo_pago }}</p>

            <hr class="my-4">

            <h3 class="text-lg font-semibold text-amber-700 mb-3">Productos</h3>
            <table class="min-w-full text-sm text-left text-gray-700 border">
                <thead class="bg-amber-600 text-white">
                    <tr>
                        <th class="px-4 py-2">Producto</th>
                        <th class="px-4 py-2">Cantidad</th>
                        <th class="px-4 py-2">Precio</th>
                        <th class="px-4 py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venta->pedido->detalles as $detalle)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $detalle->producto->nombre }}</td>
                            <td class="px-4 py-2">{{ $detalle->cantidad }}</td>
                            <td class="px-4 py-2">Bs {{ number_format($detalle->precio, 2) }}</td>
                            <td class="px-4 py-2">Bs {{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('ventas.historial') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    ⬅ Volver
                </a>
                <a href="{{ route('ventas.edit', $venta->idVenta) }}"
                    class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                    ✏ Editar
                </a>
            </div>
        </div>
    </div>
@endsection
