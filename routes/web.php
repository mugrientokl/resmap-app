<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\NotificacionController;
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
])->group(function (): void {
    Route::get('/', [CatalogoController::class, 'home'])->name('home');
    Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
    Route::post('/catalogo/solicitudes', [CatalogoController::class, 'storeRequest'])->name('catalogo.solicitudes.store');
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
    Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->middleware('auth')->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leer', [NotificacionController::class, 'read'])->middleware('auth')->name('notificaciones.read');

    // Rutas para Categorías
    Route::get('/categorias', [CategoriaController::class, 'index'])->middleware(['auth', 'role:Administrador'])->name('categorias.index');
    Route::get('/categorias/crear', [CategoriaController::class, 'create'])->middleware(['auth', 'role:Administrador'])->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->middleware(['auth', 'role:Administrador'])->name('categorias.store');
    Route::get('/categorias/{id}/editar', [CategoriaController::class, 'edit'])->middleware(['auth', 'role:Administrador'])->name('categorias.edit');
    Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->middleware(['auth', 'role:Administrador'])->name('categorias.update');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->middleware(['auth', 'role:Administrador'])->name('categorias.destroy');

    // Rutas para Productos (Inventario de repuestos)
    Route::get('/productos', [ProductoController::class, 'index'])->middleware(['auth', 'role:Administrador,Vendedor'])->name('productos.index');
    Route::get('/productos/crear', [ProductoController::class, 'create'])->middleware(['auth', 'role:Administrador'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->middleware(['auth', 'role:Administrador'])->name('productos.store');
    Route::get('/productos/{id}/editar', [ProductoController::class, 'edit'])->middleware(['auth', 'role:Administrador'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->middleware(['auth', 'role:Administrador'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->middleware(['auth', 'role:Administrador'])->name('productos.destroy');

    // Rutas para Clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->middleware(['auth', 'role:Administrador'])->name('clientes.index');
    Route::get('/clientes/{id}/editar', [ClienteController::class, 'edit'])->middleware(['auth', 'role:Administrador'])->name('clientes.edit');
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])->middleware(['auth', 'role:Administrador'])->name('clientes.update');
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->middleware(['auth', 'role:Administrador'])->name('clientes.destroy');

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
    })->middleware(['auth', 'role:Administrador,Vendedor'])->name('pos.index');
    Route::post('/ventas', [VentaController::class, 'store'])->middleware(['auth', 'role:Administrador,Vendedor']);

    // Rutas para Detalles de Venta
    Route::get('/detalle-ventas', [DetalleVentaController::class, 'index'])->middleware(['auth', 'role:Administrador']);
    Route::get('/detalle-ventas/{id}', [DetalleVentaController::class, 'show'])->middleware(['auth', 'role:Administrador']);

    // Rutas para Solicitudes Web (E-commerce / Carritos)
    Route::get('/solicitudes-web', [SolicitudWebController::class, 'index'])->middleware(['auth', 'role:Administrador,Vendedor'])->name('solicitudes.index');
    Route::patch('/solicitudes-web/{id}/estado', [SolicitudWebController::class, 'actualizarEstado'])->middleware(['auth', 'role:Administrador,Vendedor'])->name('solicitudes.estado');

    // Rutas para Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->middleware(['auth', 'role:Administrador']);
    Route::post('/usuarios', [UserController::class, 'store'])->middleware(['auth', 'role:Administrador']);
});
