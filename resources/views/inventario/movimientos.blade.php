@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Historial de inventario</h1>
            <p class="text-sm text-gray-500">Cada cambio conserva el usuario, motivo y stock resultante.</p>
        </div>
        @if (session('success'))
            <div class="rounded-md bg-green-100 px-4 py-2 text-sm font-bold text-green-800">{{ session('success') }}</div>
        @endif
    </div>

    <form method="GET" class="grid grid-cols-1 gap-3 rounded-lg bg-white p-4 shadow-md md:grid-cols-3">
        <select name="producto" class="rounded-md border p-2">
            <option value="">Todos los productos</option>
            @foreach ($productos as $producto)
                <option value="{{ $producto->id_producto }}" @selected(request('producto') == $producto->id_producto)>{{ $producto->nombre }}</option>
            @endforeach
        </select>
        <select name="tipo" class="rounded-md border p-2">
            <option value="">Todos los movimientos</option>
            @foreach (['ingreso', 'ajuste', 'venta'] as $tipo)
                <option value="{{ $tipo }}" @selected(request('tipo') === $tipo)>{{ ucfirst($tipo) }}</option>
            @endforeach
        </select>
        <button class="rounded-md bg-[#b52f25] px-4 py-2 font-bold text-white hover:bg-[#8f241d]">Filtrar</button>
    </form>

    <form method="POST" action="{{ route('inventario.movimientos.store') }}" class="grid grid-cols-1 gap-3 rounded-lg border border-[#e8c8c3] bg-[#fff7f5] p-4 md:grid-cols-4">
        @csrf
        <select name="id_producto" required class="rounded-md border p-2">
            <option value="">Producto para ajustar</option>
            @foreach ($productos as $producto)
                <option value="{{ $producto->id_producto }}">{{ $producto->nombre }} ({{ $producto->stock }})</option>
            @endforeach
        </select>
        <input name="stock_nuevo" type="number" min="0" required placeholder="Stock nuevo" class="rounded-md border p-2">
        <input name="motivo" required maxlength="255" placeholder="Motivo del ajuste" class="rounded-md border p-2">
        <button class="rounded-md bg-[#b52f25] px-4 py-2 font-bold text-white">Registrar ajuste</button>
    </form>

    <div class="overflow-x-auto rounded-lg bg-white shadow-md">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-gray-100 text-xs uppercase text-gray-600"><tr><th class="px-4 py-3">Fecha</th><th class="px-4 py-3">Producto</th><th class="px-4 py-3">Tipo</th><th class="px-4 py-3">Cambio</th><th class="px-4 py-3">Stock</th><th class="px-4 py-3">Usuario</th><th class="px-4 py-3">Motivo</th></tr></thead>
            <tbody class="divide-y">
                @forelse ($movimientos as $movimiento)
                    <tr><td class="px-4 py-3">{{ $movimiento->created_at->format('d/m/Y H:i') }}</td><td class="px-4 py-3 font-semibold">{{ $movimiento->producto->nombre }}</td><td class="px-4 py-3">{{ ucfirst($movimiento->tipo) }}</td><td class="px-4 py-3 {{ $movimiento->cantidad < 0 ? 'text-red-700' : 'text-green-700' }}">{{ $movimiento->cantidad > 0 ? '+' : '' }}{{ $movimiento->cantidad }}</td><td class="px-4 py-3">{{ $movimiento->stock_anterior }} &rarr; {{ $movimiento->stock_nuevo }}</td><td class="px-4 py-3">{{ $movimiento->usuario?->name ?? 'Sistema' }}</td><td class="px-4 py-3">{{ $movimiento->motivo }}</td></tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Todavía no hay movimientos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $movimientos->links() }}
</div>
@endsection
