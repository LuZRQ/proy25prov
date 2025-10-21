<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Pedido;
use App\Models\Reporte;
use App\Models\Producto;
use App\Models\DetallePedido;
use App\Exports\VentasExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Exports\StockExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosMesExport;
use App\Exports\GananciaMesExport;
use App\Exports\AltaRotacionExport;
use App\Exports\BajaVentaExport;
use App\Exports\CierreCajaExport;
use App\Exports\HistoricoCajaMensualExport;
use App\Models\CierreCaja;
use Illuminate\Support\Facades\Auth;
use App\Traits\Auditable;
use Carbon\Carbon;

class ReporteController extends Controller
{
    use Auditable;

    public function index(Request $request)
    {

        $totalVentasDia = Venta::whereDate('fechaPago', now()->toDateString())->sum('montoTotal');

        $pedidosAtendidosDia = Pedido::whereDate('fechaCreacion', now()->toDateString())
            ->where('estado', 'pagado')
            ->count();

        $productoMasVendido = DetallePedido::selectRaw('idProducto, SUM(cantidad) as cantidad')
            ->whereHas('pedido', function ($q) {
                $q->whereDate('fechaCreacion', now()->toDateString())
                    ->where('estado', 'pagado');
            })
            ->groupBy('idProducto')
            ->orderByDesc('cantidad')
            ->with('producto')
            ->first();

        $top5Productos = DetallePedido::selectRaw('idProducto, SUM(cantidad) as cantidad')
            ->whereHas('pedido', function ($q) {
                $q->whereDate('fechaCreacion', now()->toDateString())
                    ->where('estado', 'pagado');
            })
            ->groupBy('idProducto')
            ->orderByDesc('cantidad')
            ->with('producto')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'nombre'   => $item->producto->nombre ?? 'Producto',
                    'cantidad' => $item->cantidad
                ];
            });

        //  Gráfico de ventas últimos 7 días
        $ventasSemana = collect();
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->toDateString();
            $total = Venta::whereDate('fechaPago', $fecha)->sum('montoTotal');
            $ventasSemana->push(['fecha' => $fecha, 'total' => $total]);
        }

        // Stock crítico
        $stockCritico = Producto::where('stock', '<=', 5)->get();

        // Tendencias de ventas
        $ventasActuales = DetallePedido::selectRaw('idProducto, SUM(cantidad) as cantidad')
            ->whereHas('pedido', fn($q) => $q->whereBetween('fechaCreacion', [now()->subDays(6), now()]))
            ->groupBy('idProducto')
            ->with('producto')
            ->get()
            ->keyBy('idProducto');

        $ventasPrevias = DetallePedido::selectRaw('idProducto, SUM(cantidad) as cantidad')
            ->whereHas('pedido', fn($q) => $q->whereBetween('fechaCreacion', [now()->subDays(13), now()->subDays(7)]))
            ->groupBy('idProducto')
            ->get()
            ->keyBy('idProducto');

        $tendencias = [];
        foreach ($ventasActuales as $id => $actual) {
            $previa = $ventasPrevias[$id]->cantidad ?? 0;
            $cambio = $previa > 0 ? (($actual->cantidad - $previa) / $previa) * 100 : 100;
            $cambio = min($cambio, 100);

            if ($cambio >= 20) {
                $tendencias[] = [
                    'producto' => $actual->producto->nombre ?? 'Producto',
                    'tipo' => 'subiendo',
                    'cambio' => round($cambio)
                ];
            } elseif ($cambio <= -20) {
                $tendencias[] = [
                    'producto' => $actual->producto->nombre ?? 'Producto',
                    'tipo' => 'bajando',
                    'cambio' => round(abs($cambio))
                ];
            }
        }

        $tendencias = array_slice($tendencias, 0, 3);

        // Reportes históricos 
        $query = Reporte::query();

        if ($request->filled('categoria')) {
            $query->where('tipo', $request->categoria);
        }
        if ($request->filled('desde')) {
            $query->whereDate('fechaGeneracion', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fechaGeneracion', '<=', $request->hasta);
        }

        $reportes = $query->orderBy('fechaGeneracion', 'desc')
            ->paginate(10)
            ->appends($request->all());

        $ultimoCierre = CierreCaja::latest('fecha_cierre')->first();

        return view('admin.reportes.index', [
            'totalVentasDia'      => $totalVentasDia,
            'pedidosAtendidosDia' => $pedidosAtendidosDia,
            'productoMasVendido'  => $productoMasVendido ? (object)[
                'nombre'   => $productoMasVendido->producto->nombre ?? '-',
                'cantidad' => $productoMasVendido->cantidad
            ] : null,
            'top5Productos'       => $top5Productos,
            'ventasSemana'        => $ventasSemana,
            'stockCritico'        => $stockCritico,
            'tendencias'          => $tendencias,
            'reportes'            => $reportes,
            'ultimoCierre'        => $ultimoCierre,
        ])->with('title', 'Gestión de Reportes');
    }


    public function showAvanzadoPDF($tipo)
    {
        $fecha = now()->toDateString();

        switch ($tipo) {
            case 'productos_mes':
                $productos = Producto::with('categoria', 'detallePedidos.pedido.venta')
                    ->get()
                    ->map(function ($producto) {
                        $cantidadVendida = 0;

                        foreach ($producto->detallePedidos as $detalle) {
                            $venta = $detalle->pedido->venta ?? null;

                            if ($venta && \Carbon\Carbon::parse($venta->fechaPago)->month == now()->month) {
                                $cantidadVendida += $detalle->cantidad;
                            }
                        }

                        $producto->cantidad_vendida = $cantidadVendida;
                        return $producto;
                    });

                $pdf = Pdf::loadView('admin.reportes.pdf.productosMes', compact('productos'));
                break;

            case 'cierre_caja':
                $anio = now()->year;
                $mes  = now()->month;

                $primerDia = Carbon::create($anio, $mes, 1);
                $ultimoDia = $primerDia->copy()->endOfMonth();

                $semanas = [];
                $start = $primerDia->copy();

                while ($start <= $ultimoDia) {
                    $end = $start->copy()->endOfWeek();
                    if ($end > $ultimoDia) $end = $ultimoDia->copy();

                    $cierresSemana = CierreCaja::whereBetween('fecha_cierre', [$start, $end])->get();

                    $efectivo = $cierresSemana->sum('total_efectivo') + $cierresSemana->sum('fondo_inicial');
                    $tarjeta  = $cierresSemana->sum('total_tarjeta');
                    $qr       = $cierresSemana->sum('total_qr');

                    $semanas[] = [
                        'inicio'   => $start->copy(),
                        'fin'      => $end->copy(),
                        'efectivo' => $efectivo,
                        'tarjeta'  => $tarjeta,
                        'qr'       => $qr,
                        'total'    => $efectivo + $tarjeta + $qr,
                    ];

                    $start = $end->addDay();
                }

                $totalMes = [
                    'efectivo' => array_sum(array_column($semanas, 'efectivo')),
                    'tarjeta'  => array_sum(array_column($semanas, 'tarjeta')),
                    'qr'       => array_sum(array_column($semanas, 'qr')),
                    'general'  => array_sum(array_column($semanas, 'total')),
                ];

                $pdf = Pdf::loadView('admin.reportes.pdf.cierreCaja', compact('anio', 'mes', 'semanas', 'totalMes'));
                break;

            case 'alta_rotacion':
                $productos = Producto::with('detallePedidos.pedido.venta')
                    ->get()
                    ->map(function ($producto) {
                        $cantidadVendida = 0;

                        foreach ($producto->detallePedidos as $detalle) {
                            $venta = $detalle->pedido->venta ?? null;

                            if ($venta && \Carbon\Carbon::parse($venta->fechaPago)->month == now()->month) {
                                $cantidadVendida += $detalle->cantidad;
                            }
                        }

                        $producto->cantidad_vendida = $cantidadVendida;
                        return $producto;
                    })
                    ->sortByDesc('cantidad_vendida');

                $pdf = Pdf::loadView('admin.reportes.pdf.altaRotacion', compact('productos'));
                break;

            case 'baja_venta':
                $productos = Producto::with('detallePedidos.pedido.venta')
                    ->get()
                    ->map(function ($producto) {
                        $cantidadVendida = 0;

                        foreach ($producto->detallePedidos as $detalle) {
                            $venta = $detalle->pedido->venta ?? null;

                            if ($venta && \Carbon\Carbon::parse($venta->fechaPago)->month == now()->month) {
                                $cantidadVendida += $detalle->cantidad;
                            }
                        }

                        $producto->cantidad_vendida = $cantidadVendida;
                        return $producto;
                    })
                    ->sortBy('cantidad_vendida');

                $pdf = Pdf::loadView('admin.reportes.pdf.bajaVenta', compact('productos'));
                break;

            default:
                abort(404);
        }

        $filename = $tipo . '_' . $fecha . '.pdf';
        $ruta = 'reportes/' . $filename;

        Storage::disk('public')->put($ruta, $pdf->output());

        $pdfUrl = asset('storage/' . $ruta);

        return view('admin.reportes.showAvanzado', compact('pdfUrl', 'tipo'));
    }



    // ===== SECCIÓN 1: REPORTES RÁPIDOS =====

    // Ventas del día PDF
    public function ventasDiaPDF()
    {
        $fecha = now()->toDateString();
        $ventas = Venta::whereDate('fechaPago', $fecha)->get();

        $totales = [
            'efectivo' => $ventas->where('metodo_pago', 'Efectivo')->sum('montoTotal'),
            'tarjeta'  => $ventas->where('metodo_pago', 'Tarjeta')->sum('montoTotal'),
            'qr'       => $ventas->where('metodo_pago', 'QR')->sum('montoTotal'),
        ];
        $totalGeneral = array_sum($totales);

        $pdf = Pdf::loadView('admin.reportes.pdf.ventasDia', compact('ventas', 'totales', 'totalGeneral', 'fecha'));

        $nombreArchivo = 'ventas_dia_' . $fecha . '.pdf';
        $ruta = 'reportes/' . $nombreArchivo;

        Storage::disk('public')->put($ruta, $pdf->output());

        Reporte::create([
            'tipo' => 'ventas_dia',
            'periodo' => $fecha,
            'generadoPor' => Auth::user()->name ?? 'Sistema',
            'archivo' => $ruta,
        ]);

        $this->logAction("Se generó PDF de ventas del día ({$fecha})", 'Reportes', 'Exitoso');
        return response()->download(storage_path('app/public/' . $ruta));
    }

    public function ventasDiaExcel()
    {
        $fecha = now()->toDateString();
        $ventas = Venta::whereDate('fechaPago', $fecha)
            ->with('pedido.usuario')
            ->get();

        $totales = [
            'efectivo' => $ventas->where('metodo_pago', 'Efectivo')->sum('montoTotal'),
            'tarjeta'  => $ventas->where('metodo_pago', 'Tarjeta')->sum('montoTotal'),
            'qr'       => $ventas->where('metodo_pago', 'QR')->sum('montoTotal'),
        ];
        $totalGeneral = array_sum($totales);

        $this->logAction("Se generó Excel de ventas del día ({$fecha})", 'Reportes', 'Exitoso');

        return Excel::download(new VentasExport($ventas, $totales, $totalGeneral), 'ventas_dia.xlsx');
    }


    // Stock general
    public function stockPDF()
    {
        $productos = Producto::all();
        $fecha = now()->toDateString();

        $pdf = Pdf::loadView('admin.reportes.pdf.stockPDF', compact('productos', 'fecha'));

        $nombreArchivo = 'stock_' . $fecha . '.pdf';
        $ruta = 'reportes/' . $nombreArchivo;

        Storage::disk('public')->put($ruta, $pdf->output());

        Reporte::create([
            'tipo' => 'stock',
            'periodo' => $fecha,
            'generadoPor' => Auth::user()->name ?? 'Sistema',
            'archivo' => $ruta,
        ]);
        $this->logAction(
            "Se generó PDF de stock general ({$fecha})",
            'Reportes',
            'Exitoso'
        );

        return response()->download(storage_path('app/public/' . $ruta));
    }

    public function stockExcel()
    {
        return Excel::download(new StockExport, 'stock.xlsx');
        $this->logAction(
            "Se generó Excel de stock general ({$fecha})",
            'Reportes',
            'Exitoso'
        );
    }


    //reportes avanzados
    public function productosMesPDF()
    {
        $mesActual = now()->month;
        $anioActual = now()->year;

        $productos = Producto::with(['detallePedidos.pedido.venta', 'categoria'])
            ->get()
            ->map(function ($producto) use ($mesActual, $anioActual) {
                $cantidadVendida = 0;

                foreach ($producto->detallePedidos as $detalle) {
                    $pedido = $detalle->pedido;
                    $venta = $pedido->venta ?? null;

                    if ($venta && $venta->fechaPago) {
                        $fecha = \Carbon\Carbon::parse($venta->fechaPago);
                        if ($fecha->month == $mesActual && $fecha->year == $anioActual) {
                            $cantidadVendida += $detalle->cantidad;
                        }
                    }
                }

                $producto->cantidad_vendida = $cantidadVendida;

                return $producto;
            })
            ->filter(fn($p) => $p->cantidad_vendida > 0)
            ->sortByDesc('cantidad_vendida')
            ->values();
        $mes = now()->locale('es')->translatedFormat('F Y');

        $pdf = Pdf::loadView('admin.reportes.pdf.productosMes', [
            'productos' => $productos,
            'mes' => $mes,
        ]);

        $timestamp = now()->format('Ymd_His');
        $nombreArchivo = 'productos_mes_' . $timestamp . '.pdf';
        $ruta = 'reportes/' . $nombreArchivo;

        Storage::disk('public')->makeDirectory('reportes');
        Storage::disk('public')->put($ruta, $pdf->output());

        Reporte::create([
            'tipo' => 'productos_mes',
            'periodo' => $timestamp,
            'generadoPor' => Auth::user()->name ?? 'Sistema',
            'archivo' => $ruta,
        ]);

        $this->logAction(
            "Se generó PDF de productos del mes ({$timestamp})",
            'Reportes',
            'Exitoso'
        );

        return response()->download(storage_path('app/public/' . $ruta));
    }


    public function altaRotacionPDF()
    {
        $productos = Producto::with('detallePedidos.pedido.venta')
            ->get()
            ->map(function ($producto) {
                $cantidadVendida = 0;
                foreach ($producto->detallePedidos as $detalle) {
                    $venta = $detalle->pedido->venta ?? null;
                    if ($venta && $venta->fechaPago->between(now()->startOfMonth(), now()->endOfMonth())) {
                        $cantidadVendida += $detalle->cantidad;
                    }
                }
                $producto->cantidad_vendida = $cantidadVendida;
                return $producto;
            })
            ->sortByDesc('cantidad_vendida')
            ->take(10);

        $pdf = Pdf::loadView('admin.reportes.pdf.altaRotacion', compact('productos'));

        $timestamp = now()->format('Ymd_His');
        $nombreArchivo = 'alta_rotacion_' . $timestamp . '.pdf';
        $ruta = 'reportes/' . $nombreArchivo;

        Storage::disk('public')->put($ruta, $pdf->output());

        Reporte::create([
            'tipo' => 'alta_rotacion',
            'periodo' => $timestamp,
            'generadoPor' => Auth::user()->name ?? 'Sistema',
            'archivo' => $ruta,
        ]);
        $this->logAction(
            "Se generó PDF de productos alta rotación ({$timestamp})",
            'Reportes',
            'Exitoso'
        );

        return response()->download(storage_path('app/public/' . $ruta));
    }

    public function bajaVentaPDF()
    {
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();

        $productos = Producto::with('detallePedidos.pedido.venta', 'categoria')
            ->get()
            ->map(function ($producto) use ($inicioMes, $finMes) {
                $cantidadVendida = 0;

                foreach ($producto->detallePedidos as $detalle) {
                    $venta = $detalle->pedido->venta ?? null;
                    if ($venta && $venta->fechaPago->between($inicioMes, $finMes)) {
                        $cantidadVendida += $detalle->cantidad;
                    }
                }

                $producto->cantidad_vendida = $cantidadVendida;
                return $producto;
            })
            ->sortBy('cantidad_vendida')
            ->take(10);

        $pdf = Pdf::loadView('admin.reportes.pdf.bajaVenta', compact('productos'));

        $timestamp = now()->format('Ymd_His');
        $nombreArchivo = 'baja_venta_' . $timestamp . '.pdf';
        $ruta = 'reportes/' . $nombreArchivo;

        Storage::disk('public')->put($ruta, $pdf->output());

        Reporte::create([
            'tipo' => 'baja_venta',
            'periodo' => $timestamp,
            'generadoPor' => Auth::user()->name ?? 'Sistema',
            'archivo' => $ruta,
        ]);

        $this->logAction(
            "Se generó PDF de productos baja venta ({$timestamp})",
            'Reportes',
            'Exitoso'
        );

        return response()->download(storage_path('app/public/' . $ruta));
    }

    // Productos más vendidos del mes
    public function productosMesExcel()
    {
        return Excel::download(new ProductosMesExport, 'productos_mes.xlsx');
    }

    //Productos con alta rotación

    public function altaRotacionExcel()
    {
        return Excel::download(new AltaRotacionExport, 'alta_rotacion.xlsx');
    }

    // Productos con baja venta

    public function bajaVentaExcel()
    {
        return Excel::download(new BajaVentaExport, 'baja_venta.xlsx');
    }

    // Cierre de caja mensual 
    public function cierreCajaPDF($anio, $mes)
    {
        $primerDia = Carbon::create($anio, $mes, 1);
        $ultimoDia = $primerDia->copy()->endOfMonth();

        $semanas = [];
        $start = $primerDia->copy();
        while ($start <= $ultimoDia) {
            $end = $start->copy()->endOfWeek();
            if ($end > $ultimoDia) $end = $ultimoDia->copy();

            $cierresSemana = CierreCaja::whereBetween('fecha_cierre', [$start, $end])->get();

            $efectivo = $cierresSemana->sum('total_efectivo') + $cierresSemana->sum('fondo_inicial');
            $tarjeta  = $cierresSemana->sum('total_tarjeta');
            $qr       = $cierresSemana->sum('total_qr');

            $semanas[] = [
                'inicio'   => $start->copy(),
                'fin'      => $end->copy(),
                'efectivo' => $efectivo,
                'tarjeta'  => $tarjeta,
                'qr'       => $qr,
                'total'    => $efectivo + $tarjeta + $qr,
            ];

            $start = $end->addDay();
        }

        $totalMes = [
            'efectivo' => array_sum(array_column($semanas, 'efectivo')),
            'tarjeta'  => array_sum(array_column($semanas, 'tarjeta')),
            'qr'       => array_sum(array_column($semanas, 'qr')),
            'general'  => array_sum(array_column($semanas, 'total')),
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.cierreCaja', compact('anio', 'mes', 'semanas', 'totalMes'));

        return $pdf->download("cierre_caja_{$anio}_{$mes}.pdf");
    }

    public function cierreCajaExcel($anio, $mes)
    {
        $primerDia = Carbon::create($anio, $mes, 1);
        $ultimoDia = $primerDia->copy()->endOfMonth();

        $semanas = [];
        $start = $primerDia->copy();

        while ($start <= $ultimoDia) {
            $end = $start->copy()->endOfWeek();
            if ($end > $ultimoDia) $end = $ultimoDia->copy();

            $cierresSemana = CierreCaja::whereBetween('fecha_cierre', [$start, $end])->get();

            $efectivo = $cierresSemana->sum('total_efectivo') + $cierresSemana->sum('fondo_inicial');
            $tarjeta  = $cierresSemana->sum('total_tarjeta');
            $qr       = $cierresSemana->sum('total_qr');

            $semanas[] = [
                'inicio'   => $start->copy(),
                'fin'      => $end->copy(),
                'efectivo' => $efectivo,
                'tarjeta'  => $tarjeta,
                'qr'       => $qr,
                'total'    => $efectivo + $tarjeta + $qr,
            ];

            $start = $end->addDay();
        }

        $totalMes = [
            'efectivo' => array_sum(array_column($semanas, 'efectivo')),
            'tarjeta'  => array_sum(array_column($semanas, 'tarjeta')),
            'qr'       => array_sum(array_column($semanas, 'qr')),
            'general'  => array_sum(array_column($semanas, 'total')),
        ];

        return Excel::download(new CierreCajaExport($anio, $mes, $semanas, $totalMes), "cierre_caja_{$anio}_{$mes}.xlsx");
    }

    // ===== SHOW AVANZADO de reportes =====
    public function downloadPDF($tipo)
    {
        $fecha = now()->toDateString();
        $timestamp = now()->format('His');
        $filename = $tipo . '_' . $fecha . '_' . $timestamp . '.pdf';
        $ruta = 'reportes/' . $filename;

        switch ($tipo) {
            case 'productos_mes':
                $productos = Producto::with('categoria', 'detallePedidos.pedido.venta')
                    ->get()
                    ->map(function ($producto) {
                        $cantidadVendida = 0;
                        foreach ($producto->detallePedidos as $detalle) {
                            $venta = $detalle->pedido->venta ?? null;
                            if ($venta && \Carbon\Carbon::parse($venta->fechaPago)->month == now()->month) {
                                $cantidadVendida += $detalle->cantidad;
                            }
                        }
                        $producto->cantidad_vendida = $cantidadVendida;
                        return $producto;
                    });
                $pdf = Pdf::loadView('admin.reportes.pdf.productosMes', compact('productos'));
                break;

            case 'cierre_caja':
                $anio = now()->year;
                $mes = now()->month;

                $primerDia = \Carbon\Carbon::create($anio, $mes, 1);
                $ultimoDia = $primerDia->copy()->endOfMonth();

                $semanas = [];
                $start = $primerDia->copy();

                while ($start <= $ultimoDia) {
                    $end = $start->copy()->endOfWeek();
                    if ($end > $ultimoDia) $end = $ultimoDia->copy();

                    $cierresSemana = CierreCaja::whereBetween('fecha_cierre', [$start, $end])->get();

                    $efectivo = $cierresSemana->sum('total_efectivo') + $cierresSemana->sum('fondo_inicial');
                    $tarjeta  = $cierresSemana->sum('total_tarjeta');
                    $qr       = $cierresSemana->sum('total_qr');

                    $semanas[] = [
                        'inicio'   => $start->copy(),
                        'fin'      => $end->copy(),
                        'efectivo' => $efectivo,
                        'tarjeta'  => $tarjeta,
                        'qr'       => $qr,
                        'total'    => $efectivo + $tarjeta + $qr,
                    ];

                    $start = $end->addDay();
                }

                $totalMes = [
                    'efectivo' => array_sum(array_column($semanas, 'efectivo')),
                    'tarjeta'  => array_sum(array_column($semanas, 'tarjeta')),
                    'qr'       => array_sum(array_column($semanas, 'qr')),
                    'general'  => array_sum(array_column($semanas, 'total')),
                ];

                $pdf = Pdf::loadView('admin.reportes.pdf.cierreCaja', compact('anio', 'mes', 'semanas', 'totalMes'));
                break;

            case 'alta_rotacion':
                $productos = Producto::with('detallePedidos.pedido.venta')
                    ->get()
                    ->map(function ($producto) {
                        $cantidadVendida = 0;
                        foreach ($producto->detallePedidos as $detalle) {
                            $venta = $detalle->pedido->venta ?? null;
                            if ($venta && \Carbon\Carbon::parse($venta->fechaPago)->month == now()->month) {
                                $cantidadVendida += $detalle->cantidad;
                            }
                        }
                        $producto->cantidad_vendida = $cantidadVendida;
                        return $producto;
                    })
                    ->sortByDesc('cantidad_vendida');
                $pdf = Pdf::loadView('admin.reportes.pdf.altaRotacion', compact('productos'));
                break;

            case 'baja_venta':
                $productos = Producto::with('detallePedidos.pedido.venta')
                    ->get()
                    ->map(function ($producto) {
                        $cantidadVendida = 0;
                        foreach ($producto->detallePedidos as $detalle) {
                            $venta = $detalle->pedido->venta ?? null;
                            if ($venta && \Carbon\Carbon::parse($venta->fechaPago)->month == now()->month) {
                                $cantidadVendida += $detalle->cantidad;
                            }
                        }
                        $producto->cantidad_vendida = $cantidadVendida;
                        return $producto;
                    })
                    ->sortBy('cantidad_vendida');
                $pdf = Pdf::loadView('admin.reportes.pdf.bajaVenta', compact('productos'));
                break;

            default:
                abort(404);
        }

        Storage::disk('public')->makeDirectory('reportes');

        Storage::disk('public')->put($ruta, $pdf->output());

        Reporte::create([
            'tipo' => $tipo,
            'periodo' => $fecha,
            'generadoPor' => Auth::user()->name ?? 'Sistema',
            'archivo' => $ruta,
        ]);

        return response()->download(storage_path('app/public/' . $ruta));
    }

    public function downloadExcel($tipo)
    {
        switch ($tipo) {
            case 'productos_mes':
                $exportClass = \App\Exports\ProductosMesExport::class;
                break;
            case 'alta_rotacion':
                $exportClass = \App\Exports\AltaRotacionExport::class;
                break;

            case 'baja_venta':
                $exportClass = \App\Exports\BajaVentaExport::class;
                break;
            case 'cierre_caja':
                $anio = now()->year;
                $mes = now()->month;

                $primerDia = \Carbon\Carbon::create($anio, $mes, 1);
                $ultimoDia = $primerDia->copy()->endOfMonth();

                $semanas = [];
                $start = $primerDia->copy();

                while ($start <= $ultimoDia) {
                    $end = $start->copy()->endOfWeek();
                    if ($end > $ultimoDia) $end = $ultimoDia->copy();

                    $cierresSemana = CierreCaja::whereBetween('fecha_cierre', [$start, $end])->get();

                    $efectivo = $cierresSemana->sum('total_efectivo') + $cierresSemana->sum('fondo_inicial');
                    $tarjeta  = $cierresSemana->sum('total_tarjeta');
                    $qr       = $cierresSemana->sum('total_qr');

                    $semanas[] = [
                        'inicio'   => $start->copy(),
                        'fin'      => $end->copy(),
                        'efectivo' => $efectivo,
                        'tarjeta'  => $tarjeta,
                        'qr'       => $qr,
                        'total'    => $efectivo + $tarjeta + $qr,
                    ];

                    $start = $end->addDay();
                }

                $totalMes = [
                    'efectivo' => array_sum(array_column($semanas, 'efectivo')),
                    'tarjeta'  => array_sum(array_column($semanas, 'tarjeta')),
                    'qr'       => array_sum(array_column($semanas, 'qr')),
                    'general'  => array_sum(array_column($semanas, 'total')),
                ];

                return Excel::download(new \App\Exports\CierreCajaExport($anio, $mes, $semanas, $totalMes), "cierre_caja_{$anio}_{$mes}.xlsx");
            default:
                abort(404);
        }

        $nombreArchivo = $tipo . '_' . now()->toDateString() . '.xlsx';
        return Excel::download(new $exportClass, $nombreArchivo);
    }
    // ===== SHOW de reportes históricos =====

    public function show(Reporte $reporte)
    {
        return view('admin.reportes.show', compact('reporte'));
    }

    public function verPDF(Reporte $reporte)
    {
        $ruta = storage_path('app/public/' . $reporte->archivo);
        if (!file_exists($ruta)) abort(404);
        return response()->file($ruta);
    }


    // Descargar Excel y PDF histórico 
    public function downloadPDFById(Reporte $reporte)
    {
        $ruta = storage_path('app/public/' . $reporte->archivo);

        if (!file_exists($ruta)) {
            return back()->with('error', 'El archivo no existe.');
        }
        $this->logAction(
            "Se descargó PDF histórico: {$reporte->tipo}",
            'Reportes',
            'Exitoso'
        );
        return response()->download($ruta);
    }

    public function downloadExcelById(Reporte $reporte)
    {
        $ruta = storage_path('app/public/' . $reporte->archivo);

        if (!file_exists($ruta)) {
            return back()->with('error', 'El archivo no existe.');
        }
        $this->logAction(
            "Se descargó Excel histórico: {$reporte->tipo}",
            'Reportes',
            'Exitoso'
        );
        return response()->download($ruta);
    }
}
