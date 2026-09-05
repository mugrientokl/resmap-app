<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESMAP - Sistema de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }

        body { padding-top: 76px; }

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

        .brand-logo { filter: drop-shadow(1px 0 0 #fff) drop-shadow(-1px 0 0 #fff) drop-shadow(0 1px 0 #fff) drop-shadow(0 -1px 0 #fff); }
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
                        <a href="{{ route('clientes.index') }}" class="block rounded px-3 py-2 text-sm hover:bg-[#721d18] hover:text-[#ffd5c9]">Clientes</a>
                    @endif
                    <div class="relative mt-2 border-t border-[#b5534b] pt-2 md:mt-0 md:border-0 md:pt-0">
                        <button type="button" id="profile-menu-button" class="flex w-full items-center gap-2 rounded px-3 py-2 text-left text-sm hover:bg-[#721d18]" aria-haspopup="true" aria-expanded="false">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#ffd5c9] font-bold text-[#8f241d]">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" /></svg>
                        </button>
                        <div id="profile-menu" class="absolute right-0 mt-1 hidden min-w-48 rounded-md bg-white py-1 text-sm text-gray-700 shadow-xl">
                            @if(auth()->user()->rol === 'Administrador')
                                <a href="{{ route('reportes.index') }}" class="block px-4 py-2 hover:bg-[#f7e8e6]">Reportes</a>
                                <a href="{{ route('categorias.index') }}" class="block px-4 py-2 hover:bg-[#f7e8e6]">Categorías</a>
                                <a href="{{ route('solicitudes.index') }}" class="block px-4 py-2 hover:bg-[#f7e8e6]">Solicitudes</a>
                                <a href="{{ route('reportes.auditoria') }}" class="block px-4 py-2 hover:bg-[#f7e8e6]">Auditoría</a>
                                <a href="{{ route('backups.index') }}" class="block px-4 py-2 hover:bg-[#f7e8e6]">Backups</a>
                            @endif
                            <a href="{{ route('notificaciones.index') }}" class="block px-4 py-2 hover:bg-[#f7e8e6]">Avisos @if($unreadNotifications)<span class="ml-1 rounded-full bg-[#b52f25] px-2 py-0.5 text-xs text-white">{{ $unreadNotifications }}</span>@endif</a>
                            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="block w-full px-4 py-2 text-left text-red-700 hover:bg-red-50">Cerrar sesión</button></form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm hover:text-[#ffd5c9]">Acceso interno</a>
            @endauth
        </div>
    </nav>

    <main class="mx-auto min-h-[calc(100vh-140px)] max-w-7xl px-4 py-6">
        @yield('content')
    </main>
    <footer class="mt-8 border-t border-[#e8c8c3] bg-white px-4 py-5 text-center text-sm text-gray-600">
        © RESMAP 2026. Todos Los Derechos Reservados.
    </footer>
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mainMenu = document.getElementById('main-menu');
        const profileMenuButton = document.getElementById('profile-menu-button');
        const profileMenu = document.getElementById('profile-menu');

        mobileMenuButton?.addEventListener('click', () => {
            const isHidden = mainMenu.classList.toggle('hidden');
            mobileMenuButton.setAttribute('aria-expanded', String(!isHidden));
        });

        profileMenuButton?.addEventListener('click', () => {
            const isHidden = profileMenu.classList.toggle('hidden');
            profileMenuButton.setAttribute('aria-expanded', String(!isHidden));
        });

        document.addEventListener('click', (event) => {
            if (profileMenu && profileMenuButton && !profileMenuButton.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.add('hidden');
                profileMenuButton.setAttribute('aria-expanded', 'false');
            }
        });
    </script>
</body>
</html>