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

    public function buscarPorCodigo(Request $request)
    {
        $codigo = $request->string('codigo')->trim()->toString();

        if ($codigo === '') {
            return response()->json(['message' => 'El código es obligatorio.'], 422);
        }

        $producto = Producto::where('codigo_barra', $codigo)
            ->orWhere('codigo_origen', $codigo)
            ->first();

        if (! $producto) {
            return response()->json(['message' => 'No se encontró un producto con ese código.'], 404);
        }

        return response()->json([
            'id_producto' => $producto->id_producto,
            'nombre' => $producto->nombre,
            'precio' => (float) $producto->precio,
            'stock' => $producto->stock,
        ]);
    }

    public function etiqueta(int $id)
    {
        $producto = Producto::with('categoria')->findOrFail($id);

        return view('productos.etiqueta', compact('producto'));
    }

    public function create()
    {
        $categorias = Categoria::all();

        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo_barra' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9._\/-]+$/', 'unique:productos,codigo_barra'],
            'nombre' => [
                'required',
                'string',
                'max:255',
                'not_regex:/^\d+$/',
            ],
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0|max:99999999.99',
            'stock' => 'required|integer|min:0|max:2147483647',
            'stock_critico' => 'required|integer|min:0|max:2147483647',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'imagen' => 'nullable|image|max:4096',
        ], [
            'nombre.not_regex' => 'El nombre del repuesto no puede contener únicamente números; debe incluir al menos una letra.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator)
                ->with('validation_error', $validator->errors()->first());
        }

        $datos = $request->except('imagen');
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        Producto::create($datos);

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
            'codigo_barra' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9._\/-]+$/', 'unique:productos,codigo_barra,'.$id.',id_producto'],
            'nombre' => [
                'required',
                'string',
                'max:255',
                'not_regex:/^\d+$/',
            ],
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0|max:99999999.99',
            'stock' => 'required|integer|min:0|max:2147483647',
            'stock_critico' => 'required|integer|min:0|max:2147483647',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'imagen' => 'nullable|image|max:4096',
        ], [
            'nombre.not_regex' => 'El nombre del repuesto no puede contener únicamente números; debe incluir al menos una letra.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator)
                ->with('validation_error', $validator->errors()->first());
        }

        $datos = $request->except('imagen');
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        $producto->update($datos);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado con éxito.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado con éxito.');
    }
}
