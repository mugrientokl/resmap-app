<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo_barra' => 'required|string|unique:productos,codigo_barra',
            'nombre' => [
                'required',
                'string',
                'max:255',
                'not_regex:/^\d+$/',
            ],
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_critico' => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id_categoria',
        ], [
            'nombre.not_regex' => 'El nombre del repuesto no puede contener únicamente números; debe incluir al menos una letra.',
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')->with('success', 'Producto creado con éxito.');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'codigo_barra' => 'required|string|unique:productos,codigo_barra,' . $id . ',id_producto',
            'nombre' => [
                'required',
                'string',
                'max:255',
                'not_regex:/^\d+$/',
            ],
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_critico' => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id_categoria',
        ], [
            'nombre.not_regex' => 'El nombre del repuesto no puede contener únicamente números; debe incluir al menos una letra.',
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')->with('success', 'Producto actualizado con éxito.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado con éxito.');
    }
}