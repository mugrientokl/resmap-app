@extends('layouts.app')

@section('content')
<<<<<<< HEAD
    <style>
        .hero-dot[aria-current="true"] {
            background-color: #f6b941;
            box-shadow: 0 0 0 3px rgba(246, 185, 65, .25), 0 2px 8px rgba(0, 0, 0, .25);
            transform: scaleY(1.35);
        }
    </style>
    <main class="-mt-6">
        <section class="hero-bleed relative isolate min-h-svh overflow-hidden bg-[#8f241d]" aria-label="Servicios destacados">
            <div id="hero-slides" class="absolute inset-0">
                <article class="hero-slide absolute inset-0 opacity-100 transition-opacity duration-700" data-slide="0" aria-hidden="false">
                    <div class="absolute inset-0 bg-[linear-gradient(115deg,rgba(82,17,13,.94),rgba(143,36,29,.72)),url('https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=2200&q=85')] bg-cover bg-center"></div>
                    <div class="hero-content relative mx-auto flex min-h-svh max-w-7xl items-end pb-20 pt-32"><div class="max-w-3xl text-white"><div class="flex flex-wrap items-center gap-6"><p class="m-0 text-sm font-bold uppercase tracking-[.3em] text-[#f6b941]">Suministros</p><a href="#catalogo-destacado" class="hero-action inline-flex items-center rounded-full bg-[#f6b941] px-6 py-3 font-bold text-[#17211f] shadow-lg transition hover:bg-white">Ver <span class="ml-3">↓</span></a></div><h1 class="max-w-2xl text-5xl font-black leading-[.95] tracking-tight md:text-7xl">Repuestos eléctricos, sin perder tiempo.</h1><p class="mt-7 max-w-xl text-lg leading-8 text-white/80">Explora el catálogo RESMAP y envíanos tu solicitud. Nuestro equipo confirma disponibilidad y te contacta directamente.</p></div></div>
                </article>
                @foreach([
                    ['Mantención', 'Mantención preventiva para seguir avanzando.', 'Anticipa fallas y mantén tus equipos listos para trabajar.', 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=2200&q=85'],
                    ['Reparación', 'Reparaciones que devuelven tu equipo al trabajo.', 'Cuéntanos qué ocurrió y coordinamos una atención técnica.', 'https://images.unsplash.com/photo-1581092795360-fd1ca04f0952?auto=format&fit=crop&w=2200&q=85'],
                    ['Soldadura', 'Soldadura precisa para soluciones resistentes.', 'Recibe orientación para resolver tu requerimiento con el acabado correcto.', 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=2200&q=85'],
                ] as $indice => [$tipo, $titulo, $descripcion, $imagen])
                    <article class="hero-slide absolute inset-0 opacity-0 transition-opacity duration-700" data-slide="{{ $indice + 1 }}" aria-hidden="true" inert>
                        <div class="absolute inset-0 bg-[linear-gradient(115deg,rgba(82,17,13,.94),rgba(143,36,29,.62)),url('{{ $imagen }}')] bg-cover bg-center"></div>
                        <div class="hero-content relative mx-auto flex min-h-svh max-w-7xl items-end pb-20 pt-32"><div class="max-w-3xl text-white"><div class="flex flex-wrap items-center gap-6"><p class="m-0 text-sm font-bold uppercase tracking-[.3em] text-[#f6b941]">Servicios RESMAP</p><a href="{{ route('servicios.index', ['tipo_servicio' => $tipo]) }}" class="hero-action inline-flex items-center rounded-full bg-[#f6b941] px-6 py-3 font-bold text-[#17211f] shadow-lg transition hover:bg-white">Solicitar {{ $tipo }} <span class="ml-3">→</span></a></div><h2 class="max-w-2xl text-5xl font-black leading-[.95] tracking-tight md:text-7xl">{{ $titulo }}</h2><p class="mt-7 max-w-xl text-lg leading-8 text-white/80">{{ $descripcion }}</p></div></div>
                    </article>
                @endforeach
=======
    <main class="-mx-4 -mt-6">
        <section class="relative isolate min-h-[76vh] overflow-hidden bg-[#8f241d]">
            <div class="absolute inset-0 bg-[linear-gradient(115deg,rgba(82,17,13,.94),rgba(143,36,29,.72)),url('https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=2200&q=85')] bg-cover bg-center"></div>
            <div class="relative mx-auto flex min-h-[76vh] max-w-7xl items-end px-6 pb-20 pt-32 lg:px-8">
                <div class="max-w-3xl text-white">
                    <p class="mb-5 text-sm font-bold uppercase tracking-[.3em] text-[#f6b941]">Suministros que mantienen el trabajo en marcha</p>
                    <h1 class="max-w-2xl text-5xl font-black leading-[.95] tracking-tight md:text-7xl">Repuestos eléctricos, sin perder tiempo.</h1>
                    <p class="mt-7 max-w-xl text-lg leading-8 text-white/80">Explora el catálogo RESMAP y envíanos tu solicitud. Nuestro equipo confirma disponibilidad y te contacta directamente.</p>
                    <a href="#catalogo-destacado" class="mt-9 inline-flex items-center rounded-full bg-[#f6b941] px-6 py-3 font-bold text-[#17211f] shadow-lg transition hover:bg-white">Ver catálogo <span class="ml-3">↓</span></a>
                </div>
>>>>>>> 6bd4a393eaa1e25a3e69d1eb87c095bedcb53f31
            </div>
            <button type="button" id="hero-previous" class="group absolute inset-y-0 left-0 z-10 flex w-1/5 items-center justify-center text-white/80" aria-label="Lámina anterior"><span class="rounded-full border border-white/40 px-4 py-2 text-2xl opacity-0 transition group-hover:bg-white group-hover:text-[#8f241d] group-hover:opacity-100">←</span></button>
            <button type="button" id="hero-next" class="group absolute inset-y-0 right-0 z-10 flex w-1/5 items-center justify-center text-white/80" aria-label="Siguiente lámina"><span class="rounded-full border border-white/40 px-4 py-2 text-2xl opacity-0 transition group-hover:bg-white group-hover:text-[#8f241d] group-hover:opacity-100">→</span></button>
            <div class="absolute bottom-7 left-1/2 z-20 flex -translate-x-1/2 gap-2" aria-label="Seleccionar lámina">@foreach(range(0, 3) as $indice)<button type="button" class="hero-dot h-2.5 w-10 rounded-full bg-white/45 transition duration-300 hover:bg-white/80" data-slide-target="{{ $indice }}" aria-label="Ir a la lámina {{ $indice + 1 }}" aria-current="{{ $indice === 0 ? 'true' : 'false' }}"></button>@endforeach</div>
        </section>

        <section id="catalogo-destacado" class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
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
<<<<<<< HEAD
    <script>
        (() => {
            const slides = [...document.querySelectorAll('.hero-slide')];
            const dots = [...document.querySelectorAll('.hero-dot')];
            let activeSlide = 0;
            let timer;

            const showSlide = (index) => {
                activeSlide = (index + slides.length) % slides.length;
                slides.forEach((slide, slideIndex) => {
                    const isActive = slideIndex === activeSlide;
                    slide.classList.toggle('opacity-100', isActive);
                    slide.classList.toggle('opacity-0', !isActive);
                    slide.setAttribute('aria-hidden', String(!isActive));
                    slide.inert = !isActive;
                });
                dots.forEach((dot, dotIndex) => dot.setAttribute('aria-current', String(dotIndex === activeSlide)));
            };

            const startTimer = () => {
                window.clearInterval(timer);
                timer = window.setInterval(() => showSlide(activeSlide + 1), 3000);
            };

            document.getElementById('hero-previous')?.addEventListener('click', () => { showSlide(activeSlide - 1); startTimer(); });
            document.getElementById('hero-next')?.addEventListener('click', () => { showSlide(activeSlide + 1); startTimer(); });
            dots.forEach((dot) => dot.addEventListener('click', () => { showSlide(Number(dot.dataset.slideTarget)); startTimer(); }));
            document.querySelector('a[href="#catalogo-destacado"]')?.addEventListener('click', (event) => {
                event.preventDefault();
                document.getElementById('catalogo-destacado')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            document.querySelectorAll('.hero-action').forEach((action) => {
                action.addEventListener('mouseenter', () => window.clearInterval(timer));
                action.addEventListener('mouseleave', startTimer);
            });
            startTimer();
        })();
    </script>
=======
>>>>>>> 6bd4a393eaa1e25a3e69d1eb87c095bedcb53f31
@endsection
