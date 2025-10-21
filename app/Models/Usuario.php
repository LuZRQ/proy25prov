<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $ciUsuario
 * @property string $nombre
 * @property string $apellido
 * @property string $correo
 * @property string $telefono
 * @method bool save(array $options = [])
 * @method static \Illuminate\Database\Eloquent\Builder query()
 */

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable; 
    // ...
    public $timestamps = false;
    protected $table = 'Usuario';
    protected $primaryKey = 'ciUsuario';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'ultimo_acceso' => 'datetime',
    ];


    protected $fillable = [
        'ciUsuario',
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'usuario',
        'contrasena',
        'estado',
        'fechaRegistro',
        'rolId',
        'ultimo_acceso',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getAuthIdentifierName()
    {
        return 'ciUsuario';
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rolId', 'idRol');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'ciUsuario', 'ciUsuario');
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'ciUsuario', 'ciUsuario');
    }

    public function auditorias()
    {
        return $this->hasMany(Auditoria::class, 'ciUsuario', 'ciUsuario');
    }
    public function esDueno()
    {
        return $this->rol->nombre === 'Dueno';
    }
    public function esCajero()
    {
        return $this->rol->nombre === 'Cajero';
    }
    public function esCocinero()
    {
        return $this->rol->nombre === 'Cocina';
    }
    public function esMesero()
    {
        return $this->rol->nombre === 'Mesero';
    }
    public function esCliente()
    {
        return $this->rol->nombre === 'Cliente';
    }
    public function cajaActual()
    {
        return $this->hasOne(CajaActual::class, 'ciUsuario', 'ciUsuario');
    }
}
