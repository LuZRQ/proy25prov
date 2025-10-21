{{-- resources/views/admin/ventas/historial.blade.php --}}
@php
    $backRoute = route('ventas.index'); 
    $title = 'Historial de ventas'; 
@endphp
@extends('layouts.crud')

@section('content')
<div class="p-6 bg-gradient-to-br from-amber-50 via-white to-amber-100 min-h-screen">
   
    <div class="bg-white rounded-2xl shadow-md p-4 mb-6 border border-amber-200">
        <form method="GET" action="{{ route('ventas.historial') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-sm font-semibold text-amber-700">Fecha desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                       class="w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-amber-400 focus:border-amber-400">
            </div>
            <div>
                <label class="text-sm font-semibold text-amber-700">Fecha hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                       class="w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-amber-400 focus:border-amber-400">
            </div>
            <div>
                <label class="text-sm font-semibold text-amber-700">Mesa</label>
                <select name="mesa" 
                    class="w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-amber-400 focus:border-amber-400">
                    <option value="">-- Todas --</option>
                    @foreach ($mesas as $mesa)
                        <option value="{{ $mesa->mesa }}" {{ request('mesa') == $mesa->mesa ? 'selected' : '' }}>
                            Mesa {{ $mesa->mesa }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit"
                        class="w-full bg-amber-600 hover:bg-amber-700 text-white py-2 rounded-lg shadow-md transition">
                    Filtrar
                </button>
            </div>
        </form>
    </div>
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-amber-200">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-amber-600 text-white">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Pedido / Mesa</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Método Pago</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($ventas as $venta)
                    <tr class="hover:bg-amber-50 transition">
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $venta->idVenta }}</td>
                        <td class="px-4 py-3">{{ $venta->pedido->idPedido }} - {{ $venta->pedido->mesa }}</td>
                        <td class="px-4 py-3">{{ $venta->fechaPago }}</td>
                        <td class="px-4 py-3 font-bold text-amber-700">
                            Bs {{ number_format($venta->montoTotal, 2) }}
                        </td>
                        <td class="px-4 py-3">{{ $venta->metodo_pago }}</td>
                        <td class="px-4 py-3 text-center space-x-2">

                            <a href="{{ route('ventas.show', $venta->idVenta) }}"
                               class="inline-flex items-center px-2 py-1 text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('ventas.edit', $venta->idVenta) }}"
                               class="inline-flex items-center px-2 py-1 text-sm text-amber-600 hover:text-amber-800">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('ventas.destroy', $venta->idVenta) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Seguro deseas eliminar esta venta?')"
                                        class="inline-flex items-center px-2 py-1 text-sm text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-gray-500 text-center">No hay ventas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t bg-gray-50">
            {{ $ventas->links() }}
        </div>
    </div>
</div>
@endsection
