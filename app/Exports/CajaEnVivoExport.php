<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CajaEnVivoExport  implements FromView
{
    protected $caja;
    protected $totalEfectivo;
    protected $totalTarjeta;
    protected $totalQR;
    protected $totalEnCaja;

    public function __construct($caja, $totalEfectivo, $totalTarjeta, $totalQR, $totalEnCaja)
    {
        $this->caja = $caja;
        $this->totalEfectivo = $totalEfectivo;
        $this->totalTarjeta = $totalTarjeta;
        $this->totalQR = $totalQR;
        $this->totalEnCaja = $totalEnCaja;
    }

    public function view(): View
    {
        $ventas = Venta::whereDate('fechaPago', now()->toDateString())
            ->with('pedido.usuario')
            ->get();

        return view('admin.ventas.cajaEnVivoExcel', [
            'caja'          => $this->caja,
            'ventas'        => $ventas,
            'totalEfectivo' => $this->totalEfectivo,
            'totalTarjeta'  => $this->totalTarjeta,
            'totalQR'       => $this->totalQR,
            'totalEnCaja'   => $this->totalEnCaja,
        ]);
    }
}