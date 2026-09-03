<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\SolicitudWeb;
use App\Models\User;
use App\Notifications\SolicitudWebRecibida;
use App\Rules\RutChileno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogoController extends Controller
{
    public function home()
    {
        return view('welcome', [
            'categorias' => Categoria::withCount('productos')->orderByDesc('productos_count')->limit(6)->get(),
            'productos' => Producto::with('categoria')->orderByDesc('stock')->limit(8)->get(),
        ]);
    }

    public function index(Request $request)
    {
        $categorias = Categoria::withCount('productos')->orderBy('nombre_categoria')->get();
        $productos = Producto::with('categoria')
            ->when($request->filled('nombre'), fn ($query) => $query->where('nombre', 'like', '%'.$request->string('nombre')->toString().'%'))
            ->when($request->filled('categoria'), fn ($query) => $query->where('id_categoria', $request->integer('categoria')))
            ->orderBy('nombre')->paginate(24)->withQueryString();

        return view('catalogo.index', compact('categorias', 'productos'));
    }

    public function storeRequest(Request $request)
    {
        $data = $request->validate([
            'rut' => ['required', 'string', 'max:20', new RutChileno],
            'nombre' => ['required', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'detalles_productos' => ['required', 'array', 'min:1'],
            'detalles_productos.*.id_producto' => ['required', 'exists:productos,id_producto'],
            'detalles_productos.*.cantidad' => ['required', 'integer', 'min:1', 'max:2147483647'],
        ]);

        $solicitud = DB::transaction(function () use ($data): SolicitudWeb {
            $cliente = Cliente::updateOrCreate(['rut' => $data['rut']], [
                'nombre' => $data['nombre'], 'correo' => $data['correo'] ?? null,
                'telefono' => $data['telefono'] ?? null, 'direccion' => $data['direccion'] ?? null,
            ]);

            return SolicitudWeb::create([
                'fecha' => now(), 'estado' => 'Pendiente', 'id_cliente' => $cliente->id_cliente,
                'detalles_productos' => $data['detalles_productos'],
            ]);
        });

        User::whereIn('rol', ['Administrador', 'Vendedor'])->get()->each(fn (User $user) => $user->notify(new SolicitudWebRecibida($solicitud)));

        return redirect()->route('catalogo.index')->with('success', 'Solicitud enviada. El equipo de RESMAP se pondrá en contacto contigo.');
    }
}
