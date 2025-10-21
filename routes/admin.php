<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedido;
use App\Http\Controllers\Admin\{
    PublicController,
    UsuarioController,
    PdfController,
    AuditoriaController,
    RolController,
    ProductoController,
    StockController,
    VentaController,
    PedidoController,
    CajaController,
    ReporteController
};
use App\Http\Controllers\PdfController as ControllersPdfController;

// =============== DUEÑO (todo el sistema) ===============
Route::middleware(['auth', 'verificarRol:Usuarios y Roles'])->group(function () {
    // -------- Usuarios --------
    Route::get('usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/crear', [UsuarioController::class, 'crear'])->name('usuarios.crear');
    Route::post('usuarios', [UsuarioController::class, 'guardar'])->name('usuarios.guardar');
    Route::get('usuarios/{ciUsuario}/editar', [UsuarioController::class, 'editar'])->name('usuarios.editar');
    Route::put('usuarios/{ciUsuario}', [UsuarioController::class, 'actualizar'])->name('usuarios.actualizar');
    Route::delete('usuarios/{ciUsuario}', [UsuarioController::class, 'eliminar'])->name('usuarios.eliminar');
    Route::get('usuarios/{ciUsuario}', [UsuarioController::class, 'mostrar'])->name('usuarios.mostrar');

    // -------- Roles --------
    Route::get('roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('roles/crear', [RolController::class, 'crear'])->name('roles.crear');
    Route::post('roles', [RolController::class, 'guardar'])->name('roles.guardar');
    Route::get('roles/{idRol}/editar', [RolController::class, 'editar'])->name('roles.editar');
    Route::put('roles/{idRol}', [RolController::class, 'actualizar'])->name('roles.actualizar');
    Route::delete('roles/{idRol}', [RolController::class, 'eliminar'])->name('roles.eliminar');
});

// =============== VENTAS (módulo: Gestión de Ventas) ===============
Route::middleware(['auth', 'verificarRol:Gestión de Ventas'])->group(function () {
    Route::prefix('ventas')->name('ventas.')->group(function () {

        // CRUD principal
        Route::get('/', [VentaController::class, 'index'])->name('index');
        Route::get('/crear', [VentaController::class, 'create'])->name('crear');
        Route::post('/', [VentaController::class, 'store'])->name('guardar');

        // Historial de ventas
        Route::get('/historial', [VentaController::class, 'historial'])->name('historial');

        // Mostrar detalle de venta
        Route::get('/detalle/{idVenta}', [VentaController::class, 'show'])->name('show');

        // Editar / Actualizar venta
        Route::get('/editar/{idVenta}', [VentaController::class, 'edit'])->name('edit');
        Route::put('/actualizar/{idVenta}', [VentaController::class, 'update'])->name('update');

        // Eliminar venta
        Route::delete('/eliminar/{idVenta}', [VentaController::class, 'destroy'])->name('destroy');

        // Enviar pedidos a cocina
        Route::post('/enviarACocina', [VentaController::class, 'enviarACocina'])->name('enviarACocina');

        //Caja (parte de ventas, pero usa CajaController)
        Route::get('/caja', [CajaController::class, 'index'])->name('caja');
        Route::post('/abrirCaja', [CajaController::class, 'abrirCaja'])->name('abrirCaja');

        Route::post('/cobrar', [CajaController::class, 'cobrar'])->name('cobrar');
        Route::get('/recibo/{idVenta}', [CajaController::class, 'recibo'])->name('recibo');
        Route::post('/cerrarCaja', [CajaController::class, 'cerrarCaja'])->name('cerrarCaja');

        Route::put('/caja/monto-inicial', [CajaController::class, 'updateMontoInicial'])
            ->name('caja.updateMontoInicial');
        // Exportaciones de Caja en Vivo
        Route::get('/caja/export/excel', [CajaController::class, 'exportCajaExcel'])->name('caja.export.excel');
        Route::get('/caja/export/pdf',   [CajaController::class, 'exportCajaPDF'])->name('caja.export.pdf');

        // Recibo en PDF con PdfController
        Route::get('/recibo/pdf/{idVenta}', [PdfController::class, 'reciboVenta'])->name('recibo.pdf');
    });
});



// =============== COCINA (modulos: Pedidos de Cocina, Gestión de Productos, Control de Stock) ===============
Route::middleware(['auth', 'verificarRol:Pedidos de Cocina,Gestión de Productos,Control de Stock'])->group(function () {
    // Pedidos de cocina
    Route::get('/cocina/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::post('/cocina/pedidos/{pedido}/estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado');
    Route::get('/cocina/pedidos/{pedido}', [PedidoController::class, 'mostrar'])->name('pedidos.mostrar');
    Route::get('/cocina/pedidos/listos', [PedidoController::class, 'listos'])->name('pedidos.listos');

    // -------- Productos --------
    Route::get('productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('productos/crear', [ProductoController::class, 'crear'])->name('productos.crear');
    Route::post('productos', [ProductoController::class, 'guardar'])->name('productos.guardar');
    Route::get('productos/{idProducto}/editar', [ProductoController::class, 'editar'])->name('productos.editar');
    Route::put('productos/{idProducto}', [ProductoController::class, 'actualizar'])->name('productos.actualizar');
    Route::delete('productos/{idProducto}', [ProductoController::class, 'eliminar'])->name('productos.eliminar');
    Route::get('productos/{idProducto}', [ProductoController::class, 'ver'])->name('productos.ver');

    // -------- Stock --------
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('stock/{idProducto}/entrada', [StockController::class, 'entrada'])->name('stock.entrada');
    Route::post('stock/{idProducto}/salida', [StockController::class, 'salida'])->name('stock.salida');

    // Recibo de pedido (tipo ticket para imprimir)
    Route::get('/cocina/pedidos/{pedido}/recibo', [PedidoController::class, 'imprimirRecibo'])
        ->name('pedidos.recibo');
});



// =============== REPORTES (Módulo: Ventas, Stock) ===============

Route::middleware(['auth', 'verificarRol:Gestión de Reportes'])->group(function () {

    // -------- Página principal de reportes --------
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

    // -------- REPORTES RÁPIDOS --------
    // Ventas del día
    Route::get('/reportes/ventas/dia/pdf', [ReporteController::class, 'ventasDiaPDF'])->name('reportes.ventasDiaPDF');
    Route::get('/reportes/ventas/dia/excel', [ReporteController::class, 'ventasDiaExcel'])->name('reportes.ventasDiaExcel');

    // Stock general
    Route::get('/reportes/stock/pdf', [ReporteController::class, 'stockPDF'])->name('reportes.stockPDF');
    Route::get('/reportes/stock/excel', [ReporteController::class, 'stockExcel'])->name('reportes.stockExcel');

    // Ganancia total del mes (solo Excel)
    Route::get('/reportes/ganancia-mes/excel', [ReporteController::class, 'gananciaMesExcel'])
        ->name('reportes.gananciaMesExcel');


    // -------- REPORTES AVANZADOS --------
    // Productos más vendidos del mes
    Route::get('/reportes/productos-mes/pdf', [ReporteController::class, 'productosMesPDF'])->name('reportes.productosMesPDF');
    Route::get('/reportes/productos-mes/excel', [ReporteController::class, 'productosMesExcel'])->name('reportes.productosMesExcel');

    // cierre de caja por mes
    Route::get('/reportes/cierre-caja/pdf/{anio}/{mes}', [ReporteController::class, 'cierreCajaPDF'])->name('reportes.cierreCajaPDF');
    Route::get('/reportes/cierre-caja/pdf/{anio}/{mes}', [ReporteController::class, 'cierreCajaExcel'])->name('reportes.cierreCajaExcel');

    // Insumos / Productos con alta rotación
    Route::get('/reportes/alta-rotacion/pdf', [ReporteController::class, 'altaRotacionPDF'])->name('reportes.altaRotacionPDF');
    Route::get('/reportes/alta-rotacion/excel', [ReporteController::class, 'altaRotacionExcel'])->name('reportes.altaRotacionExcel');

    // Productos con baja venta
    Route::get('/reportes/baja-venta/pdf', [ReporteController::class, 'bajaVentaPDF'])->name('reportes.bajaVentaPDF');
    Route::get('/reportes/baja-venta/excel', [ReporteController::class, 'bajaVentaExcel'])->name('reportes.bajaVentaExcel');

    // -------- AVANZADOS (por tipo, genera al vuelo)
    Route::get('/reportes/avanzado/{tipo}', [ReporteController::class, 'showAvanzadoPDF'])->name('reportes.showAvanzado');
    Route::get('/reportes/descargar/pdf/{tipo}', [ReporteController::class, 'downloadPDF'])->name('reportes.downloadPDFByTipo');
    Route::get('/reportes/descargar/excel/{tipo}', [ReporteController::class, 'downloadExcel'])->name('reportes.downloadExcelByTipo');

    // -------- SHOW y Descarga histórica --------

    // -------- HISTÓRICOS (por ID de la tabla reportes)
    Route::get('/reportes/{reporte}/ver', [ReporteController::class, 'verPDF'])->name('reportes.verPDF');
    Route::get('/reportes/{reporte}/show', [ReporteController::class, 'show'])->name('reportes.show');
    Route::get('/reportes/{reporte}/download/pdf', [ReporteController::class, 'downloadPDFById'])->name('reportes.downloadPDFById');
    Route::get('/reportes/{reporte}/download/excel', [ReporteController::class, 'downloadExcelById'])->name('reportes.downloadExcelById');
});

Route::middleware(['auth', 'verificarRol:Gestión de Auditoría'])->group(function () {
    // Mostrar tabla de auditoría
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');

    // Exportar logs a PDF
    Route::get('/auditoria/pdf', [AuditoriaController::class, 'exportPDF'])->name('auditoria.pdf');

    // Cambiar contraseña
    Route::post('/auditoria/cambiar-contrasena', [AuditoriaController::class, 'cambiarContrasena'])->name('auditoria.cambiarContrasena');
});
