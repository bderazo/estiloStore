<!doctype html>
<html lang="zxx">

<head>
    <!-- Recursos de cabecera -->
    @include('web.index.partials.head-recursos')
</head>

<body>
    <!-- Bloque: Preloader -->
    @include('web.layouts.preloader')

    <!-- Bloque: Encabezado -->
    @include('web.layouts.header')

    <main class="main__content_wrapper">
        @include('web.index.nosotros', [
            'nosotros' => $nosotros,
            'imagenes' => $imagenes,
        ])
    </main>

    <!-- Bloque: Pie de página -->
    @include('web.layouts.footer')

    <!-- Bloque: Modal de vista rápida -->
    @include('web.index.partials.modal-quickview')

    <!-- Bloque: Botón subir -->
    @include('web.index.partials.boton-scroll-top')

    <!-- Bloque: Scripts principales -->
    @include('web.index.partials.scripts')
</body>

</html>
