<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\SolicitudWeb;
use Illuminate\Http\Request;

class SolicitudWebController extends Controller
{
    public function index(Request $request)
    {
        $solicitudes = SolicitudWeb::with('cliente')
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->string('estado')->toString()))
            ->when($request->filled('desde'), fn ($query) => $query->whereDate('fecha', '>=', $request->date('desde')))
            ->when($request->filled('hasta'), fn ($query) => $query->whereDate('fecha', '<=', $request->date('hasta')))
            ->latest('fecha')
            ->paginate(20)
            ->withQueryString();

        return request()->expectsJson()
            ? response()->json($solicitudes)
            : view('solicitudes.index', compact('solicitudes'));
    }

    public function show($id)
    {
        $solicitud = SolicitudWeb::with('cliente')->findOrFail($id);
        $productos = collect($solicitud->detalles_productos)->map(function (array $detalle) {
            return [
                'producto' => Producto::find($detalle['id_producto']),
                'cantidad' => $detalle['cantidad'],
            ];
        });

        return view('solicitudes.show', compact('solicitud', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'detalles_productos' => 'required|array|min:1',
        ]);

        $solicitud = SolicitudWeb::create([
            'fecha' => now(),
            'estado' => 'Pendiente',
            'id_cliente' => $request->id_cliente,
            'detalles_productos' => $request->detalles_productos,
        ]);

        return response()->json([
            'message' => 'Solicitud web registrada con éxito.',
            'solicitud' => $solicitud,
        ], 201);
    }

    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|string|in:Pendiente,Pendiente de pago,Pagado,Entregado,Rechazado',
            'observaciones' => 'nullable|string|max:2000',
        ]);

        $solicitud = SolicitudWeb::findOrFail($id);
        $solicitud->update([
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
            'atendida_at' => $request->estado === 'Entregado' ? now() : $solicitud->atendida_at,
        ]);

        if (! $request->expectsJson()) {
            return redirect()->route('solicitudes.show', $solicitud)->with('success', 'Estado de la solicitud actualizado.');
        }

        return response()->json([
            'message' => 'Estado de la solicitud actualizado.',
            'solicitud' => $solicitud,
        ]);
    }
}
