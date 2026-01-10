<!doctype html>
<html lang="zxx">

<head>
    <!-- Recursos de cabecera -->
    @include('web.index.partials.head-recursos')
</head>

<body>

    @php
        $carouselItems = ($carouselItems ?? collect())->values();
    @endphp

    <script id="carousel-data" type="application/json">
        {!! $carouselItems->toJson(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <!-- Bloque: Preloader -->
    @include('web.layouts.preloader')

    <!-- Bloque: Encabezado -->
    @include('web.layouts.header')

    <main class="main__content_wrapper">
        <!-- Sección: Slider principal -->
        @include('web.index.partials.slider-hero')

        <!--Sección categorias -->
        @include('web.index.partials.carrusel-categoria')
        
        <!--Sección ofertas -->
        @include('web.index.partials.carrusel-ofertas')

        <!-- Start shipping section -->

        <!-- End shipping section -->
        @include('web.index.partials.shipping')

        <!-- Sección: Promociones dobles -->
        @include('web.index.partials.banner-promos-dobles')

        <!-- Sección: Banner ancho completo -->
        @include('web.index.partials.banner-fullwidth')
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


