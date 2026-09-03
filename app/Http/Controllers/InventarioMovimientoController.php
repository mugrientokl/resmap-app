<?php

namespace App\Http\Controllers;

use App\Models\InventarioMovimiento;
use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioMovimientoController extends Controller
{
    public function index(Request $request)
    {
        $movimientos = InventarioMovimiento::with(['producto', 'usuario'])
            ->when($request->filled('producto'), function ($query) use ($request): void {
                $query->where('id_producto', $request->integer('producto'));
            })
            ->when($request->filled('tipo'), function ($query) use ($request): void {
                $query->where('tipo', $request->string('tipo')->toString());
            })
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('inventario.movimientos', [
            'movimientos' => $movimientos,
            'productos' => Producto::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_producto' => ['required', 'exists:productos,id_producto'],
            'stock_nuevo' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'motivo' => ['required', 'string', 'max:255'],
        ]);

        $producto = Producto::findOrFail($validated['id_producto']);
        $stockAnterior = $producto->stock;
        $stockNuevo = $validated['stock_nuevo'];
        $producto->update(['stock' => $stockNuevo]);

        InventarioMovimiento::create([
            'id_producto' => $producto->id_producto,
            'user_id' => $request->user()->id,
            'tipo' => 'ajuste',
            'cantidad' => $stockNuevo - $stockAnterior,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo' => $stockNuevo,
            'motivo' => $validated['motivo'],
        ]);

        return redirect()->route('inventario.movimientos')->with('success', 'Stock ajustado y movimiento registrado.');
    }
}
