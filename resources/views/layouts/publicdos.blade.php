<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>@yield('title', 'Garabato Café')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {!! NoCaptcha::renderJs() !!}

</head>

<body class="bg-white" style="background-color: #13339c77;">
    <nav class="navbar navbar-light bg-white border-bottom sticky-top">
        <div class="container">
            <a class="btn btn-link text-decoration-none text-muted" href="javascript:history.back()">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </nav>

    <main class="pt-1">
        @yield('content')
    </main>

    <footer
        style="
    background: rgba(0, 0, 0, 0.61);
    color: #ffffff;
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    text-align: center;
    padding: 20px 0;
    font-size: 14px;
    letter-spacing: 0.5px;
">
        © 2025 | Tu Café, Tu Momento. Ven, quédate, saborea.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .hover-opacity:hover {
            opacity: 1 !important;
        }
    </style>
</body>

</html>
