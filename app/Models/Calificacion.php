<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;
    protected $table = 'Calificacion';
    protected $primaryKey = 'idCalificacion';
    public $timestamps = false;
    protected $fillable = [
        'ciUsuario',
        'calificacion',
        'comentario',
        'fecha'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ciUsuario', 'ciUsuario');
    }
}
