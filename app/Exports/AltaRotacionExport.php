<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\DetallePedido;
use Carbon\Carbon;

class AltaRotacionExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $productos = Producto::with('detallePedidos.pedido.venta', 'categoria')
            ->get()
            ->map(function ($producto) {
                $cantidadVendida = 0;

                foreach ($producto->detallePedidos as $detalle) {
                    $venta = $detalle->pedido->venta ?? null;

                    if ($venta && $venta->fechaPago->between(now()->startOfMonth(), now()->endOfMonth())) {
                        $cantidadVendida += $detalle->cantidad;
                    }
                }

                return [
                    'ID Producto'      => $producto->idProducto,
                    'Nombre'           => $producto->nombre,
                    'Categoría'        => $producto->categoria->nombreCategoria ?? '',
                    'Cantidad Vendida' => $cantidadVendida,
                ];
            })
            ->sortByDesc('Cantidad Vendida')
            ->take(10);

        return collect($productos);
    }

    public function headings(): array
    {
        return [
            'ID Producto',
            'Nombre',
            'Categoría',
            'Cantidad Vendida',
        ];
    }
}