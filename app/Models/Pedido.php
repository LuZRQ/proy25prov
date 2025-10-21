<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
     use HasFactory;
    protected $table = 'Pedido';
    protected $primaryKey = 'idPedido';
    public $timestamps = false;

    protected $fillable = [
        'ciUsuario',
        'mesa',
        'comentarios',
        'estado',
        'total',
        'fechaCreacion'
    ];

    protected $casts = [
        'fechaCreacion' => 'datetime',
    ];
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ciUsuario', 'ciUsuario');
    }

    public function venta()
    {
        return $this->hasOne(Venta::class, 'idPedido', 'idPedido');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'idPedido', 'idPedido');
    }
}
