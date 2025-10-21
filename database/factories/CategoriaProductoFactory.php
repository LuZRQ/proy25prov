<?php

namespace Database\Factories;

use App\Models\CategoriaProducto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoriaProducto>
 */
class CategoriaProductoFactory extends Factory
{
  protected $model = CategoriaProducto::class;

    public function definition(): array
    {
        return [
            'nombreCategoria' => $this->faker->word(),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
