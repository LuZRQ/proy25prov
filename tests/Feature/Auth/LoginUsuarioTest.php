<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class LoginUsuarioTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_usuario_activo_puede_iniciar_sesion_y_redirige_segun_su_rol(): void
    {
        $rol = Rol::factory()->create(['nombre' => 'Cajero']);

        $usuario = Usuario::factory()->create([
            'ciUsuario'  => '12345678',
            'contrasena' => Hash::make('12345678'),
            'estado'     => true,
            'rolId'      => $rol->idRol,
        ]);

        $response = $this->post(route('login'), [
            'ci'         => '12345678',
            'contrasena' => '12345678',
        ]);

        $response->assertRedirect(route('ventas.index'));
        $this->assertAuthenticatedAs($usuario);
    }

    #[Test]
    public function un_usuario_con_contrasena_incorrecta_no_puede_iniciar_sesion(): void
    {
        $rol = Rol::factory()->create(['nombre' => 'Cajero']);

        $usuario = Usuario::factory()->create([
            'ciUsuario'  => '87654321',
            'contrasena' => Hash::make('12345678'),
            'estado'     => true,
            'rolId'      => $rol->idRol,
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'ci'         => '87654321',
            'contrasena' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('ci');
        $this->assertGuest();
    }

    #[Test]
    public function un_usuario_inactivo_no_puede_iniciar_sesion(): void
    {
        $rol = Rol::factory()->create(['nombre' => 'Cajero']);

        $usuario = Usuario::factory()->create([
            'ciUsuario'  => '11122233',
            'contrasena' => Hash::make('123456'),
            'estado'     => false,
            'rolId'      => $rol->idRol,
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'ci'         => '11122233',
            'contrasena' => '123456',
        ]);

        $response->assertSessionHasErrors('ci');
        $this->assertGuest();
    }
}
