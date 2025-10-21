<?php

namespace Tests\Feature\Publico;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Calificacion;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OpinionesClienteTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function un_cliente_puede_enviar_opinion()
    {
        // Crear el rol Cliente primero
        $rolCliente = Rol::factory()->create([
            'idRol' => 4,
            'nombre' => 'Cliente'
        ]);

        // Crear usuario con rol Cliente
        $usuario = Usuario::factory()->create([
            'rolId' => $rolCliente->idRol
        ]);
        /** @var Usuario $usuario */
        $this->actingAs($usuario);

        // Enviar opinion
        $response = $this->post(route('opiniones.store'), [
            'rating' => 5,
            'comentario' => 'Excelente servicio!'
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('Calificacion', [
            'ciUsuario' => $usuario->ciUsuario,
            'comentario' => 'Excelente servicio!',
            'calificacion' => 5
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function un_cliente_puede_actualizar_su_opinion()
    {
        // Crear rol Cliente
        $rolCliente = Rol::factory()->create([
            'idRol' => 4,
            'nombre' => 'Cliente'
        ]);

        // Crear usuario
        $usuario = Usuario::factory()->create([
            'rolId' => $rolCliente->idRol
        ]);
        /** @var Usuario $usuario */
        $this->actingAs($usuario);

        // Crear opinion existente
        $calificacion = Calificacion::factory()->create([
            'ciUsuario' => $usuario->ciUsuario,
            'comentario' => 'Bueno',
            'calificacion' => 3
        ]);

        // Actualizar opinion
        $response = $this->put(route('opiniones.update'), [
            'rating' => 5,
            'comentario' => 'Excelente servicio!'
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('Calificacion', [
            'ciUsuario' => $usuario->ciUsuario,
            'comentario' => 'Excelente servicio!',
            'calificacion' => 5
        ]);
    }
}
