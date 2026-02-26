<!-- Inicio banners dobles -->
@php
    // Obtener los banners promocionales
    $promocionalUno = App\Models\Banner::where('seccion', 'promocional_uno')
        ->where('estado', true)
        ->first();

    $promocionalDos = App\Models\Banner::where('seccion', 'promocional_dos')
        ->where('estado', true)
        ->first();
@endphp

@if($promocionalUno || $promocionalDos)
    <section class="banner__section section--padding pt-0">
        <div class="container-fluid">
            <div class="row row-cols-md-2 row-cols-1 mb--n28">

                {{-- Banner Promocional Uno --}}
                @if($promocionalUno && $promocionalUno->imagen_ruta)
                    <div class="col mb-28">
                        <div class="banner__items position__relative">
                            <a class="banner__items--thumbnail" href="{{ $promocionalUno->url_destino ?? '#' }}">
                                <img class="banner__items--thumbnail__img banner__img--max__height"
                                    src="{{ asset('storage/' . $promocionalUno->imagen_ruta) }}"
                                    alt="{{ $promocionalUno->titulo ?? 'Banner promocional' }}">
                                <div class="banner__items--content">
                                    @if($promocionalUno->subtitulo)
                                        <span class="banner__items--content__subtitle d-none d-lg-block">
                                            {{ $promocionalUno->subtitulo }}
                                        </span>
                                    @endif

                                    @if($promocionalUno->titulo)
                                        <h2 class="banner__items--content__title h3">
                                            {{ $promocionalUno->titulo }}
                                        </h2>
                                    @endif

                                    @if($promocionalUno->url_destino)
                                        <span class="banner__items--content__link">
                                            <u>Descubrir ahora</u>
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Banner Promocional Dos --}}
                @if($promocionalDos && $promocionalDos->imagen_ruta)
                    <div class="col mb-28">
                        <div class="banner__items position__relative">
                            <a class="banner__items--thumbnail" href="{{ $promocionalDos->url_destino ?? '#' }}">
                                <img class="banner__items--thumbnail__img banner__img--max__height"
                                    src="{{ asset('storage/' . $promocionalDos->imagen_ruta) }}"
                                    alt="{{ $promocionalDos->titulo ?? 'Banner promocional' }}">
                                <div class="banner__items--content">
                                    @if($promocionalDos->subtitulo)
                                        <span class="banner__items--content__subtitle d-none d-lg-block">
                                            {{ $promocionalDos->subtitulo }}
                                        </span>
                                    @endif

                                    @if($promocionalDos->titulo)
                                        <h2 class="banner__items--content__title h3">
                                            {{ $promocionalDos->titulo }}
                                        </h2>
                                    @endif

                                    @if($promocionalDos->url_destino)
                                        <span class="banner__items--content__link">
                                            <u>Descubrir ahora</u>
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
@endif