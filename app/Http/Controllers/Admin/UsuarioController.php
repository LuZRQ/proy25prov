<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\Auditable;

class UsuarioController extends Controller
{
    use Auditable;

    public function index(Request $request)
    {
        $usuarios = Usuario::with('rol')
            ->when($request->search, function ($query) use ($request) {
                $query->where('nombre', 'like', "%{$request->search}%")
                    ->orWhere('apellido', 'like', "%{$request->search}%")
                    ->orWhere('usuario', 'like', "%{$request->search}%")
                    ->orWhere('correo', 'like', "%{$request->search}%");
            })
            ->when($request->filled('estado'), function ($query) use ($request) {
                $query->where('estado', $request->estado);
            })
            ->get();

        $roles = Rol::all();
        $modulos = Modulo::with('roles')->get();

        return view('admin.usuarios.index', compact('usuarios', 'roles', 'modulos'))
            ->with('title', 'Gestión de Usuarios');
    }

    public function crear()
    {
        $roles = Rol::all();
        return view('admin.usuarios.crear', compact('roles'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'ciUsuario'   => 'required|digits_between:6,8|unique:Usuario,ciUsuario',
            'nombre'      => 'required|string|max:50',
            'apellido'    => 'required|string|max:60',
            'correo'      => 'required|email|unique:Usuario,correo',
            'telefono'    => 'nullable|digits_between:7,8',
            'usuario'     => 'required|string|max:50|unique:Usuario,usuario',
            'contrasena'  => 'required|string|min:8',
            'rolId'       => 'required|exists:Rol,idRol',
            'estado'      => 'required|in:0,1',
        ]);

        $usuario = Usuario::create([
            'ciUsuario'   => $request->ciUsuario,
            'nombre'      => $request->nombre,
            'apellido'    => $request->apellido,
            'correo'      => $request->correo,
            'telefono'    => $request->telefono,
            'usuario'     => $request->usuario,
            'contrasena'  => Hash::make($request->contrasena),
            'rolId'       => $request->rolId,
            'estado'      => $request->estado ?? 1,
        ]);
        $this->logAction(
            "Se creó el usuario '{$usuario->usuario}' (CI: {$usuario->ciUsuario})",
            'Usuarios',
            'Exitoso'
        );
        return redirect()->route('usuarios.index')->with('exito', 'Usuario creado correctamente.');
    }

    public function mostrar($ciUsuario)
    {
        $usuario = Usuario::findOrFail($ciUsuario);
        return view('admin.usuarios.mostrar', compact('usuario'));
    }

    public function editar($ciUsuario)
    {
        $usuario = Usuario::findOrFail($ciUsuario);
        $roles = Rol::all();
        return view('admin.usuarios.editar', compact('usuario', 'roles'));
    }

    public function actualizar(Request $request, $ciUsuario)
    {
        $usuario = Usuario::findOrFail($ciUsuario);

        $request->validate([
            'nombre'      => 'required|string|max:50',
            'apellido'    => 'required|string|max:60',
            'correo'      => 'required|email|unique:Usuario,correo,' . $ciUsuario . ',ciUsuario',
            'telefono'    => 'nullable|digits_between:7,8',
            'usuario'     => 'required|string|max:50|unique:Usuario,usuario,' . $ciUsuario . ',ciUsuario',
            'rolId'       => 'required|exists:Rol,idRol',
            'estado'      => 'required|in:0,1',
            'contrasena'  => 'nullable|string|min:8',
        ]);

        $datos = $request->only([
            'nombre',
            'apellido',
            'correo',
            'telefono',
            'usuario',
            'rolId',
            'estado'
        ]);

        if ($request->filled('contrasena')) {
            $datos['contrasena'] = Hash::make($request->contrasena);
        }

        $usuario->update($datos);

        $this->logAction(
            "Se actualizó el usuario '{$usuario->usuario}' (CI: {$usuario->ciUsuario})",
            'Usuarios',
            'Exitoso'
        );
        return redirect()->route('usuarios.index')->with('exito', 'Usuario actualizado correctamente.');
    }

    public function eliminar($ciUsuario)
    {
        $usuario = Usuario::findOrFail($ciUsuario);
        $usuario->delete();
        $this->logAction(
            "Se eliminó el usuario '{$usuario->usuario}' (CI: {$usuario->ciUsuario})",
            'Usuarios',
            'Exitoso'
        );
        return redirect()->route('usuarios.index')->with('exito', 'Usuario eliminado correctamente.');
    }
}
