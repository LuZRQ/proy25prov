<?php

namespace Database\Factories;

use App\Models\Calificacion;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calificacion>
 */
class CalificacionFactory extends Factory
{
     protected $model = Calificacion::class;

    public function definition(): array
    {
        return [
            'ciUsuario' => Usuario::factory(),
            'calificacion' => $this->faker->numberBetween(1, 5),
            'comentario' => $this->faker->sentence(),
            'fecha' => now(),
        ];
    }
}
