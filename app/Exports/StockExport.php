<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class StockExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Producto::all()->map(function($producto) {
            return [
                'ID Producto'   => $producto->idProducto,
                'Nombre'        => $producto->nombre,
                'Categoría'     => $producto->categoria->nombreCategoria ?? '',
                'Stock Actual'  => $producto->stock,
                'Stock Inicial' => $producto->stock_inicial,
                'Estado' => $producto->getEstadoStockNombre(),

            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Producto',
            'Nombre',
            'Categoría',
            'Stock Actual',
            'Stock Inicial',
            'Estado',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');

        foreach(range('A','F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('A2:F' . (Producto::count() + 1))
              ->getAlignment()->setVertical('center');
    }
}
