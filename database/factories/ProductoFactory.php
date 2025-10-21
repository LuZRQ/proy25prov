<?php

namespace Database\Factories;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
      protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word(),
            'descripcion' => $this->faker->sentence(),
            'precio' => $this->faker->randomFloat(2, 5, 50),
            'stock' => $this->faker->numberBetween(5, 20),
            'stock_inicial' => $this->faker->numberBetween(5, 20),
            'estado' => 1,
            'categoriaId' => CategoriaProducto::factory(),
            'imagen' => null,
            'vendidos_dia' => 0,
            'fecha_actualizacion_stock' => now(),
        ];
    }
}
