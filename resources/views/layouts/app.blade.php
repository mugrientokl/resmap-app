<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESMAP - Sistema de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        nav[role="navigation"] a,
        nav[role="navigation"] span {
            border-color: #e8c8c3 !important;
            color: #9f2f25 !important;
            background-color: #fff !important;
        }

        nav[role="navigation"] a:hover {
            background-color: #f7e8e6 !important;
            color: #8f241d !important;
        }

        nav[role="navigation"] span[aria-current="page"] span {
            background-color: #b52f25 !important;
            border-color: #b52f25 !important;
            color: #fff !important;
        }
    </style>
</head>
<body class="bg-[#f7f3f0] font-sans antialiased text-[#241817]">
    <nav class="bg-[#8f241d] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-wide">RESMAP SpA</a>
            @auth
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('productos.index') }}" class="hover:text-[#ffd5c9]">Inventario</a>
                    <a href="{{ route('pos.index') }}" class="hover:text-[#ffd5c9]">POS</a>
                    <a href="{{ route('solicitudes.index') }}" class="hover:text-[#ffd5c9]">Solicitudes</a>
                    @if(auth()->user()->rol === 'Administrador')
                        <a href="{{ route('reportes.index') }}" class="hover:text-[#ffd5c9]">Reportes</a>
                        <a href="{{ route('reportes.auditoria') }}" class="hover:text-[#ffd5c9]">Auditoría</a>
                        <a href="{{ route('backups.index') }}" class="hover:text-[#ffd5c9]">Backups</a>
                    @endif
                    @if(auth()->user()->rol === 'Administrador')
                        <a href="{{ route('categorias.index') }}" class="hover:text-[#ffd5c9]">Categorías</a>
                        <a href="{{ route('clientes.index') }}" class="hover:text-[#ffd5c9]">Clientes</a>
                    @endif
                    <a href="{{ route('notificaciones.index') }}" class="relative hover:text-[#ffd5c9]">Avisos @if(auth()->user()->unreadNotifications()->count())<span class="ml-1 rounded-full bg-[#ffd5c9] px-2 py-0.5 text-xs text-[#8f241d]">{{ auth()->user()->unreadNotifications()->count() }}</span>@endif</a>
                    <span class="hidden text-slate-300 md:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-red-300 hover:text-red-100">Salir</button></form>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm hover:text-[#ffd5c9]">Acceso interno</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>
</body>
</html>