<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RESMAP | Repuestos para trabajo real</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f2efe8] text-[#17211f] antialiased">
    <header class="absolute inset-x-0 top-0 z-10">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-xl font-black tracking-[.18em] text-white">RESMAP</a>
            <div class="flex flex-wrap items-center justify-end gap-3 text-sm font-semibold text-white">
                <a href="{{ route('catalogo.index') }}" class="hover:text-[#ffd5c9]">Catálogo</a>
                @auth
                    <a href="{{ route('productos.index') }}" class="hover:text-[#ffd5c9]">Inventario</a>
                    <a href="{{ route('pos.index') }}" class="hover:text-[#ffd5c9]">POS</a>
                    <a href="{{ route('notificaciones.index') }}" class="hover:text-[#ffd5c9]">Avisos @if(auth()->user()->unreadNotifications()->count())<span class="rounded-full bg-[#ffd5c9] px-2 py-0.5 text-xs text-[#8f241d]">{{ auth()->user()->unreadNotifications()->count() }}</span>@endif</a>
                    @if(auth()->user()->rol === 'Administrador')
                        <a href="{{ route('categorias.index') }}" class="hover:text-[#ffd5c9]">Categorías</a>
                        <a href="{{ route('clientes.index') }}" class="hover:text-[#ffd5c9]">Clientes</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button class="rounded-full border border-white/50 px-4 py-2 hover:bg-white hover:text-[#8f241d]">Salir</button></form>
                @else
                    <a href="{{ route('login') }}" class="rounded-full border border-white/50 px-4 py-2 hover:bg-white hover:text-[#8f241d]">Acceso interno</a>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        <section class="relative isolate min-h-[76vh] overflow-hidden bg-[#8f241d]">
            <div class="absolute inset-0 bg-[linear-gradient(115deg,rgba(82,17,13,.94),rgba(143,36,29,.72)),url('https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=2200&q=85')] bg-cover bg-center"></div>
            <div class="relative mx-auto flex min-h-[76vh] max-w-7xl items-end px-6 pb-20 pt-32 lg:px-8">
                <div class="max-w-3xl text-white">
                    <p class="mb-5 text-sm font-bold uppercase tracking-[.3em] text-[#f6b941]">Suministros que mantienen el trabajo en marcha</p>
                    <h1 class="max-w-2xl text-5xl font-black leading-[.95] tracking-tight md:text-7xl">Repuestos eléctricos, sin perder tiempo.</h1>
                    <p class="mt-7 max-w-xl text-lg leading-8 text-white/80">Explora el catálogo RESMAP y envíanos tu solicitud. Nuestro equipo confirma disponibilidad y te contacta directamente.</p>
                    <a href="#catalogo-destacado" class="mt-9 inline-flex items-center rounded-full bg-[#f6b941] px-6 py-3 font-bold text-[#17211f] shadow-lg transition hover:bg-white">Ver catálogo <span class="ml-3">↓</span></a>
                </div>
            </div>
        </section>

        <section id="catalogo-destacado" class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
            <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
                <div><p class="text-sm font-bold uppercase tracking-[.25em] text-[#9f2f25]">Explora por especialidad</p><h2 class="mt-2 text-4xl font-black tracking-tight">Lo que más se mueve</h2></div>
                <a href="{{ route('catalogo.index') }}" class="font-bold text-[#9f2f25] hover:text-[#721d18]">Ver catálogo completo →</a>
            </div>
            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($categorias as $categoria)
                    <a href="{{ route('catalogo.index', ['categoria' => $categoria->id_categoria]) }}" class="group border-t-4 border-[#b52f25] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="flex items-center justify-between"><h3 class="text-xl font-black">{{ $categoria->nombre_categoria }}</h3><span class="text-2xl text-[#f6b941]">↗</span></div>
                        <p class="mt-3 text-sm text-gray-500">{{ $categoria->productos_count }} productos disponibles</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-16 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach($productos as $producto)
                    <article class="bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase tracking-wider text-[#9f2f25]">{{ $producto->categoria->nombre_categoria ?? 'Repuesto' }}</p><h3 class="mt-3 min-h-12 font-bold">{{ $producto->nombre }}</h3><p class="mt-4 text-xl font-black">$ {{ number_format($producto->precio, 0, ',', '.') }}</p></article>
                @endforeach
            </div>
        </section>
    </main>
</body>
</html>
