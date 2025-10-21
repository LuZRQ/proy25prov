<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel de Administración' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-b from-stone-100 to-white text-gray-800 font-sans" x-data="{ open: false }">
    <div class="fixed inset-y-0 left-0 z-40 w-64 transform bg-gradient-to-b from-stone-800 to-stone-900 text-white shadow-lg transition-transform duration-300"
        :class="{ '-translate-x-full': !open, 'translate-x-0': open }">

        <div class="absolute top-4 right-[-60px]">
            <button @click="open = !open"
                class="flex items-center space-x-1 bg-stone-700 text-white px-3 py-2 rounded-full shadow hover:bg-stone-600">
                <i class="fas fa-coffee text-amber-200"></i>
                <span>></span>
            </button>
        </div>

        <div class="flex items-center space-x-3 px-4 py-4 border-b border-stone-700">
            <div class="w-10 h-10 rounded-full bg-stone-600 flex items-center justify-center text-xl font-bold">
                {{ Auth::user()->nombre[0] ?? 'U' }}
            </div>
            <div>
                <p class="font-semibold">{{ Auth::user()->nombre ?? 'Usuario' }} {{ Auth::user()->apellido ?? '' }}</p>
                <p class="text-xs text-amber-200">{{ Auth::user()->rol->nombre ?? 'Rol' }}</p>
            </div>
        </div>

        <nav class="px-4 py-4 space-y-3 text-sm font-medium">
            <p class="uppercase text-xs text-stone-400 mb-2">Menú principal</p>

            @php
                $modulos = \App\Models\Modulo::all();
                $rolModulos = Auth::user()->rol->modulos->pluck('idModulo')->toArray();
            @endphp

            @foreach ($modulos as $modulo)
                @php
                    $habilitado = in_array($modulo->idModulo, $rolModulos);
                    $rutaValida = $modulo->ruta && Route::has($modulo->ruta);
                @endphp
                <a href="{{ $habilitado && $rutaValida ? route($modulo->ruta) : '#' }}"
                    class="block py-2 rounded {{ $habilitado && $rutaValida ? 'hover:text-amber-300' : 'text-stone-500 cursor-not-allowed opacity-60' }}">
                    {{ $modulo->nombre }}
                </a>
            @endforeach

        </nav>

        <div class="absolute bottom-0 w-full p-4 border-t border-stone-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full py-2 bg-stone-700 hover:bg-stone-600 text-white rounded-lg shadow">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

    <header class="bg-stone-800 text-white shadow-md flex-1 p-2 w-full">
        <div class="container mx-auto px-4 py-3 flex items-center justify-center">
            <h1 class="text-lg text-center font-bold">{{ $title ?? 'Panel de Administración' }}</h1>
        </div>
    </header>

    <main class="flex-1 p-6 w-full">
        @foreach (['exito', 'error', 'info'] as $msg)
            @if (session($msg))
                <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
                    <div class="toast align-items-center text-dark border-0 show shadow-lg animate__animated animate__fadeInDown"
                        style="
                     background: {{ $msg == 'exito' ? 'linear-gradient(135deg, #fffbe6, #f7f305e6)' : ($msg == 'error' ? 'linear-gradient(135deg, #f8d7da, #f5a4a8)' : 'linear-gradient(135deg, #d1ecf1, #a0e1f5)') }};
                     font-weight: bold;
                     border-radius: 20px;
                     box-shadow: 0 0 15px rgba(0,0,0,0.2);
                 ">
                        <div class="d-flex">
                            <div class="toast-body d-flex align-items-center">
                                <i class="fa-solid fa-mug-hot fa-bounce me-2 text-warning fs-4"></i>
                                <span class="fs-6">
                                    {{ session($msg) }}
                                </span>
                            </div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>
                <script>
                    setTimeout(() => {
                        const toast = document.querySelector('.toast.show');
                        toast.classList.remove('animate__fadeInDown');
                        toast.classList.add('animate__fadeOut');
                        setTimeout(() => toast.remove(), 1000);
                    }, 4000);
                </script>
            @endif
        @endforeach

        @yield('content')
    </main>

    @vite('resources/js/app.js')
    <script src="{{ asset('js/crudDelete.js') }}"></script>
    <script>
        window.rolUsuario = @json(Auth::user()?->rol?->nombre ?? '');
    </script>
    @vite('resources/js/ventas.js')
    @vite(['resources/js/reporte.js'])
</body>

</html>
