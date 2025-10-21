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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @Vite('resources/js/caja.js')
</head>

<body class="bg-gradient-to-b from-stone-100 to-white text-orange-800 font-sans" x-data="{ open: false }">

    <header class="bg-stone-800 text-white shadow-md w-full">
        <div class="container mx-auto px-6 py-3 flex items-center justify-between">

            <a href="{{ $backRoute ?? url()->previous() }}"
                class="flex items-center gap-2 px-4 py-2 rounded-full border border-stone-500 text-stone-300 
                   hover:text-amber-300 hover:border-amber-400 hover:bg-stone-700 transition-all duration-200 shadow-sm">
                <i class="fas fa-arrow-left text-sm"></i>
                <span class="text-sm font-medium">Volver</span>
            </a>

            <h1 class="text-xl font-semibold text-center flex-1 text-amber-200 tracking-wide">
                {{ $title ?? 'Panel de Administración' }}
            </h1>
            <div class="w-[90px]"></div>
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
    @stack('scripts')
</body>

</html>

