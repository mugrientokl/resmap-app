<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $categorias = Categoria::orderBy('nombre_categoria')->get();
        $productos = Producto::with('categoria')
            ->when($request->filled('nombre'), function ($query) use ($request): void {
                $nombre = $request->string('nombre')->toString();
                $query->where(function ($productQuery) use ($nombre): void {
                    $productQuery->where('nombre', 'like', "%{$nombre}%")
                        ->orWhere('codigo_barra', 'like', "%{$nombre}%")
                        ->orWhere('codigo_origen', 'like', "%{$nombre}%");
                });
            })
            ->when($request->filled('categoria'), function ($query) use ($request): void {
                $query->where('id_categoria', $request->integer('categoria'));
            })
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::all();

        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator)
                ->with('validation_error', $validator->errors()->first());
        }

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

        $validator = Validator::make($request->all(), [
            'codigo_barra' => 'required|string|unique:productos,codigo_barra,'.$id.',id_producto',
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

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator)
                ->with('validation_error', $validator->errors()->first());
        }

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
