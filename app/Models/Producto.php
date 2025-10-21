<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'Producto';
    protected $primaryKey = 'idProducto';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'stock_inicial',
        'estado',
        'categoriaId',
        'imagen',
        'vendidos_dia',
        'fecha_actualizacion_stock'
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoriaId', 'idCategoria');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class, 'idProducto', 'idProducto');
    }

    public function getVendidosAttribute()
    {
        return $this->stock_inicial - $this->stock;
    }

    public function getRestanteAttribute()
    {
        return $this->stock;
    }

    public function getEstadoStock(): string
    {
        if ($this->stock <= 0) return 'rojo';
        if ($this->stock < 5) return 'rojo';
        if ($this->stock < 10) return 'amarillo';
        return 'verde';
    }

    public function getEstadoStockNombre(): string
    {
        return match ($this->getEstadoStock()) {
            'rojo' => 'Crítico',
            'amarillo' => 'Bajo',
            'verde' => 'Disponible',
            default => 'Desconocido',
        };
    }

    public function descontarStock(int $cantidad): bool
    {
        if ($this->stock < $cantidad) {
            return false;
        }

        $this->stock -= $cantidad;
        $this->vendidos_dia += $cantidad;
        $this->save();

        return true;
    }
}
