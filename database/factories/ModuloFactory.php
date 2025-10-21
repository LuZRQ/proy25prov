<?php

namespace Database\Factories;

use App\Models\Modulo;
use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Modulo>
 */
class ModuloFactory extends Factory
{
     protected $model = Modulo::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->randomElement([
                'Usuarios',
                'Ventas',
                'Productos',
                'Reportes',
                'Cocina',
                'Auditoría',
                'Stock'
            ]),
            'descripcion' => $this->faker->sentence(),
        ];
    }

    /**
     * ✅ Crea un módulo asociado a un rol existente o nuevo
     */
    public function withRol(?Rol $rol = null)
    {
        return $this->afterCreating(function (Modulo $modulo) use ($rol) {
            $rol = $rol ?? Rol::factory()->create();
            $modulo->roles()->attach($rol->idRol);
        });
    }
}
