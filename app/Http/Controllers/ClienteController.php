<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Rules\RutChileno;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->paginate(20)->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'rut' => ['required', 'string', 'max:20', new RutChileno, 'unique:clientes,rut,'.$id.',id_cliente'],
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente->update($request->only(['rut', 'nombre', 'correo', 'telefono', 'direccion']));

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado con éxito.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado con éxito.');
    }
}
