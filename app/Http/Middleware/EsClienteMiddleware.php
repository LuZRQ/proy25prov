<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class EsClienteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    public function handle(Request $request, Closure $next)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect('/ingresar')->withErrors(['auth' => 'Debes iniciar sesión primero']);
        }

        if ($usuario->rol->nombre !== 'Cliente') {
            abort(403, 'Solo los clientes pueden acceder a esta sección.');
        }

        return $next($request);
    }
}
