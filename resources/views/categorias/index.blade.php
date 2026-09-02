@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Categorías de Repuestos</h2>
        <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-medium">+ Nueva Categoría</button>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre de Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($categorias as $cat)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cat->id_categoria }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cat->nombre_categoria }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cat->descripcion ?? 'Sin descripción' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <button onclick="openEditModal('{{ $cat->id_categoria }}', '{{ addslashes($cat->nombre_categoria) }}', '{{ addslashes($cat->descripcion ?? '') }}')" 
                            class="text-indigo-600 hover:text-indigo-900">Editar</button>
                        
                        <form action="{{ route('categorias.destroy', $cat->id_categoria) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL CREAR CATEGORÍA -->
<div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden">
        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Nueva Categoría</h3>
                <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label for="nombre_categoria" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Categoría</label>
                    <input type="text" id="nombre_categoria" name="nombre_categoria" value="{{ old('nombre_categoria') }}" 
                        class="w-full px-3 py-2 border @error('nombre_categoria', 'create') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    
                    @error('nombre_categoria', 'create')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción (Opcional)</label>
                    <textarea id="descripcion" name="descripcion" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-2">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm font-medium">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR CATEGORÍA -->
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Editar Categoría</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label for="edit_nombre_categoria" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Categoría</label>
                    <input type="text" id="edit_nombre_categoria" name="nombre_categoria" value="{{ old('nombre_categoria') }}"
                        class="w-full px-3 py-2 border @error('nombre_categoria', 'edit') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    
                    @error('nombre_categoria', 'edit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="edit_descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción (Opcional)</label>
                    <textarea id="edit_descripcion" name="descripcion" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm font-medium">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts de control y validación automática de modales -->
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(id, nombre, descripcion) {
        document.getElementById('editForm').action = "/categorias/" + id;
        document.getElementById('edit_nombre_categoria').value = nombre;
        document.getElementById('edit_descripcion').value = descripcion;
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    document.addEventListener("DOMContentLoaded", function() {
        @if ($errors->create->any())
            openCreateModal();
        @endif

        @if ($errors->edit->any())
            let failedId = "{{ session('edit_id') }}";
            let oldNombre = "{{ old('nombre_categoria') }}";
            let oldDesc = "{{ old('descripcion') }}";
            if (failedId) {
                openEditModal(failedId, oldNombre, oldDesc);
            }
        @endif
    });
</script>
@endsection