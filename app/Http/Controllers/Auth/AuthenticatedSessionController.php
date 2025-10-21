<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Auditoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $key = Str::lower($request->input('ci')) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            throw ValidationException::withMessages([
                'ci' => "Demasiados intentos fallidos 🙂",
            ]);
        }

        $request->merge([
            'ci' => strip_tags($request->input('ci')),
            'contrasena' => strip_tags($request->input('contrasena')),
        ]);

        $request->validate([
            'ci' => 'required|string|max:8|exists:Usuario,ciUsuario',
            'contrasena' => 'required|string|max:20',
            'g-recaptcha-response' => RateLimiter::attempts($key) >= 3 ? 'required|captcha' : '',
        ], [
            'ci.exists' => 'El CI ingresado no es válido',
            'g-recaptcha-response.required' => 'Debes completar la verificación CAPTCHA',
            'g-recaptcha-response.captcha' => 'Error en la verificación CAPTCHA',
        ]);

        $usuario = Usuario::where('ciUsuario', $request->ci)->first();

        if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
            RateLimiter::hit($key, 3600);

            Auditoria::create([
                'ciUsuario' => $request->ci,
                'accion' => 'Intento de login fallido',
                'fechaHora' => now(),
                'ipOrigen' => $request->ip(),
                'modulo' => 'Login',
            ]);

            return back()->withErrors(['ci' => 'Credenciales inválidas']);
        }

        if (!$usuario->estado) {
            return back()->withErrors(['ci' => 'Tu cuenta está inactiva, no puedes acceder.']);
        }

        RateLimiter::clear($key);
        Auth::login($usuario);

        $usuario->ultimo_acceso = now();
        $usuario->save();

        Auditoria::create([
            'ciUsuario' => $usuario->ciUsuario,
            'accion' => 'Login exitoso',
            'fechaHora' => now(),
            'ipOrigen' => $request->ip(),
            'modulo' => 'Login',
        ]);


        switch ($usuario->rol->nombre) {
            case 'Dueno':
                return redirect()->route('usuarios.index');
            case 'Cajero':
                return redirect()->route('ventas.index');
            case 'Cocina':
                return redirect()->route('pedidos.index');
            case 'Mesero':
                return redirect()->route('ventas.index');
            case 'Cliente':
                return redirect()->route('home');
            default:
                Auth::logout();
                return redirect('/')->withErrors(['ci' => 'Rol no permitido']);
        }
    }


    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect()->route('home');
    }
}
