<section class="hero__slider--section">
    <div class="hero__slider--inner hero__slider--activation swiper">
        <div class="hero__slider--wrapper swiper-wrapper">
            @php
                $sizeMap = ['sm' => '480w', 'md' => '768w', 'lg' => '1280w'];
                $defaultImage = asset('web/assets/img/slider/home1-slider1.png');
            @endphp

            @foreach ($carouselItems as $index => $item)
                @php
                    // OBTENER DATOS DEL BOTÓN CON COMPATIBILIDAD PARA AMBAS ESTRUCTURAS
                    $activarBoton = data_get($item, 'activar_boton', data_get($item, 'boton.visible', false));
                    $urlBoton = data_get($item, 'url_boton', data_get($item, 'boton.url', ''));
                    $textoBoton = data_get($item, 'texto_boton', data_get($item, 'boton.texto', __('Ver más')));
                    $colorBoton = data_get($item, 'color_boton', '#3B82F6');
                    $redirigirMismaPagina = data_get($item, 'redirigir_misma_pagina', data_get($item, 'boton.nueva_pestana', false));
                    
                    // DATOS DE IMAGEN (usando tu estructura original)
                    $imagenes = data_get($item, 'imagenes', []);
                    $webpVariants = collect(data_get($imagenes, 'webp', []))->filter();
                    $fallbackVariants = collect(data_get($imagenes, 'fallback', []))->filter();

                    $webpSrcset = $webpVariants->map(function ($url, $size) use ($sizeMap) {
                        return $url . ' ' . ($sizeMap[$size] ?? '1280w');
                    })->implode(', ');

                    $fallbackSrcset = $fallbackVariants->map(function ($url, $size) use ($sizeMap) {
                        return $url . ' ' . ($sizeMap[$size] ?? '1280w');
                    })->implode(', ');

                    $preferredSrc = $fallbackVariants->get('lg') ?? $fallbackVariants->first() ?? $webpVariants->first() ?? $defaultImage;

                    $fallbackMime = null;
                    if ($fallbackVariants->isNotEmpty()) {
                        $extension = pathinfo(parse_url($fallbackVariants->first(), PHP_URL_PATH) ?? '', PATHINFO_EXTENSION);
                        $fallbackMime = match (strtolower($extension)) {
                            'png' => 'image/png',
                            'gif' => 'image/gif',
                            'webp' => 'image/webp',
                            default => 'image/jpeg',
                        };
                    } elseif ($fallbackSrcset === '') {
                        $fallbackMime = 'image/jpeg';
                        $fallbackSrcset = $defaultImage . ' 1280w';
                    }

                    $loadingMode = $loop->first ? 'eager' : 'lazy';
                    $sizesAttr = '(min-width: 1400px) 1280px, (min-width: 992px) 960px, (min-width: 768px) 720px, 100vw';
                    $altText = data_get($item, 'alt_text') ?: 'Banner promocional';

                    // DATOS DE TEXTO
                    $textoDestacado = data_get($item, 'texto_destacado', '');
                    
                    // TÍTULO (dividido en dos líneas)
                    $nombre = trim((string) data_get($item, 'titulo', ''));
                    $nombrePalabras = $nombre !== '' ? preg_split('/\s+/', $nombre) : [];
                    $nombrePrimeraParte = implode(' ', array_slice($nombrePalabras, 0, 3));
                    $nombreSegundaParte = implode(' ', array_slice($nombrePalabras, 3));
                    
                    // SUBTÍTULO (solo para posición derecha, debajo del título)
                    $subtituloRaw = trim((string) data_get($item, 'subtitulo', ''));
                    $subtituloPalabras = $subtituloRaw !== '' ? preg_split('/\s+/', $subtituloRaw) : [];
                    $subtituloPrimeraParte = implode(' ', array_slice($subtituloPalabras, 0, 6));
                    $subtituloSegundaParte = implode(' ', array_slice($subtituloPalabras, 6));
                    
                    // POSICIÓN DEL CONTENIDO
                    $posicionIzquierda = data_get($item, 'posicion_contenido') === 'Derecha';
                    
                    // CONFIGURACIÓN DEL BOTÓN
                    $targetBlank = !$redirigirMismaPagina;
                    $estiloBoton = "background-color: {$colorBoton}; border-color: {$colorBoton};";
                    $mostrarBotonPersonalizado = $activarBoton && !empty($urlBoton);
                @endphp

                {{-- DEBUG (opcional) --}}
                @if ($loop->first && false) {{-- Cambia a true si quieres ver el debug --}}
                    <div style="display: none; position: fixed; top: 10px; left: 10px; background: white; padding: 20px; z-index: 9999; border: 2px solid red; max-width: 400px;">
                        <h4>Debug del Carrusel (Primer Item)</h4>
                        <pre>{{ print_r($item, true) }}</pre>
                        <p>posicion_contenido: {{ data_get($item, 'posicion_contenido', 'NO EXISTE') }}</p>
                        <p>posicionIzquierda: {{ $posicionIzquierda ? 'TRUE' : 'FALSE' }}</p>
                        <p>preferredSrc: {{ $preferredSrc }}</p>
                    </div>
                @endif

                <div class="swiper-slide" aria-label="Diapositiva {{ $loop->iteration }}">
                    <div class="hero__slider--items {{ $posicionIzquierda ? 'home1__slider--bg' : 'home1__slider--bg three' }}">
                        {{-- IMAGEN --}}
                        <div class="hero__slider--media">
                            <picture>
                                @if ($webpSrcset !== '')
                                    <source type="image/webp" srcset="{{ $webpSrcset }}" sizes="{{ $sizesAttr }}">
                                @endif
                                @if ($fallbackSrcset !== '' && $fallbackMime)
                                    <source type="{{ $fallbackMime }}" srcset="{{ $fallbackSrcset }}" sizes="{{ $sizesAttr }}">
                                @endif
                                <img src="{{ $preferredSrc }}" alt="{{ $altText }}" loading="{{ $loadingMode }}" width="1280" height="720">
                            </picture>
                        </div>

                        {{-- CONTENIDO PARA POSICIÓN IZQUIERDA --}}
                        @if ($posicionIzquierda)
                            <div class="container-fluid">
                                <div class="hero__slider--items__inner">
                                    <div class="row row-cols-1">
                                        <div class="col">
                                            <div class="slider__content">
                                                <!-- @if (!empty($textoDestacado))
                                                    <p class="slider__content--desc desc1 mb-15">
                                                        <img class="slider__text--shape__icon" src="{{ asset('web/assets/img/icon/text-shape-icon.png') }}" alt="text-shape-icon">
                                                        {{ $textoDestacado }}
                                                    </p>
                                                @endif -->
                                                
                                                {{-- TÍTULO SOLO PARA POSICIÓN IZQUIERDA SI ES NECESARIO --}}
                                                @if ((!empty($nombrePrimeraParte) || !empty($nombreSegundaParte)) && $posicionIzquierda)
                                                    <h2 class="slider__content--maintitle h1">
                                                        @if (!empty($nombrePrimeraParte)){{ $nombrePrimeraParte }}@endif
                                                        @if (!empty($nombreSegundaParte))<br>{{ $nombreSegundaParte }}@endif
                                                    </h2>
                                                @endif

                                                 {{-- SUBTÍTULO (SOLO DEBAJO DEL TÍTULO) --}}
                                                @if ($subtituloRaw !== '')
                                                    <p class="slider__content--desc desc2 d-sm-2-none mb-40">
                                                        {{ $subtituloPrimeraParte }}
                                                        @if (!empty($subtituloSegundaParte))<br>{{ $subtituloSegundaParte }}@endif
                                                    </p>
                                                @endif
                                                
                                                {{-- BOTÓN --}}
                                                @if ($mostrarBotonPersonalizado)
                                                    <a class="slider__btn primary__btn" 
                                                       href="{{ $urlBoton }}" 
                                                       @if ($targetBlank) target="_blank" rel="noopener" @endif
                                                       style="{{ $estiloBoton }}">
                                                        {{ $textoBoton }}
                                                        <svg class="primary__btn--arrow__icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                                            <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <a class="slider__btn primary__btn" href="{{ route('login') }}">
                                                        {{ __('Iniciar sesión') }}
                                                        <svg class="primary__btn--arrow__icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                                            <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{-- CONTENIDO PARA POSICIÓN DERECHA --}}
                        @else
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-6 offset-lg-6">
                                        <div class="hero__slider--items__inner">
                                            <div class="slider__content text-center">
                                                {{-- TEXTO DESTACADO (icono + texto) --}}
                                                <!-- @if (!empty($textoDestacado))
                                                    <p class="slider__content--desc desc1 mb-15">
                                                        <img class="slider__text--shape__icon" src="{{ asset('web/assets/img/icon/text-shape-icon.png') }}" alt="text-shape-icon">
                                                        {{ $textoDestacado }}
                                                    </p>
                                                @endif -->
                                                
                                                {{-- TÍTULO --}}
                                                @if (!empty($nombrePrimeraParte) || !empty($nombreSegundaParte))
                                                    <h2 class="slider__content--maintitle h1">
                                                        @if (!empty($nombrePrimeraParte)){{ $nombrePrimeraParte }}@endif
                                                        @if (!empty($nombreSegundaParte))<br>{{ $nombreSegundaParte }}@endif
                                                    </h2>
                                                @endif
                                                
                                                {{-- SUBTÍTULO (SOLO DEBAJO DEL TÍTULO) --}}
                                                @if ($subtituloRaw !== '')
                                                    <p class="slider__content--desc desc2 d-sm-2-none mb-40">
                                                        {{ $subtituloPrimeraParte }}
                                                        @if (!empty($subtituloSegundaParte))<br>{{ $subtituloSegundaParte }}@endif
                                                    </p>
                                                @endif
                                                
                                                {{-- BOTÓN --}}
                                                @if ($mostrarBotonPersonalizado)
                                                    <a class="slider__btn primary__btn" 
                                                       href="{{ $urlBoton }}" 
                                                       @if ($targetBlank) target="_blank" rel="noopener" @endif
                                                       style="{{ $estiloBoton }}">
                                                        {{ $textoBoton }}
                                                        <svg class="primary__btn--arrow__icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                                            <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <a class="slider__btn primary__btn" href="{{ route('login') }}">
                                                        {{ __('Iniciar sesión') }}
                                                        <svg class="primary__btn--arrow__icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                                            <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper__nav--btn swiper-button-next"></div>
        <div class="swiper__nav--btn swiper-button-prev"></div>
    </div>
</section>