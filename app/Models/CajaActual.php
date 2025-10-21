<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CajaActual extends Model
{
    use HasFactory;

    protected $table = 'caja_actual';
    protected $primaryKey = 'id_caja';
    public $timestamps = false;

    protected $fillable = [
        'ciUsuario',
        'fondo_inicial',
        'estado',
        'fecha_apertura',
        'observaciones',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ciUsuario', 'ciUsuario');
    }
}
