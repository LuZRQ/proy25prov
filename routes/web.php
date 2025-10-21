<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\OpinionController;
use Illuminate\Support\Facades\Http;
// Página pública
Route::get('/', [PublicController::class, 'index'])->name('home');

// Autenticación (login y logout)
Route::get('/ingresar', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/ingresar', [AuthenticatedSessionController::class, 'store']);
Route::post('/salir', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Registro
Route::get('/registro', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/registro', [RegisteredUserController::class, 'store']);

// Solo requiere que el usuario esté autenticado y sea Cliente
Route::middleware(['auth'])->group(function () {
    Route::post('/opiniones', [OpinionController::class, 'store'])
        ->name('opiniones.store')
        ->middleware('esCliente');

    Route::put('/opiniones', [OpinionController::class, 'update'])
        ->name('opiniones.update')
        ->middleware('esCliente');
});



// API Recetas de bebidas
Route::get('/bebidas', function () {
    $response = Http::get("https://www.thecocktaildb.com/api/json/v1/1/search.php?s=coffee");
    $bebidas = $response->json();
    return view('public.bebidas', compact('bebidas'));
});

// API Clima
Route::get('/clima', function () {
    $city = "La Paz,BO";
    $apiKey = "TU_API_KEY"; // reemplaza con tu key gratuita
    $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric");
    $clima = $response->json();
    return view('public.clima', compact('clima'));
});

require __DIR__.'/admin.php';
