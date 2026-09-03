@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-8"><p class="text-sm font-bold uppercase tracking-wider text-indigo-600">Atención comercial</p><h1 class="mt-1 text-3xl font-black text-gray-900">Solicitudes web</h1><p class="mt-2 text-gray-500">Pedidos recibidos desde el catálogo público.</p></div>
    <div class="space-y-4">
        @forelse($solicitudes as $solicitud)
            <article class="bg-white p-6 shadow-sm"><div class="flex flex-col justify-between gap-4 md:flex-row md:items-start"><div><div class="flex items-center gap-3"><h2 class="text-lg font-black">Solicitud #{{ $solicitud->id_solicitud }}</h2><span class="rounded-full bg-[#f7e8e6] px-3 py-1 text-xs font-bold text-[#9f2f25]">{{ $solicitud->estado }}</span></div><p class="mt-2 text-sm text-gray-600">{{ $solicitud->cliente->nombre }} · {{ $solicitud->cliente->rut }} · {{ $solicitud->cliente->correo ?: 'Sin correo' }}</p><p class="mt-1 text-xs text-gray-400">{{ $solicitud->fecha->format('d/m/Y H:i') }}</p></div><a href="{{ route('solicitudes.show', $solicitud->id_solicitud) }}" class="rounded-md bg-[#b52f25] px-4 py-2 text-sm font-bold text-white">Ver detalle</a></div><div class="mt-4 border-t pt-4 text-sm text-gray-600">{{ count($solicitud->detalles_productos) }} producto(s) solicitado(s)</div></article>
        @empty
            <div class="bg-white p-8 text-center text-gray-500">No hay solicitudes recibidas.</div>
        @endforelse
    </div>
    <div class="mt-6">{{ $solicitudes->links() }}</div>
</div>
@endsection