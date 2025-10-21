<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory; 
    protected $table = 'Rol';
    protected $primaryKey = 'idRol';
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'rolId', 'idRol');
    }

    public function modulos()
    {
        return $this->belongsToMany(
            Modulo::class,
            'modulo_rol',
            'rol_id',
            'modulo_id'
        )->withTimestamps();
    }

    public function getColorAttribute()
    {
        return match ($this->nombre) {
            'Dueno' => 'bg-purple-500 text-white',
            'Cajero' => 'bg-green-500 text-white',
            'Cocina' => 'bg-red-500 text-white',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
