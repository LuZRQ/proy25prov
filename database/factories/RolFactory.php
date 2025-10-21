<?php

namespace Database\Factories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rol>
 */
class RolFactory extends Factory
{
    protected $model = Rol::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->randomElement([
                'Dueno', 'Cajero', 'Cocina', 'Mesero', 'Cliente'
            ]),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
