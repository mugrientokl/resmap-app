<!DOCTYPE html>
@php($isHome = request()->routeIs('home'))
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESMAP - Sistema de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }

        body { padding-top: 76px; }
<<<<<<< HEAD
        body.homepage { padding-top: 0; }

        :root { --content-gutter: clamp(1rem, 10vw, 5cm); }

        body > main {
            max-width: none !important;
            padding-left: var(--content-gutter);
            padding-right: var(--content-gutter);
        }

        .max-w-5xl,
        .max-w-6xl,
        .max-w-7xl { max-width: 100% !important; }

        .hero-bleed {
            margin-left: calc(var(--content-gutter) * -1);
            margin-right: calc(var(--content-gutter) * -1);
            width: calc(100% + (var(--content-gutter) * 2));
        }

        .hero-content {
            padding-left: var(--content-gutter);
            padding-right: var(--content-gutter);
        }

        nav > div.max-w-7xl {
            max-width: calc(100% - var(--content-gutter) - var(--content-gutter)) !important;
            padding-left: 0;
            padding-right: 0;
        }
=======
>>>>>>> 6bd4a393eaa1e25a3e69d1eb87c095bedcb53f31

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
<body class="bg-[#f7f3f0] font-sans antialiased text-[#241817] {{ $isHome ? 'homepage' : '' }}">
    <nav class="fixed inset-x-0 top-0 z-40 text-white {{ $isHome ? 'bg-transparent' : 'bg-[#8f241d] shadow-lg' }}">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center" aria-label="Ir al inicio de Resmap">
                <img src="{{ asset('images/resmap sin fondo.png') }}" alt="Resmap" class="brand-logo h-12 w-auto max-w-45 object-contain">
            </a>
            @auth
                @php($unreadNotifications = auth()->user()->unreadNotifications()->count())
                @php($pendingRequests = \App\Models\SolicitudWeb::where('estado', 'Pendiente')->count())
                <button type="button" id="mobile-menu-button" class="rounded-md p-2 hover:bg-[#721d18] md:hidden" aria-controls="main-menu" aria-expanded="false" aria-label="Abrir menú">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            @endauth
            @auth
                <div id="main-menu" class="absolute left-0 right-0 top-full hidden bg-[#8f241d] px-4 pb-4 shadow-lg md:static md:flex md:items-center md:gap-4 md:bg-transparent md:p-0 md:shadow-none">
                    <a href="{{ route('productos.index') }}" class="block rounded px-3 py-2 text-sm hover:bg-[#721d18] hover:text-[#ffd5c9]">Inventario</a>
                    <a href="{{ route('pos.index') }}" class="block rounded px-3 py-2 text-sm hover:bg-[#721d18] hover:text-[#ffd5c9]">POS</a>
                    <a href="{{ route('catalogo.index') }}" class="block rounded px-3 py-2 text-sm hover:bg-[#721d18] hover:text-[#ffd5c9]">Catálogo</a>
                    <a href="{{ route('servicios.index') }}" class="block rounded px-3 py-2 text-sm hover:bg-[#721d18] hover:text-[#ffd5c9]">Servicios</a>
                    <a href="{{ route('solicitudes.index') }}" class="block rounded px-3 py-2 text-sm hover:bg-[#721d18] hover:text-[#ffd5c9]">Solicitudes @if($pendingRequests)<span class="ml-1 rounded-full bg-[#ffd5c9] px-2 py-0.5 text-xs text-[#8f241d]">{{ $pendingRequests }}</span>@endif</a>
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
                <div class="flex items-center gap-3 text-sm">
                    <a href="{{ route('catalogo.index') }}" class="rounded px-3 py-2 hover:bg-[#721d18] hover:text-[#ffd5c9]">Catálogo</a>
                                        <a href="{{ route('servicios.index') }}" class="rounded px-3 py-2 hover:bg-[#721d18] hover:text-[#ffd5c9]">Servicios</a>
                    <a href="{{ route('login') }}" class="rounded px-3 py-2 hover:bg-[#721d18] hover:text-[#ffd5c9]">Acceso interno</a>
                </div>
            @endauth
        </div>
    </nav>

<<<<<<< HEAD
    <main class="mx-auto min-h-[calc(100vh-140px)] py-6">
=======
    <main class="mx-auto min-h-[calc(100vh-140px)] max-w-7xl px-4 py-6">
>>>>>>> 6bd4a393eaa1e25a3e69d1eb87c095bedcb53f31
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