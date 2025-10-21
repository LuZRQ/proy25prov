<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Recibo')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&family=Pacifico&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    @Vite('resources/js/recibo.js')
    <style>
        body {
            font-family: 'Roboto Mono', monospace;
        }

        .ticket-title {
            font-family: 'Pacifico', cursive;
        }

        input[type="range"]::-webkit-slider-thumb {
            background-color: #4A5568;

        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 min-h-screen">

    <main class="container mx-auto my-6">
        @yield('content')
    </main>

    @stack('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

</body>

</html>
