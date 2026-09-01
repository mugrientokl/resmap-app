<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return response()->json(Cliente::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'rut' => 'required|string|unique:clientes,rut',
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente = Cliente::create($request->all());

        return response()->json([
            'message' => 'Cliente registrado con éxito.',
            'cliente' => $cliente
        ], 201);
    }

    public function show($id)
    {
        $cliente = Cliente::with(['ventas', 'solicitudesWeb'])->findOrFail($id);
        return response()->json($cliente);
    }
}