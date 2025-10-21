<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
     use HasFactory;
    protected $table = 'Venta';
    protected $primaryKey = 'idVenta';
    public $timestamps = false;

    protected $fillable = [
        'idPedido',
        'montoTotal',
        'fechaPago',
        'metodo_pago',
        'pago_cliente',
        'cambio',
    ];
    protected $casts = [
        'fechaPago' => 'datetime',
    ];


    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'idPedido', 'idPedido');
    }
}
