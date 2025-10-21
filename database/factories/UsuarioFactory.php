<?php

namespace Database\Factories;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'ciUsuario'  => $this->faker->unique()->numerify('########'),
            'nombre'     => $this->faker->firstName(),
            'apellido'   => $this->faker->lastName(),
            'correo'     => $this->faker->safeEmail(),
            'telefono'   => $this->faker->phoneNumber(),
            'usuario'    => $this->faker->userName(),
            'contrasena' => Hash::make('123456'),
            'estado'     => true,
            'fechaRegistro' => now(),
            'rolId'      => Rol::factory(),
        ];
    }
}
