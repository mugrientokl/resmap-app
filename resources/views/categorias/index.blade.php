@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Categorías de Repuestos</h2>
        <button onclick="openCreateModal()" class="bg-[#b52f25] text-white px-4 py-2 rounded-md hover:bg-[#8f241d] font-medium">+ Nueva Categoría</button>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    @if(session('validation_error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <p class="font-medium">No se pudo guardar la categoría:</p>
            <p>{{ session('validation_error') }}</p>
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
                            class="text-[#9f2f25] hover:text-[#721d18]">Editar</button>
                        
                        <form action="{{ route('categorias.destroy', $cat->id_categoria, absolute: false) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
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

    <div class="mt-6">
        {{ $categorias->links() }}
    </div>
</div>

<!-- MODAL CREAR CATEGORÍA -->
<div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden">
        <form action="{{ route('categorias.store', absolute: false) }}" method="POST" onsubmit="return validateCategoryForm(this)">
            @csrf
            <input type="hidden" name="form_type" value="create">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Nueva Categoría</h3>
                <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>

            <div class="category-validation-error hidden mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md" role="alert">
                <p class="font-medium">No se puede guardar la categoría</p>
                <p class="validation-message text-sm mt-1"></p>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label for="nombre_categoria" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Categoría</label>
                    <input type="text" id="nombre_categoria" name="nombre_categoria" value="{{ old('nombre_categoria') }}" 
                        class="w-full px-3 py-2 border @if($errors->has('nombre_categoria') && old('form_type') != 'edit') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    
                    @if($errors->has('nombre_categoria') && old('form_type') != 'edit')
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('nombre_categoria') }}</p>
                    @endif
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción (Opcional)</label>
                    <textarea id="descripcion" name="descripcion" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-2">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm font-medium">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-[#b52f25] text-white rounded-md hover:bg-[#8f241d] text-sm font-medium">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR CATEGORÍA -->
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden">
        <form id="editForm" method="POST" onsubmit="return validateCategoryForm(this)">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit">
            <input type="hidden" id="edit_categoria_id" name="categoria_id" value="{{ old('categoria_id') }}">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Editar Categoría</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>

            <div class="category-validation-error hidden mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md" role="alert">
                <p class="font-medium">No se puede actualizar la categoría</p>
                <p class="validation-message text-sm mt-1"></p>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label for="edit_nombre_categoria" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Categoría</label>
                    <input type="text" id="edit_nombre_categoria" name="nombre_categoria" value="{{ old('nombre_categoria') }}"
                        class="w-full px-3 py-2 border @if($errors->has('nombre_categoria') && old('form_type') == 'edit') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    
                    @if($errors->has('nombre_categoria') && old('form_type') == 'edit')
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('nombre_categoria') }}</p>
                    @endif
                </div>

                <div>
                    <label for="edit_descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción (Opcional)</label>
                    <textarea id="edit_descripcion" name="descripcion" rows="3" 
                        class="w-full px-3 py-2 border @if($errors->has('descripcion') && old('form_type') == 'edit') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('descripcion') }}</textarea>
                    @if($errors->has('descripcion') && old('form_type') == 'edit')
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('descripcion') }}</p>
                    @endif
                </div>
            </div>

            <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm font-medium">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-[#b52f25] text-white rounded-md hover:bg-[#8f241d] text-sm font-medium">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts de control -->
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

    function validateCategoryForm(form) {
        let categoryName = form.querySelector('input[name="nombre_categoria"]').value.trim();
        let validationError = form.querySelector('.category-validation-error');
        let validationMessage = validationError.querySelector('.validation-message');

        validationError.classList.add('hidden');
        validationMessage.textContent = '';

        if (!categoryName) {
            showCategoryValidationError(validationError, validationMessage, 'El nombre de la categoría es obligatorio.');
            return false;
        }

        if (/^\d+$/.test(categoryName)) {
            showCategoryValidationError(validationError, validationMessage, 'El nombre de la categoría no puede contener únicamente números; debe incluir al menos una letra.');
            return false;
        }

        return true;
    }

    function showCategoryValidationError(validationError, validationMessage, message) {
        validationMessage.textContent = message;
        validationError.classList.remove('hidden');
    }

    document.addEventListener("DOMContentLoaded", function() {
        @if (session('validation_error') || $errors->any())
            @if(old('form_type') == 'edit')
                let editCategoriaId = document.getElementById('edit_categoria_id').value;
                if (editCategoriaId) {
                    document.getElementById('editForm').action = "/categorias/" + editCategoriaId;
                }
                document.getElementById('editModal').classList.remove('hidden');
            @else
                openCreateModal();
            @endif
        @endif
    });
</script>
@endsection