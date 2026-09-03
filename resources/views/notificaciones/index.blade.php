@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-8"><p class="text-sm font-bold uppercase tracking-wider text-indigo-600">Centro de avisos</p><h1 class="mt-1 text-3xl font-black text-gray-900">Notificaciones operativas</h1><p class="mt-2 text-gray-500">Solicitudes nuevas y productos que necesitan reposición.</p></div>
    <div class="space-y-3">
        @forelse($notificaciones as $notificacion)
            @php($data = $notificacion->data)
            <div class="flex items-start justify-between gap-4 border-l-4 {{ $data['tipo'] === 'stock_critico' ? 'border-amber-400' : 'border-indigo-500' }} bg-white p-5 shadow-sm {{ $notificacion->read_at ? 'opacity-60' : '' }}">
                <div><h2 class="font-bold text-gray-900">{{ $data['titulo'] }}</h2><p class="mt-1 text-sm text-gray-600">{{ $data['mensaje'] }}</p><p class="mt-2 text-xs text-gray-400">{{ $notificacion->created_at->diffForHumans() }}</p></div>
                @if(!$notificacion->read_at)<form method="POST" action="{{ route('notificaciones.read', $notificacion->id) }}">@csrf<button class="text-sm font-semibold text-indigo-600 hover:text-indigo-900">Marcar leído</button></form>@endif
            </div>
        @empty
            <div class="bg-white p-8 text-center text-gray-500">No hay avisos pendientes.</div>
        @endforelse
    </div>
    <div class="mt-6">{{ $notificaciones->links() }}</div>
</div>
@endsection