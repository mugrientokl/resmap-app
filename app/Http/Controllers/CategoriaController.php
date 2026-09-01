<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with('productos')->get();
        return response()->json($categorias);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_categoria' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $categoria = Categoria::create([
            'nombre_categoria' => $request->nombre_categoria,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json([
            'message' => 'Categoría creada con éxito.',
            'categoria' => $categoria
        ], 201);
    }

    public function show($id)
    {
        $categoria = Categoria::with('productos')->findOrFail($id);
        return response()->json($categoria);
    }
}