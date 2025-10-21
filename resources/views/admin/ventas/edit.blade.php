{{-- resources/views/admin/ventas/edit.blade.php --}}
@php
    $backRoute = route('ventas.historial'); 
    $title = 'Editar ventas'; 
@endphp
@extends('layouts.crud')

@section('content')
<div class="p-6 bg-gradient-to-br from-amber-50 via-white to-amber-100 min-h-screen">

    <div class="bg-white rounded-2xl shadow-md p-6 border border-amber-200">
        <form action="{{ route('ventas.update', $venta->idVenta) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-amber-700">Monto Total (Bs.)</label>
                <input type="number" step="0.01" name="montoTotal" value="{{ old('montoTotal', $venta->montoTotal) }}"
                       class="w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-amber-400 focus:border-amber-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-amber-700">Método de Pago</label>
                <select name="metodo_pago"
                        class="w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-amber-400 focus:border-amber-400">
                    <option value="Efectivo" {{ $venta->metodo_pago == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="Tarjeta" {{ $venta->metodo_pago == 'Tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    <option value="QR" {{ $venta->metodo_pago == 'QR' ? 'selected' : '' }}>QR</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-amber-700">Fecha de Pago</label>
                <input type="datetime-local" name="fechaPago"
                       value="{{ old('fechaPago', $venta->fechaPago ? $venta->fechaPago->format('Y-m-d\TH:i') : '') }}"
                       class="w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-amber-400 focus:border-amber-400">
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('ventas.historial') }}"
                   class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg shadow">Cancelar</a>
                <button type="submit"
                        class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg shadow">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
