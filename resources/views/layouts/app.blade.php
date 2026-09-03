<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESMAP - Sistema de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <nav class="bg-slate-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-wide">RESMAP SpA</a>
            @auth
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('productos.index') }}" class="hover:text-blue-300">Inventario</a>
                    <a href="{{ route('pos.index') }}" class="hover:text-blue-300">POS</a>
                    @if(auth()->user()->rol === 'Administrador')
                        <a href="{{ route('categorias.index') }}" class="hover:text-blue-300">Categorías</a>
                        <a href="{{ route('clientes.index') }}" class="hover:text-blue-300">Clientes</a>
                    @endif
                    <a href="{{ route('notificaciones.index') }}" class="relative hover:text-blue-300">Avisos @if(auth()->user()->unreadNotifications()->count())<span class="ml-1 rounded-full bg-amber-400 px-2 py-0.5 text-xs text-slate-900">{{ auth()->user()->unreadNotifications()->count() }}</span>@endif</a>
                    <span class="hidden text-slate-300 md:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-red-300 hover:text-red-100">Salir</button></form>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm hover:text-blue-300">Acceso interno</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>
</body>
</html>