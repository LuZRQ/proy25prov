@extends('layouts.publicdos')

@section('title', 'Registro • Garabato Café')

@section('content')
<section class="d-flex align-items-center justify-content-center"
    style="min-height: 100vh; width: 100%; background-image: url('{{ asset('img/fondo2.jpeg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">

    <div class="card shadow-lg p-4 rounded-4"
         style="max-width: 500px; width: 90%; background: rgba(59, 46, 1, 0.493); color: white;">

        {{-- Logo --}}
        <div class="text-center mb-4">
            <img src="{{ asset('img/fondo3.png') }}" alt="Garabato Café" class="rounded-circle shadow"
                 style="width:100px; height:100px; object-fit:cover; border: 3px solid white;background: rgba(236, 227, 195, 0.856);">
        </div>

        <h4 class="text-center mb-4 fw-bold">Crear Cuenta</h4>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">CI</label>
                <input type="text" name="ciUsuario" class="form-control" value="{{ old('ciUsuario') }}" required
                       pattern="\d{1,8}" maxlength="8" title="Ingresa hasta 8 números">
                @error('ciUsuario') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="row g-2">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required maxlength="50">
                    @error('nombre') <small class="text-warning">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Apellido</label>
                    <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required maxlength="50">
                    @error('apellido') <small class="text-warning">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Correo</label>
                <input type="email" name="correo" class="form-control" value="{{ old('correo') }}" required maxlength="100">
                @error('correo') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" pattern="\d{7,15}" title="Sólo números, entre 7 y 15 dígitos" maxlength="15">
                @error('telefono') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Usuario</label>
                <input type="text" name="usuario" class="form-control" value="{{ old('usuario') }}" required maxlength="30">
                @error('usuario') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Contraseña</label>
                <input type="password" name="contrasena" class="form-control" required minlength="6" maxlength="20">
                @error('contrasena') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Confirmar Contraseña</label>
                <input type="password" name="contrasena_confirmation" class="form-control" required minlength="6" maxlength="20">
            </div>

            <button type="submit" class="btn w-100 fw-bold text-dark" style="background-color: #f0dd97;">
                Registrarme
            </button>
        </form>
    </div>
</section>
@endsection
