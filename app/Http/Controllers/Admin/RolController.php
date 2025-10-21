<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Modulo;
use App\Traits\Auditable;

class RolController extends Controller
{
    use Auditable;
    public function index()
    {
        $roles = Rol::all();
        return view('admin.roles.index', compact('roles'))
            ->with('title', 'Control de roles');
    }

    public function crear()
    {

        $modulos = Modulo::all();
        return view('admin.roles.crear', compact('modulos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre',
            'descripcion' => 'nullable|string|max:255',
            'modulos' => 'array',
            'modulos.*' => 'exists:modulos,id',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio',
            'nombre.unique' => 'Ya existe un rol con este nombre',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres',
            'modulos.*.exists' => 'El módulo seleccionado no es válido',
        ]);

        $rol = Rol::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        if ($request->has('modulos')) {
            $rol->modulos()->sync($request->modulos);
        }

        $this->logAction(
            "Se creó el rol '{$rol->nombre}' (ID: {$rol->id})",
            'Roles',
            'Exitoso'
        );

        return redirect()->route('roles.index')->with('exito', 'Rol creado correctamente.');
    }


    public function editar($idRol)
    {
        $rol = Rol::with('modulos')->findOrFail($idRol);
        $modulos = Modulo::all();
        return view('admin.roles.editar', compact('rol', 'modulos'));
    }

    public function actualizar(Request $request, $idRol)
    {
        $rol = Rol::findOrFail($idRol);

        $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre,' . $rol->id,
            'descripcion' => 'nullable|string|max:255',
            'modulos' => 'array',
            'modulos.*' => 'exists:modulos,id',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio',
            'nombre.unique' => 'Ya existe un rol con este nombre',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres',
            'modulos.*.exists' => 'El módulo seleccionado no es válido',
        ]);

        $rol->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        if ($request->has('modulos')) {
            $rol->modulos()->sync($request->modulos);
        } else {
            $rol->modulos()->sync([]);
        }

        $this->logAction(
            "Se actualizó el rol '{$rol->nombre}' (ID: {$rol->id})",
            'Roles',
            'Exitoso'
        );

        return redirect()->route('roles.index')->with('exito', 'Rol actualizado correctamente.');
    }


    public function eliminar($idRol)
    {
        $rol = Rol::findOrFail($idRol);
        $rol->delete();
        $this->logAction(
            "Se eliminó el rol '{$rol->nombre}' (ID: {$rol->id})",
            'Roles',
            'Exitoso'
        );
        return redirect()->route('roles.index')->with('exito', 'Rol eliminado correctamente.');
    }
}
