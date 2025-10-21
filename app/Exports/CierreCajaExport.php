<?php

namespace App\Exports;

use App\Models\CierreCaja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CierreCajaExport implements FromArray, WithHeadings, WithStyles
{
    protected $anio;
    protected $mes;
    protected $semanas;
    protected $totalMes;

    public function __construct($anio, $mes, $semanas, $totalMes)
    {
        $this->anio = $anio;
        $this->mes = $mes;
        $this->semanas = $semanas;
        $this->totalMes = $totalMes;
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->semanas as $index => $semana) {
            $data[] = [
                "Semana " . ($index + 1) . " (" . $semana['inicio']->format('d/m') . " - " . $semana['fin']->format('d/m') . ")",
                $semana['efectivo'],
                $semana['tarjeta'],
                $semana['qr'],
                $semana['total'],
            ];
        }

        // Totales del mes
        $data[] = [
            'TOTAL MES',
            $this->totalMes['efectivo'],
            $this->totalMes['tarjeta'],
            $this->totalMes['qr'],
            $this->totalMes['general'],
        ];

        return $data;
    }

    public function headings(): array
    {
        return [
            'Semana',
            'Efectivo (Incl. Fondo Inicial)',
            'Tarjeta',
            'QR',
            'Total Semana',
        ];
    }

    public function styles(Worksheet $sheet)
    {
       
        $sheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');

        $lastRow = count($this->semanas) + 2; 
        $sheet->getStyle("A{$lastRow}:E{$lastRow}")->getFont()->setBold(true);

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}