<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\CategoriaProducto;
use App\Traits\Auditable;

class ProductoController extends Controller
{
    use Auditable;
    
    public function index(Request $request)
    {
        $categorias = CategoriaProducto::all();

        $productos = Producto::with('categoria')
            ->when($request->search, function ($query) use ($request) {
                $query->where('nombre', 'like', "%{$request->search}%")
                    ->orWhere('descripcion', 'like', "%{$request->search}%");
            })
            ->when($request->categoria, function ($query) use ($request) {
                $query->where('categoriaId', $request->categoria);
            })
            ->when($request->estado !== null && $request->estado !== '', function ($query) use ($request) {
                $query->where('estado', $request->estado);
            })
            ->get();

        return view('admin.productos.index', compact('productos', 'categorias'))
            ->with('title', 'Gestión de Productos');
    }

    public function crear()
    {
        $categorias = CategoriaProducto::all();
        return view('admin.productos.crear', compact('categorias'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'stock' => 'required|integer|min:0',
            'categoriaId' => 'required|exists:CategoriaProducto,idCategoria',
            'estado' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $nombreArchivo = null;
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->storeAs('productos', $nombreArchivo, 'public');
        }

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoriaId' => $request->categoriaId,
            'estado' => $request->estado,
            'imagen' => $nombreArchivo ? 'productos/' . $nombreArchivo : null,
        ]);

        $this->logAction(
            "Se creó el producto '{$producto->nombre}' (ID: {$producto->idProducto})",
            'Productos',
            'Exitoso'
        );

        return redirect()->route('productos.index')->with('exito', 'Producto creado correctamente.');
    }

    public function editar($idProducto)
    {
        $producto = Producto::findOrFail($idProducto);
        $categorias = CategoriaProducto::all();
        return view('admin.productos.editar', compact('producto', 'categorias'));
    }

    public function actualizar(Request $request, $idProducto)
    {
        $producto = Producto::findOrFail($idProducto);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:producto,nombre,' . $idProducto . ',idProducto',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'stock' => 'required|integer|min:0',
            'categoriaId' => 'required|exists:CategoriaProducto,idCategoria',
            'estado' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $nombreArchivo = $producto->imagen;

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->storeAs('productos', $nombreArchivo, 'public');
            $nombreArchivo = 'productos/' . $nombreArchivo;
        }

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoriaId' => $request->categoriaId,
            'estado' => $request->estado,
            'imagen' => $nombreArchivo,
        ]);

        $this->logAction(
            "Se actualizó el producto '{$producto->nombre}' (ID: {$producto->idProducto})",
            'Productos',
            'Exitoso'
        );

        return redirect()->route('productos.index')->with('exito', 'Producto actualizado correctamente.');
    }

    public function eliminar($idProducto)
    {
        $producto = Producto::findOrFail($idProducto);
        $productoNombre = $producto->nombre;
        $productoId = $producto->idProducto;
        $producto->delete();
        $this->logAction(
            "Se eliminó el producto '{$productoNombre}' (ID: {$productoId})",
            'Productos',
            'Exitoso'
        );
        return redirect()->route('productos.index')->with('exito', 'Producto eliminado correctamente.');
    }

    public function ver($idProducto)
    {
        $producto = Producto::with('categoria')->findOrFail($idProducto);
        return view('admin.productos.ver', compact('producto'));
    }
}
