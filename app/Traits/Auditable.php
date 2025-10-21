<?php

namespace App\Traits;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public function logAction(string $mensaje, string $modulo, string $estado = 'Exitoso')
    {
        activity('sistema')
            ->causedBy(Auth::user() ?? null)
            ->withProperties([
                'ip_origen' => request()->ip(),
                'modulo' => $modulo,
                'estado' => $estado
            ])
            ->log($mensaje);
    }
}
