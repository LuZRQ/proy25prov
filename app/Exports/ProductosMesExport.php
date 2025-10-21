<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\DetallePedido;

class ProductosMesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $mesActual = now()->month;
        $mesAnterior = now()->subMonth()->month;

        $ventasMesAnterior = DetallePedido::selectRaw('idProducto, SUM(cantidad) as cantidad')
            ->whereHas('pedido', fn($q) => $q->whereMonth('fechaCreacion', $mesAnterior))
            ->groupBy('idProducto')
            ->pluck('cantidad', 'idProducto');
        $productos = Producto::with('detallePedidos.pedido.venta', 'categoria')->get()->map(function ($producto) use ($mesActual, $ventasMesAnterior) {
            $cantidadVendida = 0;
            $ingresos = 0;
            $costoTotal = 0;

            foreach ($producto->detallePedidos as $detalle) {
                $venta = $detalle->pedido->venta ?? null;
                if ($venta && Carbon::parse($venta->fechaPago)->month == $mesActual) {
                    $cantidadVendida += $detalle->cantidad;
                    $ingresos += $detalle->cantidad * $producto->precio;
                    $costoTotal += $detalle->cantidad * $producto->costo; 
                }
            }

            $margen = $ingresos > 0 ? (($ingresos - $costoTotal) / $ingresos) * 100 : 0;

            $anterior = $ventasMesAnterior[$producto->idProducto] ?? 0;
            $variacion = $anterior > 0 ? (($cantidadVendida - $anterior) / $anterior) * 100 : 100;

            return [
                'ID Producto'       => $producto->idProducto,
                'Nombre'            => $producto->nombre,
                'Categoría'         => $producto->categoria->nombreCategoria ?? '',
                'Cantidad Vendida'  => $cantidadVendida,
              
                'Ingresos Generados ($)' => number_format($ingresos, 2),
               
                'Variación vs Mes Anterior (%)' => number_format($variacion, 2),
            ];
        })
            ->filter(fn($p) => $p['Cantidad Vendida'] > 0)
            ->sortByDesc('Cantidad Vendida')
            ->values();

        return collect($productos);
    }

    public function headings(): array
    {
        return [
            'ID Producto',
            'Nombre',
            'Categoría',
            'Cantidad Vendida',
          
            'Ingresos Generados ($)',
           
            'Variación vs Mes Anterior (%)',
        ];
    }
}
