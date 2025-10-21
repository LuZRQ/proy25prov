<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['ciUsuario' => '11111111', 'nombre' => 'Rafaela', 'apellido' => 'Lima', 'correo' => 'rafaelafabiana.lv@gmail.com', 'telefono' => '76760238', 'usuario' => 'rafaela.lima', 'rolId' => 3],
            ['ciUsuario' => '22222222', 'nombre' => 'Ignacio', 'apellido' => 'Flores', 'correo' => 'ignaciof324@gmail.com', 'telefono' => '70612344', 'usuario' => 'ignacio.f', 'rolId' => 2], 
            ['ciUsuario' => '33333333', 'nombre' => 'Carla', 'apellido' => 'Flores', 'correo' => 'carlaf@garabato.com', 'telefono' => '70642866', 'usuario' => 'carla.f', 'rolId' => 4], 
            ['ciUsuario' => '44444444', 'nombre' => 'Ana', 'apellido' => 'Benavides', 'correo' => 'anitab@garabato.com', 'telefono' => '76751171', 'usuario' => 'ana.b', 'rolId' => 4], 
            ['ciUsuario' => '55555555', 'nombre' => 'Cinthya', 'apellido' => 'Licona', 'correo' => 'cintyl@garabato.com', 'telefono' => '73706041', 'usuario' => 'cinthya.l', 'rolId' => 4], 
            ['ciUsuario' => '99999999', 'nombre' => 'Dueño', 'apellido' => 'Principal', 'correo' => 'dueno@garabato.com', 'telefono' => '70000000', 'usuario' => 'dueno', 'rolId' => 1], 
        ];

        foreach ($usuarios as $u) {
            Usuario::create([
                'ciUsuario' => $u['ciUsuario'],
                'nombre' => $u['nombre'],
                'apellido' => $u['apellido'],
                'correo' => $u['correo'],
                'telefono' => $u['telefono'],
                'usuario' => $u['usuario'],
                'contrasena' => Hash::make('garbatocafe2025proy'),
                'rolId' => $u['rolId'],
            ]);
        }
    }
}
