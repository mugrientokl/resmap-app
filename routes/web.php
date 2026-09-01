<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\SolicitudWebController;
use App\Http\Controllers\UserController;

// Ruta principal de bienvenida o dashboard temporal
Route::get('/', function () {
    return view('welcome');
});

// Rutas para Categorías
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::post('/categorias', [CategoriaController::class, 'store']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);

// Rutas para Productos (Inventario de repuestos)
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/crear', [ProductoController::class, 'create'])->name('productos.create');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::get('/productos/{id}/editar', [ProductoController::class, 'edit'])->name('productos.edit');
Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

// Rutas para Clientes
Route::get('/clientes', [ClienteController::class, 'index']);
Route::post('/clientes', [ClienteController::class, 'store']);
Route::get('/clientes/{id}', [ClienteController::class, 'show']);

// Rutas para Ventas / POS (Boletas y Facturas DTE)
Route::get('/pos', function () {
    return view('pos.index');
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