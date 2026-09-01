@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Categoría: {{ $categoria->nombre_categoria }}</h2>

    <form action="{{ route('categorias.update', $categoria->id_categoria) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Nombre de la Categoría</label>
            <input type="text" name="nombre_categoria" value="{{ old('nombre_categoria', $categoria->nombre_categoria) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">{{ old('descripcion', $categoria->descripcion) }}</textarea>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('categorias.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Actualizar Categoría</button>
        </div>
    </form>
</div>
@endsection