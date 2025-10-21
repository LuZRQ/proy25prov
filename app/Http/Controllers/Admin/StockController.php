<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaProducto;
use Illuminate\Http\Request;
use App\Models\Producto;
use Carbon\Carbon;
use App\Traits\Auditable;

class StockController extends Controller
{
    use Auditable;

    public function index(Request $request)
    {
        $this->verificarResetDiario();

        $categorias = CategoriaProducto::with(['productos' => function ($query) {
            $query->activos();
        }])->get();

        $productos = collect();

        foreach ($categorias as $categoria) {
            foreach ($categoria->productos as $producto) {
                if ($request->filled('estado') && $producto->getEstadoStock() !== $request->estado) {
                    continue;
                }

                if ($request->filled('buscar') && !str_contains(strtolower($producto->nombre), strtolower($request->buscar))) {
                    continue;
                }

                if ($request->filled('categoria') && $categoria->nombreCategoria !== $request->categoria) {
                    continue;
                }

                $productos->push($producto);
            }
        }

        return view('admin.stock.index', compact('productos'))
            ->with('title', 'Control de stock');
    }


    public function update(Request $request, Producto $producto)
    {
        $oldStock = $producto->stock;
        $producto->update($request->all());

        if ($oldStock != $producto->stock) {
            $this->logAction(
                "Stock del producto '{$producto->nombre}' actualizado manualmente de {$oldStock} a {$producto->stock}",
                'Stock',
                'Exitoso'
            );
        }

        $redirect = $request->input('redirect', 'productos.index');
        return redirect()->route($redirect)->with('exito', 'Producto actualizado correctamente.');
    }

    // Registrar entrada de stock
    public function entrada(Request $request, $idProducto)
    {
        $producto = Producto::findOrFail($idProducto);

        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto->stock += $request->cantidad;

        if ($producto->stock_inicial < $producto->stock) {
            $producto->stock_inicial = $producto->stock;
        }

        $producto->save();

        $this->logAction(
            "Se registró entrada de {$request->cantidad} unidades en {$producto->nombre}",
            'Stock',
            'Exitoso'
        );

        return redirect()->route('stock.index')->with('exito', 'Stock actualizado con entrada.');
    }

    // Registrar salida de stock
    public function salida(Request $request, $idProducto)
    {
        $producto = Producto::findOrFail($idProducto);

        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        if ($producto->stock < $request->cantidad) {
            return redirect()->route('stock.index')->with('error', 'No hay suficiente stock disponible.');
        }

        $producto->stock -= $request->cantidad;
        $producto->save();

        $this->logAction(
            "Salida de stock: -{$request->cantidad} unidades del producto '{$producto->nombre}'. Stock actual: {$producto->stock}",
            'Stock',
            'Exitoso'
        );

        return redirect()->route('stock.index')->with('exito', 'Stock actualizado con salida.');
    }

    private function verificarResetDiario()
    {
        $hoy = now()->toDateString();

        $productos = Producto::all();
        foreach ($productos as $producto) {
            if ($producto->fecha_actualizacion_stock !== $hoy) {
                $producto->update([
                    'vendidos_dia' => 0,
                    'stock' => $producto->stock_inicial,
                    'fecha_actualizacion_stock' => $hoy,
                ]);
            }
        }
    }
}
