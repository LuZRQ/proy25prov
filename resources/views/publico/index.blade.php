@extends('layouts.public')

@section('title', 'Inicio • Garabato Café')

@section('content')

    <section class="hero-cover d-flex align-items-center"
        style="background-image: url('{{ asset('img/fondo1.jpeg') }}');
           background-size: cover;
           background-position: center;
           height: 350px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div
                        class="hero-card bg-dark bg-opacity-75 rounded-4 shadow-lg p-5 text-center d-flex flex-column align-items-center">

                        <h2 class="display-6 mb-3 text-white text-center" style="font-family: 'Playfair Display', serif;">
                            @guest
                                <h2 class="display-6 mb-3 text-white text-center" style="font-family: 'Playfair Display', serif;">
                                    ¿Ya eres cliente de <span class="text-warning">Garabato Café</span>?
                                </h2>
                                <p class="mb-4 text-light fs-5 text-center" style="font-family: 'Playfair Display', serif;">
                                    Regístrate para dejar tu opinión.
                                </p>
                            @else
                                ¡Qué alegría tenerte de vuelta, <span style="color: #fff06d;">{{ Auth::user()->nombre }}</span>!
                            @endguest
                        </h2>

                        @auth
                            <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
                                <img src="{{ asset('img/cafe.gif') }}" alt="Café" style="height: 50px;">

                                <p class="mb-0 text-light fs-5 fst-italic text-center"
                                    style="font-family: 'Playfair Display', serif; max-width: 400px;">
                                    “Cada día mejora con café caliente y panqueques que abrazan el alma.”
                                </p>

                                <img src="{{ asset('img/pancakes.gif') }}" alt="Panqueques" style="height: 50px;">
                            </div>
                        @endauth

                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-light px-4 py-2">
                                <i class="bi bi-person-vcard me-1"></i> Registrarse
                            </a>
                        @endguest

                    </div>
                </div>
            </div>
        </div>
    </section>



    {{-- OPINIONES Y COMENTARIOS --}}
    <section class="py-5">
        <div class="container">
            @auth
                @if ($usuario->rol && $usuario->rol->nombre === 'Cliente')
                    <div class="row justify-content-center mb-5">
                        <div class="col-md-6">
                            <div class="form-box shadow-lg p-4">
                                <h3 class="section-title mb-4 text-center">¿Cómo fue tu experiencia?</h3>

                                @if (!$yaOpino)
                                    <form method="POST" action="{{ route('opiniones.store') }}" class="text-center">
                                        @csrf
                                        <input type="hidden" name="rating" id="ratingInput">
                                        <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
                                            @php
                                                $emojis = [
                                                    5 => 'feliz.png',
                                                    4 => 'sonriente.png',
                                                    3 => 'neutro.png',
                                                    2 => 'triste.png',
                                                    1 => 'enojado.png',
                                                ];
                                            @endphp

                                            @foreach ($emojis as $val => $img)
                                                <button type="button" class="emoji-btn border bg-light rounded-circle p-2"
                                                    data-value="{{ $val }}">
                                                    <img src="{{ asset('img/' . $img) }}" alt="emoji" width="30"
                                                        height="30">
                                                </button>
                                            @endforeach
                                        </div>

                                        <div class="mb-3">
                                            <textarea name="comentario" class="form-control" rows="3" placeholder="Escribe tu opinión..."></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-dark px-4 py-2 fw-bold"
                                            style="background: linear-gradient(45deg, #3c2a21, #000); border: 2px solid gold; color: gold;">
                                            Enviar ✨
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('opiniones.update') }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="rating" id="ratingInput"
                                            value="{{ $yaOpino->calificacion }}">
                                        <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
                                            @php
                                                $emojis = [
                                                    5 => 'feliz.png',
                                                    4 => 'sonriente.png',
                                                    3 => 'neutro.png',
                                                    2 => 'triste.png',
                                                    1 => 'enojado.png',
                                                ];
                                            @endphp

                                            @foreach ($emojis as $val => $img)
                                                <button type="button"
                                                    class="emoji-btn border bg-light rounded-circle p-2 {{ $yaOpino->calificacion == $val ? 'selected' : '' }}"
                                                    data-value="{{ $val }}">
                                                    <img src="{{ asset('img/' . $img) }}" alt="emoji" width="30"
                                                        height="30">
                                                </button>
                                            @endforeach
                                        </div>

                                        <div class="mb-3">
                                            <textarea name="comentario" class="form-control" rows="3">{{ $yaOpino->comentario }}</textarea>
                                        </div>

                                        <button type="submit" class="btn btn-warning px-4 py-2 fw-bold">
                                            Actualizar ✨
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

            <div>
                <h3 class="section-title mb-4">Opiniones de clientes</h3>
                <div class="row g-4">
                    @forelse ($opiniones->take(3) as $opinion)
                        <div class="col-md-4">
                            <div class="opinion-bubble p-3">
                                <div class="d-flex align-items-center mb-2">
                                    @php
                                        $icons = [
                                            1 => 'enojado.gif',
                                            2 => 'triste.gif',
                                            3 => 'neutro.gif',
                                            4 => 'sonriente.gif',
                                            5 => 'feliz.gif',
                                        ];
                                    @endphp

                                    <img src="{{ asset('img/' . $icons[$opinion->calificacion]) }}" width="48"
                                        height="48" class="me-2">

                                    <div>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $opinion->usuario->nombre ?? 'Anónimo' }}
                                            <small class="text-muted"
                                                style="font-weight: normal; font-size: 0.8rem; margin-left: 8px;">
                                                {{ \Carbon\Carbon::parse($opinion->fecha)->format('d M, Y') }}
                                            </small>
                                        </h6>
                                        <div class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span style="font-size: 1.1rem;">{!! $i <= $opinion->calificacion ? '★' : '☆' !!}</span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                <p class="mb-2">{{ $opinion->comentario }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Aún no hay opiniones registradas.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

    <script>
        const emojiBtns = document.querySelectorAll('.emoji-btn');
        const ratingInput = document.getElementById('ratingInput');

        emojiBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                emojiBtns.forEach(b => b.classList.remove('active-emoji'));
                btn.classList.add('active-emoji');
                ratingInput.value = btn.dataset.value;
            });
        });
    </script>

    <style>
        .form-box {
            background: linear-gradient(135deg, #fdfaf0, #ffe985cc);
            border-radius: 16px;
            border: 2px solid #f0f0ddb0;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);

        }

        .form-box:hover {

            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
        }

        .opinion-bubble {
            position: relative;
            background: linear-gradient(135deg, #fffbea, #fdf2d7);
            border-radius: 16px;
            padding: 1rem;
            border: 2px solid #d4a017;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15),
                inset 0 0 10px rgba(255, 223, 0, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .opinion-bubble:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 18px rgba(0, 0, 0, 0.25),
                inset 0 0 12px rgba(255, 223, 0, 0.3);
        }

        .opinion-bubble::after {
            content: "";
            position: absolute;
            bottom: -12px;
            left: 30px;
            border-width: 12px 12px 0;
            border-style: solid;
            border-color: #fffbea transparent;
            filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.2));
        }

        .emoji-btn {
            transition: transform 0.2s, background 0.3s;
        }

        .emoji-btn:hover {
            transform: scale(1.2);
            background: #e0eefd;
        }

        .active-emoji {
            background: rgba(65, 53, 1, 0.747) !important;
            border-color: #000 !important;
        }
    </style>


{{-- SECCIÓN DE APIS EXTERNAS --}}
<section class="py-5 position-relative" style="background: linear-gradient(135deg, #f9f4ef, #f0ece9); background-image: url('https://www.transparenttextures.com/patterns/white-wall.png');">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold text-dark text-uppercase display-6 position-relative">
            <span style="position: relative; z-index: 2;">Empieza con sabor, clima y datos curiosos ☕</span>
            <span class="position-absolute start-50 translate-middle-x" style="bottom: -10px; width: 60px; height: 4px; background: #c49a6c; border-radius: 10px;"></span>
        </h2>

        <div class="row g-4 justify-content-center">

         {{-- CARD: Clima --}}
<div class="col-md-4 col-sm-10">
    <div class="card h-100 text-center p-4 border-0 shadow-lg rounded-4 glass-card glass-blue">
        <div class="mb-3">
            <i class="fas fa-cloud-sun fa-2x text-info icon-bg"></i>
        </div>
        <h5 class="fw-bold mb-3">Clima en La Paz</h5>

        @if($clima)
            <img src="https://openweathermap.org/img/wn/{{ $clima['icon'] }}@2x.png"
                 alt="icono clima" width="80" height="80" class="mx-auto d-block">
            <p class="mt-3 fs-4 fw-semibold">{{ $clima['temp'] }}°C</p>
            <p class="text-muted mb-2">{{ ucfirst($clima['desc']) }}</p>

            {{-- 🔸 Mensaje personalizado --}}
            <div class="alert alert-light border-0 bg-opacity-25 shadow-sm mt-3 rounded-3 px-3 py-2 fw-semibold text-dark d-flex align-items-center justify-content-center gap-2"
                 style="background: rgba(255,255,255,0.35); font-size: 0.95rem;">
                <i class="fas {{ $clima['icono'] }} fa-lg"></i>
                <span>{{ $clima['mensaje'] }}</span>
            </div>
        @else
            <p class="text-muted">No disponible</p>
        @endif
    </div>
</div>


            {{-- CARD: Trivia del Café --}}
            <div class="col-md-4 col-sm-10">
                <div class="card h-100 text-center p-4 border-0 shadow-lg rounded-4 glass-card glass-gold">
                    <div class="mb-3">
                        <i class="fas fa-mug-hot fa-2x text-warning icon-bg"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Trivia del Café</h5>
                    @if($triviaCafe)
                        <p class="fw-semibold fs-5">{{ $triviaCafe['pregunta'] }}</p>
                        <div class="bg-light text-dark shadow-sm px-3 py-2 my-2 rounded text-break text-start w-100" style="font-size: 0.95rem; word-wrap: break-word;">
                            <i class="fas fa-lightbulb text-warning me-1"></i>
                            <strong>Respuesta:</strong><br>
                            {{ $triviaCafe['respuesta'] }}
                        </div>
                        <hr class="opacity-10">
                        <p class="text-muted fst-italic small">
                            <strong>Traducción:</strong><br>
                            {{ $triviaCafe['pregunta_es'] }} <br>
                            <span class="text-success">Respuesta: {{ $triviaCafe['respuesta_es'] }}</span>
                        </p>
                    @else
                        <p class="text-muted">No disponible</p>
                    @endif
                </div>
            </div>
{{-- CARD: Recomendación del Día --}}
<div class="col-md-4 col-sm-10">
    <div class="card h-100 text-center p-4 border-0 shadow-lg rounded-4 glass-card glass-pink">
        <div class="mb-3">
            <i class="fas fa-mug-hot fa-2x text-danger icon-bg"></i>
        </div>
        <h5 class="fw-bold mb-3 text-dark">Recomendación del Día ☕</h5>

        @if($bebidaRecomendada)
            <img src="{{ $bebidaRecomendada['imagen'] }}"
                 alt="{{ $bebidaRecomendada['nombre'] }}"
                 class="img-fluid rounded-3 shadow-sm mb-3 card-img-hover"
                 style="max-height: 250px; object-fit: cover;">

            <h6 class="fw-bold text-dark">{{ $bebidaRecomendada['nombre'] }}</h6>
            <p class="text-muted mb-1">
                <i class="fas fa-tag me-1 text-secondary"></i>
                {{ $bebidaRecomendada['categoria'] }}
            </p>

            <p class="small fst-italic text-muted">
                <i class="fas fa-quote-left me-1 text-pink"></i>
                {{ $bebidaRecomendada['ingrediente'] }}
            </p>

            <div class="mt-2 fw-bold text-danger fs-5">
                <i class="fas fa-coins me-1"></i>Bs {{ number_format($bebidaRecomendada['precio'], 2) }}
            </div>
        @else
            <p class="text-muted">No disponible</p>
        @endif
    </div>
</div>


        </div>
    </div>
</section>

{{-- Estilos Glassmorphism mejorados --}}
<style>
    .glass-card {
        color: #2c2c2c;
        backdrop-filter: blur(12px);
        border-radius: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .glass-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    /* === Variantes de color === */
    .glass-blue {
        background: linear-gradient(145deg, rgba(173, 216, 230, 0.4), rgba(240, 248, 255, 0.2));
        box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.1), 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .glass-gold {
        background: linear-gradient(145deg, rgba(245, 222, 179, 0.4), rgba(255, 255, 255, 0.2));
        box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.1), 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .glass-pink {
        background: linear-gradient(145deg, rgba(255, 182, 193, 0.4), rgba(255, 255, 255, 0.2));
        box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.1), 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    /* Icon background circle */
    .icon-bg {
        background: rgba(255, 255, 255, 0.3);
        padding: 16px;
        border-radius: 50%;
        backdrop-filter: blur(5px);
    }

    /* Imagen hover efecto */
    .card-img-hover:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease-in-out;
    }

    h5 {
        font-family: 'Poppins', sans-serif;
    }

    /* Responsive fix for long text */
    .text-break {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
</style>

    {{-- =================== MENÚ =================== --}}
    <section id="menu" class="py-5 bg-light">
        <div class="container">
            <h3 class="text-center section-title mb-4">Nuestro Menú</h3>
            <ul class="nav nav-pills justify-content-center gap-2 pill-filter mb-4">
                @php
                    $totalProductos = count($productos);
                @endphp
                <li class="nav-item">
                    <a class="nav-link active" data-category="all" href="#">Todo ({{ $totalProductos }})</a>
                </li>

                @foreach ($categorias as $categoria)
                    @php
                        $countCat = $productos->where('categoriaId', $categoria->idCategoria)->count();
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link" data-category="{{ $categoria->idCategoria }}" href="#">
                            {{ $categoria->nombreCategoria }} ({{ $countCat }})
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="row g-4" id="menu-items">
                @foreach ($productos as $p)
                    <div class="col-12 col-md-6 col-lg-4 menu-item" data-category="{{ $p->categoriaId }}">
                        <div class="menu-card garabato-card rounded-4 p-3 h-100 shadow-sm">

                            <div
                                class="ratio ratio-16x9 mb-3 rounded-3 overflow-hidden border garabato-img position-relative">
                                <div
                                    class="absolute top-0 start-0 w-100 h-100 bg-gradient-to-tr from-amber-200 to-amber-400">
                                </div>
                                <img src="{{ $p->imagen ? asset('storage/' . $p->imagen) : asset('images/default.png') }}"
                                    alt="{{ $p->nombre }}"
                                    class="w-100 h-100 object-fit-cover position-absolute top-0 start-0 mix-blend-multiply">
                            </div>
                            <h5 class="mb-1 fw-bold">{{ $p->nombre }}</h5>
                            <p class="text-muted small mb-2">{{ $p->descripcion }}</p>
                            <div class="fw-bold text-coffee">Bs. {{ number_format($p->precio, 2, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <style>
        .menu-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: linear-gradient(135deg, #fffaf0, #fdf3e5);
            border-radius: 16px;
            border: 1px solid #e0cda9;
        }

        .garabato-img img {
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .garabato-img img:hover {
            transform: scale(1.05);
        }

        .nav-link {
            cursor: pointer;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background-color: #b8734e;
            color: gold !important;
        }
    </style>

    <script>
        const filterLinks = document.querySelectorAll('.nav-link[data-category]');
        const menuItems = document.querySelectorAll('.menu-item');

        filterLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                filterLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                const category = this.dataset.category;

                menuItems.forEach(item => {
                    if (category === 'all' || item.dataset.category.toString() === category
                        .toString()) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>



    {{-- Nuestra Historia --}}
    <section id="nosotros" class="py-5 bg-light">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-lg-6">
                    <div class="p-4 rounded-4 shadow-sm bg-white">
                        <h3 class="section-title mb-3">Nuestra Historia</h3>
                        <p class="text-muted">
                            Garabato Café nació de la pasión por el arte y el café.
                            Un espacio único en La Paz donde cada rincón está diseñado
                            para transportarte a un mundo ilustrado mientras disfrutas
                            de las mejores bebidas y aperitivos.
                        </p>

                        <div class="d-flex flex-wrap gap-4 mt-4 justify-content-center justify-content-lg-start">
                            <div class="text-center">
                                <div class="fs-3 text-coffee mb-1 icon-box">
                                    <i class="bi bi-emoji-sunglasses"></i>
                                </div>
                                <div class="small fw-semibold">Café de Especialidad</div>
                            </div>
                            <div class="text-center">
                                <div class="fs-3 text-coffee mb-1 icon-box">
                                    <i class="bi bi-brush"></i>
                                </div>
                                <div class="small fw-semibold">Arte Original</div>
                            </div>
                            <div class="text-center">
                                <div class="fs-3 text-coffee mb-1 icon-box">
                                    <i class="bi bi-heart"></i>
                                </div>
                                <div class="small fw-semibold">Ambiente Único</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="position-relative ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg">
                        <img src="{{ asset('img/fondo2.jpeg') }}" alt="Nuestra Historia"
                            class="w-100 h-100 object-fit-cover">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- Dirección + mapa --}}
    <section id="direccion" class="py-5 bg-light">
        <div class="container">
            <h3 class="text-center section-title mb-5">Encuéntranos</h3>
            <div class="row g-4 align-items-center">
                <div class="col-12 col-lg-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                        <h6 class="fw-bold text-coffee mb-1"><i class="bi bi-geo-alt-fill me-2"></i>Dirección
                        </h6>
                        <p class="text-muted mb-4">Calle Pinilla esq. Av. 6 de Agosto – La Paz, Bolivia</p>

                        <h6 class="fw-bold text-coffee mb-1"><i class="bi bi-clock-fill me-2"></i>Horarios
                        </h6>
                        <p class="text-muted mb-0">Lunes a Viernes <br> 4:00 PM – 10:00 PM</p>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="ratio ratio-16x9 rounded-4 shadow-lg overflow-hidden">

                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.2931635680065!2d-68.1237883!3d-16.511290900000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915f215b8c2feb99%3A0xaa740c5381d35771!2sGarabato%20cafe!5e0!3m2!1ses-419!2sbo!4v1756215138006!5m2!1ses-419!2sbo"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
