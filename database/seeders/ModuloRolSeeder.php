<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modulo;
use App\Models\Rol;

class ModuloRolSeeder extends Seeder
{
    public function run()
    {
        // Roles existentes
        $dueno = Rol::where('nombre', 'Dueno')->first();
        $cajero = Rol::where('nombre', 'Cajero')->first();
        $cocina = Rol::where('nombre', 'Cocina')->first();

        if (!$dueno || !$cajero || !$cocina) {
            $this->command->error("No se encontraron los roles Dueno, Cajero o Cocina. Créelos primero.");
            return;
        }

        // Crear módulos
        $modulos = [
            ['nombre' => 'Gestión de Ventas', 'roles' => [$dueno->idRol, $cajero->idRol]],
            ['nombre' => 'Gestión de Productos', 'roles' => [$dueno->idRol, $cocina->idRol]],
            ['nombre' => 'Control de Stock', 'roles' => [$dueno->idRol]],
            ['nombre' => 'Pedidos de Cocina', 'roles' => [$dueno->idRol, $cocina->idRol]],
            ['nombre' => 'Gestión de Reportes', 'roles' => [$dueno->idRol]],
            ['nombre' => 'Gestión de Auditoría', 'roles' => [$dueno->idRol]],
            ['nombre' => 'Usuarios y Roles', 'roles' => [$dueno->idRol]],
            
        ];

        foreach ($modulos as $modData) {
            $modulo = Modulo::updateOrCreate(
                ['nombre' => $modData['nombre']],
                ['descripcion' => $modData['nombre']]
            );

            // Asocia roles
            $modulo->roles()->sync($modData['roles']);
        }

        $this->command->info("Módulos y permisos creados correctamente.");
    }
}
