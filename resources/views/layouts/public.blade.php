<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>@yield('title', 'Garabato Café')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-white" style="background-color: #13339c77;">
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('img/fondo3.png') }}" alt="Garabato Café Logo" height="40" class="me-2">
                <span class="fw-bold">Garabato Café</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#menu') }}">Menú</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#nosotros') }}">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#direccion') }}">Dirección</a>
                    </li>
                    <li class="nav-item ms-3">
                        @auth
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="pt-1">
        @if (session('exito'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
                <div id="toastExito"
                    class="toast align-items-center text-dark border-0 show shadow-lg animate__animated animate__fadeInDown"
                    style="
                background: linear-gradient(135deg, #fffbe6, #f7f305e6);
                font-weight: bold;
                border-radius: 20px;
                box-shadow: 0 0 15px rgba(247, 243, 5, 0.8), 0 0 30px rgba(247, 243, 5, 0.5);
            ">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <i class="fa-solid fa-mug-hot fa-bounce me-2 text-warning fs-4"></i>
                            <span class="fs-6">
                                {{ session('exito') }}
                            </span>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>

            <script>
                setTimeout(() => {
                    const toast = document.getElementById('toastExito');
                    toast.classList.remove('animate__fadeInDown');
                    toast.classList.add('animate__fadeOut');
                    setTimeout(() => toast.remove(), 1000);
                }, 4000);
            </script>
        @endif



        @yield('content')
    </main>

    <footer class="bg-black text-light mt-5 py-4">
        <div class="container text-center">

            <div class="small mb-3">
                © Garabato Café {{ date('Y') }} - Todos los derechos reservados.
            </div>

            <div class="d-flex justify-content-center gap-4 fs-5">
                <a class="text-light opacity-75 hover-opacity" href="#" aria-label="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>
                <a class="text-light opacity-75 hover-opacity" href="#" aria-label="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a class="text-light opacity-75 hover-opacity" href="#" aria-label="TikTok">
                    <i class="bi bi-tiktok"></i>
                </a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://kit.fontawesome.com/a2e0e9e1b4.js" crossorigin="anonymous"></script>
    <style>
        .hover-opacity:hover {
            opacity: 1 !important;
        }
    </style>
</body>

</html>
