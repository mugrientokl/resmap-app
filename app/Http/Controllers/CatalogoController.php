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
            'rut' => ['required', 'string', 'max:20', 'regex:/^[0-9]{7,8}-[0-9Kk]$/', new RutChileno],
            'nombre' => ['required', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:255'],
            'telefono' => ['required', 'regex:/^(?:\+?569)?[0-9]{8}$/'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'detalles_productos' => ['required', 'array', 'min:1'],
            'detalles_productos.*.id_producto' => ['required', 'exists:productos,id_producto'],
            'detalles_productos.*.cantidad' => ['required', 'integer', 'min:1', 'max:99'],
        ], [
            'rut.required' => 'Escribe tu RUT sin puntos y con guion, por ejemplo: 12345678-5.',
            'rut.regex' => 'El RUT debe escribirse sin puntos y con guion, por ejemplo: 12345678-5.',
            'nombre.required' => 'Escribe tu nombre o razón social.',
            'correo.email' => 'Escribe un correo electrónico válido.',
            'telefono.required' => 'Escribe los 8 dígitos de tu teléfono después de +569.',
            'telefono.regex' => 'El teléfono debe contener 8 dígitos después de +569.',
            'detalles_productos.required' => 'Agrega al menos un repuesto al carrito.',
            'detalles_productos.min' => 'Agrega al menos un repuesto al carrito.',
        ]);

        $telefono = preg_replace('/[^0-9]/', '', $data['telefono']);
        $telefono = str_starts_with($telefono, '569') ? substr($telefono, 3) : $telefono;

        $solicitud = DB::transaction(function () use ($data, $telefono): SolicitudWeb {
            $cliente = Cliente::updateOrCreate(['rut' => $data['rut']], [
                'nombre' => $data['nombre'], 'correo' => $data['correo'] ?? null,
                'telefono' => '+569'.$telefono, 'direccion' => $data['direccion'] ?? null,
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
