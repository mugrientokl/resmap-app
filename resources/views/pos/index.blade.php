@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Panel Izquierdo: Selección de Productos -->
    <div class="lg:col-span-2 bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Punto de Venta (POS) - Repuestos</h2>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Buscar Repuesto</label>
            <input type="text" id="buscador-producto" placeholder="Escribe el nombre o código del repuesto..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
        </div>

        <div class="overflow-x-auto max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="tabla-productos">
                    @foreach(\App\Models\Producto::all() as $prod)
                    <tr>
                        <td class="px-4 py-2 text-sm font-mono text-gray-600">{{ $prod->codigo_barra }}</td>
                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $prod->nombre }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">$ {{ number_format($prod->precio, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $prod->stock }}</td>
                        <td class="px-4 py-2 text-center text-sm">
                            <button type="button" onclick="agregarAlCarro({{ $prod->id_producto }}, '{{ addslashes($prod->nombre) }}', {{ $prod->precio }}, {{ $prod->stock }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs">Agregar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Panel Derecho: Cliente y Carro de Venta -->
    <div class="bg-white shadow-md rounded-lg p-6 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4">Datos del Cliente (Defontana)</h3>
            
            <div class="space-y-3 mb-6">
                <div>
                    <label class="block text-xs font-medium text-gray-700">RUT Empresa / Cliente</label>
                    <input type="text" id="rut" placeholder="12.345.678-9" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Razón Social / Nombre</label>
                    <input type="text" id="nombre_cliente" placeholder="Constructora SpA" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Correo</label>
                        <input type="email" id="correo_cliente" placeholder="contacto@empresa.cl" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Teléfono</label>
                        <input type="text" id="telefono_cliente" placeholder="+569..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Dirección</label>
                    <input type="text" id="direccion_cliente" placeholder="Faena / Dirección" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm">
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-2">Documento y Pago</h3>
            <div class="grid grid-cols-2 gap-2 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700">Documento SII</label>
                    <select id="tipo_documento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm">
                        <option value="Boleta Electrónica">Boleta (39)</option>
                        <option value="Factura Electrónica">Factura (33)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Medio de Pago</label>
                    <select id="medio_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Tarjeta">Tarjeta</option>
                    </select>
                </div>
            </div>

            <h3 class="text-md font-bold text-gray-800 mb-2">Ítems Seleccionados</h3>
            <div class="max-h-40 overflow-y-auto mb-4 border rounded p-2 bg-gray-50">
                <table class="min-w-full text-xs" id="tabla-carro">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left pb-1">Repuesto</th>
                            <th class="text-center pb-1">Cant</th>
                            <th class="text-right pb-1">Subtotal</th>
                            <th class="text-center pb-1"></th>
                        </tr>
                    </thead>
                    <tbody id="carro-items">
                        <!-- Dinámico -->
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <div class="border-t pt-3 mb-4 space-y-1 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Neto:</span>
                    <span id="label-neto">$ 0</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>IVA (19%):</span>
                    <span id="label-iva">$ 0</span>
                </div>
                <div class="flex justify-between font-bold text-gray-900 text-base">
                    <span>Total Total:</span>
                    <span id="label-total">$ 0</span>
                </div>
            </div>

            <button type="button" onclick="procesarVenta()" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 font-bold text-center block">
                Emitir Documento SII
            </button>
        </div>
    </div>
</div>

<script>
    let carro = [];

    function agregarAlCarro(id_producto, nombre, precio, stockMax) {
        let existente = carro.find(item => item.id_producto === id_producto);
        if (existente) {
            if (existente.cantidad < stockMax) {
                existente.cantidad++;
            } else {
                alert('Stock máximo alcanzado.');
            }
        } else {
            carro.push({ id_producto, nombre, precio, cantidad: 1, stockMax });
        }
        renderCarro();
    }

    function cambiarCantidad(id_producto, delta) {
        let item = carro.find(i => i.id_producto === id_producto);
        if (item) {
            item.cantidad += delta;
            if (item.cantidad <= 0) {
                carro = carro.filter(i => i.id_producto !== id_producto);
            } else if (item.cantidad > item.stockMax) {
                item.cantidad = item.stockMax;
                alert('Stock máximo alcanzado.');
            }
        }
        renderCarro();
    }

    function renderCarro() {
        let tbody = document.getElementById('carro-items');
        tbody.innerHTML = '';
        let totalGeneral = 0;

        carro.forEach(item => {
            let subtotal = item.precio * item.cantidad;
            totalGeneral += subtotal;

            tbody.innerHTML += `
                <tr class="border-b">
                    <td class="py-1">${item.nombre}</td>
                    <td class="py-1 text-center">
                        <button type="button" onclick="cambiarCantidad(${item.id_producto}, -1)" class="px-1 bg-gray-200 rounded">-</button>
                        <span class="mx-1">${item.cantidad}</span>
                        <button type="button" onclick="cambiarCantidad(${item.id_producto}, 1)" class="px-1 bg-gray-200 rounded">+</button>
                    </td>
                    <td class="py-1 text-right">$ ${subtotal.toLocaleString('es-CL')}</td>
                    <td class="py-1 text-center">
                        <button type="button" onclick="cambiarCantidad(${item.id_producto}, -${item.cantidad})" class="text-red-500 font-bold">×</button>
                    </td>
                </tr>
            `;
        });

        let neto = Math.round(totalGeneral / 1.19);
        let iva = totalGeneral - neto;

        document.getElementById('label-neto').innerText = '$ ' + neto.toLocaleString('es-CL');
        document.getElementById('label-iva').innerText = '$ ' + iva.toLocaleString('es-CL');
        document.getElementById('label-total').innerText = '$ ' + totalGeneral.toLocaleString('es-CL');
    }

    function procesarVenta() {
        if (carro.length === 0) {
            alert('El carrito está vacío.');
            return;
        }

        let rut = document.getElementById('rut').value;
        let nombre_cliente = document.getElementById('nombre_cliente').value;

        if (!rut || !nombre_cliente) {
            alert('Debe ingresar al menos el RUT y Nombre del cliente.');
            return;
        }

        let datos = {
            tipo_documento: document.getElementById('tipo_documento').value,
            medio_pago: document.getElementById('medio_pago').value,
            user_id: 1, // Usuario por defecto temporal
            rut: rut,
            nombre_cliente: nombre_cliente,
            correo_cliente: document.getElementById('correo_cliente').value,
            telefono_cliente: document.getElementById('telefono_cliente').value,
            direccion_cliente: document.getElementById('direccion_cliente').value,
            detalles: carro.map(i => ({ id_producto: i.id_producto, cantidad: i.cantidad }))
        };

        fetch('/ventas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(datos)
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 201) {
                alert('¡Venta emitida y cliente sincronizado con éxito!');
                window.location.reload();
            } else {
                alert('Error: ' + (res.body.error || JSON.stringify(res.body.errors)));
            }
        })
        .catch(err => console.error('Error:', err));
    }
</script>
@endsection