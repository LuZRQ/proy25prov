<?php

namespace Tests\Feature\Publico;

use Tests\TestCase;
use App\Models\Producto;
use App\Models\CategoriaProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerMenuTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function la_pagina_publica_carga_correctamente()
    {
        // Crear categoría sin timestamps (respetando $timestamps = false)
        $categoria = CategoriaProducto::factory()->make();
        $categoria->save();

        // Crear producto asociado a la categoría
        $producto = Producto::factory()->create([
            'categoriaId' => $categoria->idCategoria,
            'estado' => 1, // tu tabla usa 'estado' no 'activo'
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSeeText($producto->nombre);
        $response->assertSeeText($categoria->nombreCategoria);
    }
}
