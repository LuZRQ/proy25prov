<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\CategoriaProducto;
use App\Models\Calificacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Str;
class PublicController extends Controller
{

    public function index()
    {
        $productos = Producto::activos()->with('categoria')->get();
        $categorias = CategoriaProducto::all();

        $opiniones = Calificacion::with('usuario')
            ->orderBy('fecha', 'desc')
            ->take(5)
            ->get();

        $total = Calificacion::count();
        $ratings = [];
        if ($total > 0) {
            $ratings = [
                [
                    'label' => 'Excelente',
                    'value' => round((Calificacion::where('calificacion', 5)->count() / $total) * 100)
                ],
                [
                    'label' => 'Bueno',
                    'value' => round((Calificacion::where('calificacion', 4)->count() / $total) * 100)
                ],
                [
                    'label' => 'Regular',
                    'value' => round((Calificacion::where('calificacion', 3)->count() / $total) * 100)
                ],
                [
                    'label' => 'Malo',
                    'value' => round((Calificacion::where('calificacion', '<=', 2)->count() / $total) * 100)
                ],
            ];
        }

        $usuario = Auth::user();
        $yaOpino = null;
        if ($usuario) {
            $yaOpino = Calificacion::where('ciUsuario', $usuario->ciUsuario)->first();
        }
  // ===============================
        // 🌤️ CLIMA EN LA PAZ
        // ===============================
        $city = "La Paz,BO";
        $apiKey = env('OPENWEATHER_KEY', '5db67398d7c50620c78b335b160e3514');
        $clima = null;

        try {
            $response = Http::timeout(5)->get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'es'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $descripcion = strtolower($data['weather'][0]['description'] ?? 'No disponible');

                // 🔸 Mensaje personalizado según descripción
                $mensaje = "Disfruta tu día con un buen café ";
                $emoji = "☕";

                if (str_contains($descripcion, 'sol') || str_contains($descripcion, 'despejado')) {
                    $mensaje = "Es un día soleado ¡Ven a Garabato Café y prueba algo frío!";
                    $icono = "fa-sun text-warning";
                } elseif (str_contains($descripcion, 'lluvia')) {
                    $mensaje = "Parece que lloverá ☔ ¡Trae tu paraguas y disfruta un café caliente!";
                    $icono = "fa-cloud-showers-heavy text-primary";
                } elseif (str_contains($descripcion, 'muy nuboso')) {
                    $mensaje = "El cielo está nublado, ideal para una charla con café ☕";
                    $icono = "fa-cloud text-secondary";
                } elseif (str_contains($descripcion, 'nieve')) {
                    $mensaje = "Hace frío ❄️ ¡Nada mejor que un chocolate caliente en Garabato Café!";
                    $icono = "fa-snowflake text-info";
                } else {
                    $mensaje = "Disfruta tu día con un buen café ☕";
                    $icono = "fa-mug-hot text-danger";
                }

                $clima = [
                    'temp' => $data['main']['temp'] ?? null,
                    'desc' => ucfirst($data['weather'][0]['description'] ?? 'No disponible'),
                    'icon' => $data['weather'][0]['icon'] ?? null,
                    'mensaje' => $mensaje,
                    'icono' => $icono,
                ];
            }
        } catch (\Exception $e) {
            $clima = null;
        }

        // ===============================
        // ☕ Trivia del Café con traducción
        // ===============================
        $triviaCafe = null;

        try {
            $resTrivia = Http::timeout(5)->get('https://opentdb.com/api.php', [
                'amount' => 1,
                'lang' => 'es',          // pedimos en español
                'category' => 17,        // Cultura General
                'difficulty' => 'easy',  // fácil
            ]);

            if ($resTrivia->successful()) {
                $data = $resTrivia->json();
                $preguntaOriginal = html_entity_decode($data['results'][0]['question'] ?? 'Pregunta no disponible');
                $respuestaOriginal = html_entity_decode($data['results'][0]['correct_answer'] ?? 'Respuesta no disponible');

                // Traducción automática (por si la API devuelve inglés)
                $tr = new GoogleTranslate('es');
                $preguntaEs = $tr->translate($preguntaOriginal);
                $respuestaEs = $tr->translate($respuestaOriginal);

                $triviaCafe = [
                    'pregunta' => $preguntaOriginal,
                    'respuesta' => $respuestaOriginal,
                    'pregunta_es' => $preguntaEs,
                    'respuesta_es' => $respuestaEs,
                ];
            }
        } catch (\Exception $e) {
            $triviaCafe = null;
        }

       // ===============================
// 🍹 BEBIDA RECOMENDADA (desde base de datos)
// ===============================
$bebidaRecomendada = null;

try {
    // Trae productos activos con imagen
    $productos = \App\Models\Producto::activos()
        ->whereNotNull('imagen')
        ->where('imagen', '!=', '')
        ->get();

    if ($productos->count() > 0) {
        // Escoge uno al azar
        $p = $productos->random();

        $bebidaRecomendada = [
            'nombre' => $p->nombre,
            'categoria' => $p->categoria?->nombreCategoria ?? 'Sin categoría',
            'ingrediente' => $p->descripcion ? Str::limit($p->descripcion, 60) : 'Perfecta para cualquier momento del día',
            'imagen' => asset('storage/' . $p->imagen),
            'precio' => $p->precio,
        ];
    }
} catch (\Exception $e) {
    $bebidaRecomendada = null;
}

        // ===============================
        // RENDERIZAR VISTA
        // ===============================
        return view('publico.index', compact(
            'productos',
            'categorias',
            'ratings',
            'opiniones',
            'usuario',
            'yaOpino',
            'clima',
            'bebidaRecomendada',
            'triviaCafe'
        ));
    }
}
