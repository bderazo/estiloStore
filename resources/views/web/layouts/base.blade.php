<!doctype html>
<html lang="es">
<head>
    @include('web.index.partials.head-recursos')
    <title>@yield('title', 'Mi Tienda')</title>
    @stack('styles')
</head>
<body>
    @include('web.layouts.preloader')
    @include('web.layouts.header')

    <main class="main__content_wrapper">
        @yield('content') {{-- Aquí se inyectará el contenido de cada página --}}
    </main>

    @include('web.layouts.footer')
    @include('web.index.partials.modal-quickview')
    @include('web.index.partials.boton-scroll-top')
    @include('web.index.partials.scripts')
    @stack('scripts')
</body>
</html>