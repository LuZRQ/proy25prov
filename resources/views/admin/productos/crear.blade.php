@php
    $backRoute = route('productos.index');
    $title = 'Nuevo Producto';
@endphp
@extends('layouts.crud')

@section('content')
    <div class="bg-gradient-to-b from-amber-50 to-orange-50 min-h-screen p-6 rounded-lg shadow">

        <form action="{{ route('productos.guardar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-2 font-medium text-stone-700">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    @error('nombre')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-stone-700">Precio</label>
                    <input type="number" step="0.01" name="precio" value="{{ old('precio') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    @error('precio')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-stone-700">Stock Inicial</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" min="0"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    @error('stock')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-stone-700">Categoría</label>
                    <select name="categoriaId"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        <option value="">Seleccione una categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->idCategoria }}"
                                {{ old('categoriaId') == $categoria->idCategoria ? 'selected' : '' }}>
                                {{ $categoria->nombreCategoria }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoriaId')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-stone-700">Estado</label>
                    <select name="estado"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        <option value="1" {{ old('estado', 1) == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', 1) == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-stone-700">Imagen</label>
                    <input type="file" name="imagen"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    @error('imagen')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium text-stone-700">Descripción</label>
                    <textarea name="descripcion" rows="3"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:outline-none">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-6 flex gap-4">
                <button type="submit"
                    class="px-6 py-2 bg-amber-200 text-stone-800 font-medium rounded-lg hover:bg-amber-300 shadow">
                    Guardar
                </button>
                <a href="{{ route('productos.index') }}"
                    class="px-6 py-2 bg-stone-300 text-stone-800 font-medium rounded-lg hover:bg-stone-400 shadow">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
