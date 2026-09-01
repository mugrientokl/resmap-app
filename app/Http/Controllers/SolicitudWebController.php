<?php

namespace App\Http\Controllers;

use App\Models\SolicitudWeb;
use Illuminate\Http\Request;

class SolicitudWebController extends Controller
{
    public function index()
    {
        return response()->json(SolicitudWeb::with('cliente')->get());
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
            'solicitud' => $solicitud
        ], 201);
    }

    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|string|in:Pendiente,Aprobado,Rechazado'
        ]);

        $solicitud = SolicitudWeb::findOrFail($id);
        $solicitud->update(['estado' => $request->estado]);

        return response()->json([
            'message' => 'Estado de la solicitud actualizado.',
            'solicitud' => $solicitud
        ]);
    }
}