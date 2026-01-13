@extends('web.layouts.base')
@section('title', 'Título de la página')
@push('styles')
@endpush

@section('content')

@php
$carouselItems = ($carouselItems ?? collect())->values();
@endphp
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
{{--@include('web.index.partials.banner-promos-dobles')--}}

<!-- Sección: Banner ancho completo -->
@include('web.index.partials.banner-fullwidth')
@include('web.index.partials.banner-dos-fullwidth')

@push('scripts')
<script id="carousel-data" type="application/json">
    {!! $carouselItems->toJson(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
@endsection