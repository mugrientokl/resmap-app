@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Crear Nuevo Producto / Repuesto</h2>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <strong class="font-bold">¡Atención!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('productos.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Código de Barra</label>
            <input type="text" name="codigo_barra" value="{{ old('codigo_barra') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nombre del Repuesto</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Categoría</label>
            <select name="id_categoria" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">
                <option value="">Seleccione una categoría</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id_categoria }}" {{ old('id_categoria') == $cat->id_categoria ? 'selected' : '' }}>
                        {{ $cat->nombre_categoria }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">{{ old('descripcion') }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Precio</label>
                <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stock Inicial</label>
                <input type="number" name="stock" value="{{ old('stock') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stock Crítico</label>
                <input type="number" name="stock_critico" value="{{ old('stock_critico') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('productos.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Guardar Producto</button>
        </div>
    </form>
</div>
@endsection