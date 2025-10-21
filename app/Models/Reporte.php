<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
     use HasFactory;
    protected $table = 'Reporte';
    protected $primaryKey = 'idReporte';
    public $timestamps = false;
    protected $fillable = [
        'tipo',
        'periodo',
        'generadoPor',
        'fechaGeneracion',
        'archivo'
    ];

    protected $casts = [
        'fechaGeneracion' => 'datetime',
    ];
}
