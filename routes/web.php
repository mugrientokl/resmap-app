<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SolicitudWebController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentaController;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::middleware([
    EncryptCookies::class,
    AddQueuedCookiesToResponse::class,
    StartSession::class,
    ShareErrorsFromSession::class,
    ValidateCsrfToken::class,
    SubstituteBindings::class,
])->group(function () {

    // Ruta principal de bienvenida o dashboard temporal
    Route::get('/', function () {
        return view('welcome');
    });

    // Rutas para Categorías
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/crear', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{id}/editar', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    // Rutas para Productos (Inventario de repuestos)
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/crear', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}/editar', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

    // Rutas para Clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/{id}/editar', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

    // Rutas para Ventas / POS (Boletas y Facturas DTE)
    Route::get('/pos', function (Request $request) {
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

        return view('pos.index', compact('productos', 'categorias'));
    });
    Route::post('/ventas', [VentaController::class, 'store']);

    // Rutas para Detalles de Venta
    Route::get('/detalle-ventas', [DetalleVentaController::class, 'index']);
    Route::get('/detalle-ventas/{id}', [DetalleVentaController::class, 'show']);

    // Rutas para Solicitudes Web (E-commerce / Carritos)
    Route::get('/solicitudes-web', [SolicitudWebController::class, 'index']);
    Route::post('/solicitudes-web', [SolicitudWebController::class, 'store']);
    Route::patch('/solicitudes-web/{id}/estado', [SolicitudWebController::class, 'actualizarEstado']);

    // Rutas para Usuarios
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::post('/usuarios', [UserController::class, 'store']);

});
