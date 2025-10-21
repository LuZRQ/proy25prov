<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calificacion;
use Illuminate\Support\Facades\Auth;

class OpinionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        $comentarioSeguro = strip_tags($request->input('comentario'));
        $comentarioSeguro = substr($comentarioSeguro, 0, 500);

        Calificacion::create([
            'ciUsuario' => Auth::user()->ciUsuario,
            'calificacion' => $request->rating,
            'comentario' => $comentarioSeguro,
            'fecha' => now(),
        ]);

        return redirect()->back()->with('success', '¡Gracias por tu opinión!');
    }

    public function update(Request $request)
    {
        $usuario = Auth::user();
        $calificacion = Calificacion::where('ciUsuario', $usuario->ciUsuario)->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        $comentarioSeguro = strip_tags($request->input('comentario'));
        $comentarioSeguro = substr($comentarioSeguro, 0, 500);

        $calificacion->update([
            'calificacion' => $request->rating,
            'comentario' => $comentarioSeguro,
            'fecha' => now(),
        ]);

        return redirect()->back()->with('success', '¡Opinión actualizada!');
    }
}
