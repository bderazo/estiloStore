@if(!empty($bannerNosotrosHeader) && !empty($bannerNosotrosHeader->imagen_ruta))
<section class="nosotros__header">
    <div class="nosotros__header-bg"
         style="background-image: url('{{ asset('storage/' . $bannerNosotrosHeader->imagen_ruta) }}');">

        <div class="nosotros__header-overlay"></div>

        <div class="nosotros__header-content">
            <h1 class="nosotros__header-title">
                Nosotros
            </h1>
            <p class="nosotros__header-subtitle">
                Belleza consciente, ingredientes honestos y resultados reales
            </p>
        </div>

        <!-- Forma inferior -->
        <div class="nosotros__header-shape">
    <svg viewBox="0 0 1440 90" preserveAspectRatio="none">
        <path
            d="M0,0 C120,20 320,50 720,70 1120,90 1320,70 1440,60 L1440,90 L0,90 Z"
            fill="var(--white-color)">
        </path>
    </svg>
        </div>

    </div>
</section>
@endif


<section class="about__section section--padding">
    <div class="container">
        @foreach($secciones as $index => $seccion)
            @php
                $esPar = ($index + 1) % 2 == 0;
                $tieneImagen = !empty($seccion->imagen);

                $bgSection = $esPar
                    ? 'background: var(--secondary-color-pastel);'
                    : 'background: var(--white-color);';
            @endphp

            <div class="row align-items-center mb-60 g-0 nosotros__card"
                 style="{{ $bgSection }}">

                @if($tieneImagen)

                    {{-- IMAGEN IZQUIERDA --}}
                    @if(!$esPar)
                        <div class="col-lg-6">
                            <div class="nosotros__image-container shape-bg-left">
                                <img
                                    src="{{ asset($seccion->url_completa) }}"
                                    alt="{{ $seccion->titulo }}"
                                    class="nosotros__img">
                            </div>
                        </div>

                        <div class="col-lg-6 p-4 p-md-5">
                            <h2 class="nosotros__title">{{ $seccion->titulo }}</h2>
                            <div class="nosotros__text">{!! $seccion->contenido !!}</div>
                        </div>

                    {{-- IMAGEN DERECHA --}}
                    @else
                        <div class="col-lg-6 order-2 order-lg-1 p-4 p-md-5">
                            <h2 class="nosotros__title">{{ $seccion->titulo }}</h2>
                            <div class="nosotros__text">{!! $seccion->contenido !!}</div>
                        </div>

                        <div class="col-lg-6 order-1 order-lg-2">
                            <div class="nosotros__image-container shape-bg-right">
                                <img
                                    src="{{ asset($seccion->url_completa) }}"
                                    alt="{{ $seccion->titulo }}"
                                    class="nosotros__img">
                            </div>
                        </div>
                    @endif

                {{-- SIN IMAGEN --}}
                @else
                    <div class="col-12 p-5 text-center">
                        <h2 class="nosotros__title mb-4">{{ $seccion->titulo }}</h2>
                        <div class="nosotros__text mx-auto" style="max-width: 820px;">
                            {!! $seccion->contenido !!}
                        </div>
                    </div>
                @endif

            </div>
        @endforeach
    </div>
</section>

<style>
/* =============================
   CARD GENERAL
============================= */
.nosotros__card {
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 12px 30px rgba(0,0,0,.06);
    transition: transform .3s ease;
}

.nosotros__card:hover {
    transform: translateY(-4px);
}

/* =============================
   CONTENEDOR DE IMAGEN
============================= */
.nosotros__image-container {
    position: relative;
    width: 100%;
    height: 360px;
    padding: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(
        135deg,
        var(--secondary-color-pastel),
        var(--light-color)
    );
}

/* IMAGEN SIN RECORTE */
.nosotros__img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    position: relative;
    z-index: 2;
    transition: transform .4s ease;
}

.nosotros__image-container:hover .nosotros__img {
    transform: scale(1.04);
}

/* =============================
   FORMAS DECORATIVAS (BEAUTY)
============================= */
.shape-bg-left::before,
.shape-bg-right::before {
    content: "";
    position: absolute;
    inset: 0;
    z-index: 1;
    opacity: .25;
}

.shape-bg-left::before {
    background: radial-gradient(
        circle at left center,
        var(--secondary-color),
        transparent 65%
    );
}

.shape-bg-right::before {
    background: radial-gradient(
        circle at right center,
        var(--primary-color),
        transparent 65%
    );
}

/* =============================
   TEXTO
============================= */
.nosotros__title {
    color: var(--primary-color);
    font-weight: 700;
    font-family: var(--font-jost);
    letter-spacing: .5px;
    margin-bottom: 1rem;
}

.nosotros__text {
    color: var(--text-gray-color);
    font-size: 1.05rem;
    line-height: 1.9;
}

/* =============================
   ESPACIADO
============================= */
.mb-60 {
    margin-bottom: 60px;
}

/* =============================
   RESPONSIVE
============================= */
@media (max-width: 991px) {
    .nosotros__image-container {
        height: 280px;
        padding: 18px;
    }

    .shape-bg-left::before,
    .shape-bg-right::before {
        display: none;
    }

    .mb-60 {
        margin-bottom: 32px;
    }
}

/* =============================
   HEADER NOSOTROS
============================= */
.nosotros__header {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.nosotros__header-bg {
    position: relative;
    min-height: 420px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Overlay elegante */
.nosotros__header-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        180deg,
        rgba(6, 23, 56, 0.55),
        rgba(6, 23, 56, 0.25)
    );
}

/* Contenido */
.nosotros__header-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: var(--white-color);
    padding: 0 20px;
}

.nosotros__header-title {
    font-family: var(--font-jost);
    font-size: clamp(2.4rem, 4vw, 3.2rem);
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: .5rem;
}

.nosotros__header-subtitle {
    font-size: 1.05rem;
    opacity: .9;
    max-width: 520px;
    margin: 0 auto;
}

/* =============================
   FORMA INFERIOR (FLUIDEZ)
============================= */
.nosotros__header-shape {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 90px;
    overflow: hidden;
    pointer-events: none;
}


.nosotros__header-shape svg {
    width: 100%;
    height: 90px;
    display: block;
}

/* =============================
   RESPONSIVE
============================= */
@media (max-width: 991px) {
    .nosotros__header-bg {
        min-height: 300px;
    }

    .nosotros__header-shape svg {
        height: 60px;
    }
}
@media (max-width: 768px) {
    .nosotros__header-bg {
        min-height: auto;
        aspect-ratio: 16 / 9;   /* o 4 / 3 si son fotos verticales */
        background-size: contain;
        background-repeat: no-repeat;
        background-color: var(--secondary-color-pastel);
    }
}

</style>
