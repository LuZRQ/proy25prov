{{-- resources/views/caja.blade.php --}}
@php
    $backRoute = route('ventas.index');
@endphp
@extends('layouts.crud')

@section('content')
    <div class="p-4 md:p-6 bg-gradient-to-br from-amber-50 via-orange-100 to-amber-200 min-h-screen">
        @if ($mostrarAlerta)
            <div
                class="flex items-center bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md mb-4 shadow-md">
                <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
                <div>
                    <strong>⚠️ Atención:</strong> Parece que hay días sin cierre registrados.
                    <br>
                    Te recomendamos revisar y realizar el cierre de caja para mantener los reportes correctos.
                </div>
            </div>
        @endif
        {{-- Panel Izquierdo: resumen de ventas --}}
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 md:col-span-5 lg:col-span-4 p-2 md:p-4">
                <div
                    class="bg-gradient-to-b from-amber-50 via-amber-100 to-emerald-50 rounded-2xl shadow-lg p-6 border border-amber-200">
                    @if ($cajaActual)
                        <div class="mb-3 p-2 text-center bg-green-100 text-green-800 rounded-lg font-semibold shadow">
                            Caja en curso — abierta
                        </div>
                    @else
                        <div class="mb-3 p-2 text-center bg-red-100 text-red-800 rounded-lg font-semibold shadow">
                            No hay caja activa
                        </div>
                    @endif

                    <h2 class="font-bold text-xl text-amber-900 mb-5 flex items-center gap-2">
                        <i class="fas fa-cash-register"></i> Control de Caja
                    </h2>

                    @if (!$cajaActual)
                        <button id="btnAbrirCaja"
                            class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600 font-semibold mb-4 shadow-md">
                            <i class="fas fa-play mr-1"></i> Abrir Caja
                        </button>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">

                        <div class="bg-amber-100 rounded-lg p-3 shadow flex flex-wrap items-start gap-3">
                            <i class="fas fa-wallet text-amber-700 text-xl shrink-0 mt-1"></i>

                            <div class="flex-grow min-w-[50%]">
                                <p class="text-sm text-amber-800">Fondo Inicial</p>
                                <p class="font-semibold text-lg text-amber-900">
                                    Bs. {{ number_format($cajaActual->fondo_inicial ?? 0, 2) }}
                                </p>

                                @if ($cajaActual && $cajaActual->observaciones)
                                    <p class="text-xs text-gray-600 mt-1">
                                        Obs: {{ $cajaActual->observaciones }}
                                    </p>
                                @endif
                            </div>

                            @php $user = Auth::user(); @endphp
                            @if ($user && $user->rol?->nombre === 'Dueno' && $cajaActual)
                                <div class="w-full sm:w-auto">
                                    <button id="btnOpenEditarMonto"
                                        class="inline-flex items-center gap-1 px-3 py-1 text-sm bg-amber-300 text-amber-900 rounded-md hover:bg-amber-400 transition w-full sm:w-auto mt-2 sm:mt-0">
                                        <i class="fas fa-pen"></i> Editar
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="bg-green-100 rounded-lg p-3 shadow flex items-center gap-3">
                            <i class="fas fa-coins text-green-700 text-xl shrink-0"></i>
                            <div>
                                <p class="text-sm text-green-800">Total en Caja</p>
                                <p class="font-semibold text-lg text-green-900">
                                    Bs. {{ number_format($totalEnCaja, 2, '.', ',') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 shadow mb-5">
                        <h3 class="font-semibold text-md sm:text-lg text-amber-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-chart-simple"></i> Resumen de Ventas
                        </h3>
                        <ul class="space-y-2">
                            <li class="flex justify-between items-center">
                                <span class="flex items-center gap-1 text-amber-800">
                                    <i class="fas fa-money-bill-wave"></i> Efectivo
                                </span>
                                <span class="font-semibold text-amber-900">
                                    Bs. {{ number_format($totalEfectivoCobrado, 2, '.', ',') }}
                                </span>
                            </li>

                            <li class="flex justify-between items-center">
                                <span class="flex items-center gap-1 text-amber-800">
                                    <i class="fas fa-credit-card"></i> Tarjeta (ya con 1.8% desc.)
                                </span>
                                <span class="font-semibold text-amber-900">
                                    Bs. {{ number_format($totalTarjeta, 2) }}
                                </span>
                            </li>

                            <li class="flex justify-between items-center">
                                <span class="flex items-center gap-1 text-amber-800"><i class="fas fa-qrcode"></i> QR</span>
                                <span class="font-semibold text-amber-900">Bs. {{ number_format($totalQR, 2) }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="flex flex-col gap-2">
                        @if ($cajaActual)
                            <form action="{{ route('ventas.cerrarCaja') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-yellow-200 text-yellow-900 py-2 rounded-md hover:bg-yellow-300 shadow-sm font-semibold">
                                    <i class="fas fa-lock mr-1"></i> Cerrar Caja
                                </button>
                            </form>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('ventas.caja.export.excel') }}"
                                class="flex-1 bg-yellow-100 text-yellow-900 py-2 rounded-md hover:bg-yellow-200 shadow-sm text-center font-semibold">
                                <i class="fas fa-file-excel mr-1"></i> Excel
                            </a>
                            <a href="{{ route('ventas.caja.export.pdf') }}"
                                class="flex-1 bg-yellow-100 text-yellow-900 py-2 rounded-md hover:bg-yellow-200 shadow-sm text-center font-semibold">
                                <i class="fas fa-file-pdf mr-1"></i> PDF
                            </a>
                        </div>

                        <a href="{{ route('ventas.index') }}"
                            class="w-full bg-gray-300 text-gray-800 py-2 rounded-md hover:bg-gray-400 shadow-sm text-center font-semibold mt-2">
                            <i class="fas fa-arrow-left mr-1"></i> Volver
                        </a>
                    </div>
                </div>
            </div>

            <div id="modalAbrirCaja" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-40 z-50">
                <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm relative">
                    <button type="button" onclick="cerrarModalAbrirCaja()"
                        class="absolute top-3 right-3 text-gray-500 hover:text-red-600">✖</button>

                    <h3 class="text-lg font-semibold text-amber-900 mb-4">Abrir Caja</h3>

                    <form method="POST" action="{{ route('ventas.abrirCaja') }}">
                        @csrf

                        <label class="block text-sm text-gray-700 mb-1">Fondo inicial (Bs)</label>
                        <input type="number" name="fondo_inicial" step="0.01" min="0" required
                            class="w-full border border-amber-300 rounded-md p-2 mb-4 focus:ring-amber-400 focus:border-amber-400">

                        <label class="block text-sm text-gray-700 mb-1">Observaciones</label>
                        <textarea name="observaciones" rows="2"
                            class="w-full border border-amber-300 rounded-md p-2 mb-4 focus:ring-amber-400 focus:border-amber-400"></textarea>

                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="cerrarModalAbrirCaja()"
                                class="px-3 py-1 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit"
                                class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600">Abrir</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modalEditarMonto" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-40 z-50">
                <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm relative">
                    <button type="button" onclick="cerrarModalEditarMonto()"
                        class="absolute top-3 right-3 text-gray-500 hover:text-red-600">✖</button>

                    <h3 class="text-lg font-semibold text-amber-900 mb-4">Editar monto inicial</h3>

                    <form id="formEditarMonto" method="POST"
                        action="{{ route('ventas.caja.updateMontoInicial', ['id' => $ultimoCierre->id ?? 1]) }}">
                        @csrf
                        @method('PUT')

                        <label class="block text-sm text-gray-700 mb-1">Nuevo monto (Bs)</label>
                        <input id="inputMontoInicial" type="number" name="monto_inicial" step="0.01" min="0"
                            required value="{{ old('monto_inicial', $fondoInicial) }}"
                            class="w-full border border-amber-300 rounded-md p-2 mb-4 focus:ring-amber-400 focus:border-amber-400">

                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="cerrarModalEditarMonto()"
                                class="px-3 py-1 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit"
                                class="px-3 py-1 bg-amber-400 text-white rounded-md hover:bg-amber-500">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Panel Derecho: Cobrar orden --}}
            <div class="col-span-12 md:col-span-7 lg:col-span-8 p-2 md:p-4">
                <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border border-amber-200">

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                        <h2 class="font-semibold text-lg text-amber-900">Cobrar orden</h2>
                        <select id="pedidoSeleccionado" data-pedidos='@json($pedidosJS)'
                            class="w-full md:w-1/2 border rounded-lg px-2 py-2 text-amber-800">
                            <option disabled selected>Selecciona un pedido</option>
                            @foreach ($pedidos as $pedido)
                                <option value="{{ $pedido->idPedido }}">
                                    Mesa: {{ $pedido->mesa }} - Pedido #{{ $pedido->idPedido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-4 mb-6 shadow-inner">
                        <p class="text-sm text-amber-700">Total a pagar</p>
                        <p class="font-bold text-2xl text-amber-900" id="totalPagar">Bs. 0.00</p>
                    </div>

                    <div class="bg-white rounded-xl p-4 mb-4 shadow">
                        <div class="mb-4">
                            <label class="block text-sm text-amber-700">Tipo de pago</label>
                            <select class="w-full border rounded-lg px-2 py-2 mt-1 text-amber-800" id="tipoPago"
                                name="tipo_pago">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="QR">QR</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-amber-700">Pago del cliente</label>
                            <input type="number" class="w-full border rounded-lg px-2 py-2 mt-1 text-amber-900"
                                id="pagoCliente" name="pago_cliente" placeholder="0.00">
                        </div>

                        <div class="bg-gray-100 rounded-lg p-4 mb-0">
                            <p class="text-sm text-gray-600">Cambio</p>
                            <p class="font-semibold text-gray-800" id="cambio">Bs. 0.00</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto mb-6">
                        <table id="tablaOrden" class="w-full border-collapse rounded-lg overflow-hidden">
                            <thead class="bg-amber-700 text-white">
                                <tr>
                                    <th class="px-4 py-2 text-left">Cantidad</th>
                                    <th class="px-4 py-2 text-left">Platillo</th>
                                    <th class="px-4 py-2 text-left">Comentarios</th>
                                    <th class="px-4 py-2 text-left">Precio</th>
                                    <th class="px-4 py-2 text-left">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-amber-200"></tbody>
                        </table>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between mt-4 gap-2">
                        <a href="{{ route('ventas.caja') }}"
                            class="px-6 py-2 rounded-lg bg-yellow-100 text-amber-900 hover:bg-yellow-200 shadow text-center">
                            ✖ Cerrar
                        </a>

                        <form id="formCobrar" action="{{ route('ventas.cobrar') }}" method="POST"
                            class="flex-1 md:flex-none">
                            @csrf
                            <input type="hidden" name="idPedido" id="pedidoIdSeleccionado">
                            <input type="hidden" name="montoTotal" id="montoTotalInput">
                            <input type="hidden" name="tipo_pago" id="tipoPagoInput">
                            <input type="hidden" name="pago_cliente" id="pagoClienteInput">

                            <button type="submit"
                                class="w-full px-6 py-2 rounded-lg bg-yellow-100 text-amber-900 hover:bg-yellow-200 shadow font-semibold">
                                ✔ Terminar orden
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modalAbrir = document.getElementById('modalAbrirCaja');
                const btnAbrir = document.getElementById('btnAbrirCaja');
                if (btnAbrir && modalAbrir) {
                    btnAbrir.addEventListener('click', () => {
                        modalAbrir.classList.remove('hidden');
                        modalAbrir.classList.add('flex');
                    });
                }
                window.cerrarModalAbrirCaja = () => {
                    modalAbrir.classList.add('hidden');
                    modalAbrir.classList.remove('flex');
                };

                const modalEditar = document.getElementById('modalEditarMonto');
                const btnEditar = document.getElementById('btnOpenEditarMonto');
                const inputEditar = document.getElementById('inputMontoInicial');
                if (btnEditar && modalEditar) {
                    btnEditar.addEventListener('click', () => {
                        inputEditar.value = "{{ $fondoInicial }}";
                        modalEditar.classList.remove('hidden');
                        modalEditar.classList.add('flex');
                    });
                }
                window.cerrarModalEditarMonto = () => {
                    modalEditar.classList.add('hidden');
                    modalEditar.classList.remove('flex');
                };

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        cerrarModalAbrirCaja();
                        cerrarModalEditarMonto();
                    }
                });
            });
        </script>
    @endpush
@endsection
