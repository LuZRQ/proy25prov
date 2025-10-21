<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    use HasFactory;
    protected $table = 'CategoriaProducto';
    protected $primaryKey = 'idCategoria';
    public $timestamps = false;

    protected $fillable = [
        'nombreCategoria',
        'descripcion'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoriaId', 'idCategoria');
    }
}
