@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl">
    <a href="{{ route('solicitudes.index') }}" class="text-sm font-bold text-[#9f2f25]">Volver a solicitudes</a>
    @if(session('success'))<div class="mt-5 border-l-4 border-[#b52f25] bg-white p-4 font-semibold">{{ session('success') }}</div>@endif
    <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_1.4fr]">
        <section class="bg-white p-6 shadow-sm">
            <p class="text-sm font-bold uppercase tracking-wider text-[#9f2f25]">Solicitud #{{ $solicitud->id_solicitud }}</p>
            <h1 class="mt-2 text-3xl font-black">{{ $solicitud->tipo_solicitud === 'servicio' ? 'Detalle del servicio' : 'Detalle del pedido' }}</h1>
            <dl class="mt-8 space-y-4 text-sm">
                <div><dt class="font-bold text-gray-500">Cliente</dt><dd>{{ $solicitud->cliente->nombre }}</dd></div>
                <div><dt class="font-bold text-gray-500">RUT</dt><dd>{{ $solicitud->cliente->rut }}</dd></div>
                <div><dt class="font-bold text-gray-500">Correo</dt><dd>{{ $solicitud->cliente->correo ?: 'No informado' }}</dd></div>
                <div><dt class="font-bold text-gray-500">Teléfono</dt><dd>{{ $solicitud->cliente->telefono ?: 'No informado' }}</dd></div>
                <div><dt class="font-bold text-gray-500">Dirección</dt><dd>{{ $solicitud->cliente->direccion ?: 'No informada' }}</dd></div>
            </dl>
        </section>
        <section class="bg-white p-6 shadow-sm">
            @if($solicitud->tipo_solicitud === 'servicio')
                <h2 class="text-xl font-black">{{ $solicitud->tipo_servicio }}</h2>
                <p class="mt-5 whitespace-pre-line border-l-4 border-[#b52f25] bg-[#f7e8e6] p-4 text-gray-700">{{ $solicitud->descripcion_servicio }}</p>
            @else
                <h2 class="text-xl font-black">Repuestos solicitados</h2>
                <div class="mt-5 divide-y">
                    @foreach($productos as $item)
                        <div class="flex items-center justify-between py-4">
                            <div class="flex items-center gap-4">
                                @if($item['producto']?->imagen)<img src="{{ asset('storage/'.$item['producto']->imagen) }}" class="h-14 w-14 object-cover" alt="">@endif
                                <div><p class="font-bold">{{ $item['producto']->nombre ?? 'Producto no encontrado' }}</p><p class="text-sm text-gray-500">Código: {{ $item['producto']->codigo_origen ?? 'Sin código' }}</p></div>
                            </div>
                            <span class="font-black">x{{ $item['cantidad'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('solicitudes.estado', $solicitud->id_solicitud) }}" class="mt-6 border-t pt-6">
                @csrf @method('PATCH')
                <label class="block text-sm font-bold">Estado de la solicitud<select name="estado" class="mt-2 w-full border border-gray-300 p-3"><option @selected($solicitud->estado === 'Pendiente')>Pendiente</option><option @selected($solicitud->estado === 'Pendiente de pago')>Pendiente de pago</option><option @selected($solicitud->estado === 'Pagado')>Pagado</option><option @selected($solicitud->estado === 'Entregado')>Entregado</option><option @selected($solicitud->estado === 'Rechazado')>Rechazado</option></select></label>
                <label class="mt-4 block text-sm font-bold">Observaciones<textarea name="observaciones" rows="3" class="mt-2 w-full border border-gray-300 p-3">{{ $solicitud->observaciones }}</textarea></label>
                <button class="mt-4 bg-[#b52f25] px-5 py-3 font-bold text-white">Guardar estado</button>
            </form>
        </section>
    </div>
</div>
@endsection
