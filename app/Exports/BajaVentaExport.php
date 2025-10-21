<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\DetallePedido;
use Carbon\Carbon;

class BajaVentaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $productos = Producto::with('detallePedidos.pedido.venta', 'categoria')
            ->get()
            ->map(function ($producto) {
                $cantidadVendida = 0;

                foreach ($producto->detallePedidos as $detalle) {
                    $venta = $detalle->pedido->venta ?? null;

                    if ($venta && Carbon::parse($venta->fechaPago)->between(now()->startOfMonth(), now()->endOfMonth())) {
                        $cantidadVendida += $detalle->cantidad;
                    }
                }

                $producto->cantidad_vendida = $cantidadVendida;

                return [
                    'ID Producto'      => $producto->idProducto,
                    'Nombre'           => $producto->nombre,
                    'Categoría'        => $producto->categoria->nombreCategoria ?? '',
                    'Cantidad Vendida' => $cantidadVendida,
                ];
            })
            ->sortBy('Cantidad Vendida')
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
