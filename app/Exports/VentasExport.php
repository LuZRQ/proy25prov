<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class VentasExport implements FromCollection, WithHeadings, WithStyles
{
    protected $ventas;
    protected $totales;
    protected $totalGeneral;

    public function __construct($ventas, $totales, $totalGeneral)
    {
        $this->ventas = $ventas;
        $this->totales = $totales;
        $this->totalGeneral = $totalGeneral;
    }

    public function collection()
    {
        $rows = $this->ventas->map(function($venta) {
            return [
                'ID Venta'      => $venta->idVenta,
                'ID Pedido'     => $venta->idPedido,
                'Usuario'       => $venta->pedido->usuario->nombre ?? '',
                'Monto Total'   => $venta->montoTotal,
                'Método Pago'   => ucfirst($venta->metodo_pago ?? ''),
                'Fecha Pago'    => $venta->fechaPago,
            ];
        });

        $rows->push([
            'ID Venta'      => '',
            'ID Pedido'     => '',
            'Usuario'       => 'Totales',
            'Monto Total'   => $this->totalGeneral,
            'Método Pago'   => 'Efectivo: '.$this->totales['efectivo'] . ' | Tarjeta: '.$this->totales['tarjeta'] . ' | QR: '.$this->totales['qr'],
            'Fecha Pago'    => '',
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'ID Venta',
            'ID Pedido',
            'Usuario',
            'Monto Total',
            'Método Pago',
            'Fecha Pago',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');

        foreach(range('A','F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('A2:F' . ($this->ventas->count() + 2))
              ->getAlignment()->setVertical('center');
    }
}