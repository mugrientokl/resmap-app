@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Inventario de Repuestos y Maquinaria</h2>
        @if(auth()->user()->rol === 'Administrador')
            <a href="{{ route('productos.create') }}" class="bg-[#b52f25] text-white px-4 py-2 rounded-md hover:bg-[#8f241d] font-medium">+ Nuevo Repuesto</a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('productos.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-3">
        <input type="search" name="nombre" value="{{ request('nombre') }}" placeholder="Buscar por nombre o código" class="border-gray-300 rounded-md shadow-sm border p-2">
        <select name="categoria" class="border-gray-300 rounded-md shadow-sm border p-2">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id_categoria }}" @selected(request('categoria') == $categoria->id_categoria)>{{ $categoria->nombre_categoria }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Filtrar</button>
            <a href="{{ route('productos.index') }}" class="border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-50">Limpiar</a>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($productos as $producto)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ $producto->codigo_origen ?: $producto->codigo_barra }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $producto->nombre }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $producto->categoria->nombre_categoria ?? 'Sin Categoría' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$ {{ number_format($producto->precio, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($producto->stock <= $producto->stock_critico)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                {{ $producto->stock }} (Crítico)
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $producto->stock }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        @if(auth()->user()->rol === 'Administrador')
                            <a href="{{ route('productos.edit', $producto->id_producto) }}" class="text-[#9f2f25] hover:text-[#721d18]">Editar</a>
                            <form action="{{ route('productos.destroy', $producto->id_producto, absolute: false) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </form>
                        @endif
                        <a href="{{ route('productos.etiqueta', $producto->id_producto) }}" target="_blank" class="text-[#9f2f25] hover:text-[#721d18]">Etiqueta</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $productos->links() }}
    </div>
</div>
@endsection