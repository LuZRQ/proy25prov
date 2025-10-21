<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Modulo;

class VerificarRolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles  // Los roles permitidos
     */
    public function handle(Request $request, Closure $next, $moduloNombre)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect('/ingresar')->withErrors(['auth' => 'Debes iniciar sesión primero']);
        }

        $modulo = Modulo::where('nombre', $moduloNombre)->with('roles')->first();

        if (!$modulo) {
            abort(403, 'Módulo no encontrado');
        }

        if (!$modulo->roles->contains($usuario->rolId)) {
            abort(403, 'No tienes permisos para este módulo');
        }

        return $next($request);
    }
}
