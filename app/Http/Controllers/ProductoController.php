<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Listar todos los productos con su respectiva categoría
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        
        // De momento lo retornamos en formato JSON para verificar datos rápidamente, 
        // luego lo conectaremos a las vistas Blade.
        return response()->json($productos);
    }
}