@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Repuesto: {{ $producto->nombre }}</h2>

    <form action="{{ route('productos.update', $producto->id_producto) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Código de Barra</label>
                <input type="text" name="codigo_barra" value="{{ old('codigo_barra', $producto->codigo_barra) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre del Repuesto</label>
                <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Categoría</label>
            <select name="id_categoria" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id_categoria }}" {{ $producto->id_categoria == $categoria->id_categoria ? 'selected' : '' }}>
                        {{ $categoria->nombre_categoria }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">{{ old('descripcion', $producto->descripcion) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Precio (CLP)</label>
                <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Stock Actual</label>
                <input type="number" name="stock" value="{{ old('stock', $producto->stock) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Stock Crítico</label>
                <input type="number" name="stock_critico" value="{{ old('stock_critico', $producto->stock_critico) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('productos.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Actualizar Producto</button>
        </div>
    </form>
</div>
@endsection