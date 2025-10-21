<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {

        $input = $request->only([
            'ciUsuario',
            'nombre',
            'apellido',
            'correo',
            'telefono',
            'usuario',
            'contrasena',
            'contrasena_confirmation'
        ]);

        foreach ($input as $key => $value) {
            $input[$key] = trim(strip_tags($value));
        }

        $request->merge($input);
        $request->validate([
            'ciUsuario' => 'nullable|digits:8|unique:Usuario,ciUsuario',
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:60',
            'correo' => 'required|email|max:100|unique:Usuario,correo',
            'telefono' => 'nullable|digits:8',
            'usuario' => 'required|string|max:30|unique:Usuario,usuario',
            'contrasena' => 'required|string|confirmed|min:6|max:20',
        ]);


        $rolCliente = Rol::where('nombre', 'Cliente')->first();

        if (!$rolCliente) {
            abort(500, 'No se encontró el rol Cliente. Verifica la tabla roles.');
        }

        $usuario = Usuario::create([
            'ciUsuario' => $request->ciUsuario ?? str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT),
            'nombre' => $input['nombre'],
            'apellido' => $input['apellido'],
            'correo' => $input['correo'],
            'telefono' => $input['telefono'],
            'usuario' => $input['usuario'],
            'contrasena' => Hash::make($input['contrasena']),
            'rolId' => $rolCliente->idRol,
        ]);


        Auth::login($usuario);
        $usuario->load('rol');
        return redirect()->route('home')
            ->with('exito', '¡Bienvenido a Garabato Café! Tu cuenta se creó exitosamente.');
    }
}
