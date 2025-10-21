<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
     use HasFactory;
    protected $table = 'Auditoria';
    protected $primaryKey = 'idAuditoria';
    public $timestamps = false;

    protected $fillable = [
        'accion',
        'fechaHora',
        'ciUsuario',
        'ipOrigen',
        'modulo'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ciUsuario', 'ciUsuario');
    }
}
