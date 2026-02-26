<section class="testimonials__section section--padding" style="background: #f9f9f9; padding: 80px 0;">
    <div class="container">
        <div class="section__heading text-center mb-50">
            <h2 class="section__heading--title" style="font-size: 2.5rem; font-weight: 800; color: var(--primary-color);">
                {{ $testimonialsConfig->titulo ?? 'Testimonios de Clientes' }}
            </h2>
            @if(!empty($testimonialsConfig->subtitulo))
                <p class="section__heading--desc mt-3" style="font-size: 1.3rem; color: #666;">
                    {{ $testimonialsConfig->subtitulo }}
                </p>
            @endif
            <div style="width: 80px; height: 4px; background: var(--secondary-color); margin: 20px auto;"></div>
        </div>

        <div class="testimonials__carousel-wrapper">
            <div class="swiper testimonialsSwiper">
                <div class="swiper-wrapper">
                    @foreach($testimonios->chunk(2) as $chunk)
                        <div class="swiper-slide">
                            <div class="testimonials__grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                                @foreach($chunk as $testimonio)
                                    <div class="testimonial__card"
                                         style="background: white; border-radius: 25px; padding: 40px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); height: 100%; transition: all 0.3s ease;">
                                        <div class="testimonial__content" style="display: flex; gap: 30px; align-items: center; height: 100%;">
                                            <!-- Imagen Izquierda más grande -->
                                            <div class="testimonial__image" style="flex: 0 0 130px;">
                                                <div style="width: 130px; height: 130px; border-radius: 50%; overflow: hidden; border: 4px solid var(--secondary-color); box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                                    <img src="{{ $testimonio['imagen_url'] ?? asset('web/assets/img/avatar-placeholder.jpg') }}"
                                                         alt="{{ $testimonio['nombre'] }}"
                                                         style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            </div>

                                            <!-- Contenido Derecha con textos más grandes -->
                                            <div class="testimonial__text" style="flex: 1;">
                                                <!-- Estrellas más grandes -->
                                                <div class="testimonial__rating mb-3">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $testimonio['rating'])
                                                            <svg class="star star-filled" width="28" height="28" viewBox="0 0 24 24" fill="currentColor" style="color: #FFB800; display: inline-block; margin-right: 3px;">
                                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="star star-empty" width="28" height="28" viewBox="0 0 24 24" fill="currentColor" style="color: #ddd; display: inline-block; margin-right: 3px;">
                                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>

                                                <!-- Testimonio más grande -->
                                                <p class="testimonial__quote" style="font-size: 1.2rem; line-height: 1.7; color: #333; font-style: italic; margin-bottom: 20px; font-weight: 400;">
                                                    "{{ $testimonio['testimonio'] }}"
                                                </p>

                                                <!-- Nombre y Cargo más grandes -->
                                                <div class="testimonial__author">
                                                    <h4 class="author__name" style="font-weight: 800; color: var(--primary-color); font-size: 1.3rem; margin-bottom: 5px; letter-spacing: -0.3px;">
                                                        {{ $testimonio['nombre'] }}
                                                    </h4>
                                                    @if(!empty($testimonio['cargo']))
                                                        <p class="author__title" style="color: #666; font-size: 1rem; font-weight: 500;">
                                                            {{ $testimonio['cargo'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Controles de navegación más grandes -->
                @if($testimonialsConfig->mostrar_controles ?? true)
                    <div class="swiper-button-next" style="color: var(--secondary-color); background: white; width: 50px; height: 50px; border-radius: 50%; box-shadow: 0 5px 20px rgba(0,0,0,0.15);"></div>
                    <div class="swiper-button-prev" style="color: var(--secondary-color); background: white; width: 50px; height: 50px; border-radius: 50%; box-shadow: 0 5px 20px rgba(0,0,0,0.15);"></div>
                @endif

                <!-- Indicadores más grandes -->
                @if($testimonialsConfig->mostrar_indicadores ?? true)
                    <div class="swiper-pagination" style="position: relative; margin-top: 40px;"></div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
.testimonials__carousel-wrapper {
    position: relative;
    padding: 0 60px;
}

.testimonial__card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.testimonial__card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.12) !important;
}

.testimonials__grid {
    height: 100%;
}

.swiper-slide {
    height: auto;
}

.swiper-button-next:after,
.swiper-button-prev:after {
    font-size: 24px;
    font-weight: bold;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
    background: var(--secondary-color) !important;
    color: white !important;
    transform: scale(1.1);
}

.swiper-pagination-bullet {
    background: var(--secondary-color);
    opacity: 0.3;
    width: 12px;
    height: 12px;
    transition: all 0.3s ease;
    margin: 0 6px !important;
}

.swiper-pagination-bullet-active {
    opacity: 1;
    width: 30px;
    border-radius: 15px;
    background: var(--secondary-color);
}

/* Responsive */
@media (max-width: 1200px) {
    .testimonial__content {
        flex-direction: column;
        text-align: center;
        gap: 20px !important;
    }
    
    .testimonial__image {
        flex: 0 0 auto !important;
        margin-bottom: 10px;
    }
    
    .testimonial__quote {
        font-size: 1.1rem !important;
    }
}

@media (max-width: 992px) {
    .testimonials__grid {
        grid-template-columns: 1fr !important;
        gap: 30px !important;
    }
    
    .testimonials__carousel-wrapper {
        padding: 0 40px;
    }
}

@media (max-width: 768px) {
    .testimonials__carousel-wrapper {
        padding: 0 30px;
    }
    
    .testimonial__card {
        padding: 25px !important;
    }
    
    .testimonial__image div {
        width: 100px !important;
        height: 100px !important;
    }
    
    .section__heading--title {
        font-size: 2rem !important;
    }
    
    .section__heading--desc {
        font-size: 1.1rem !important;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.testimonialsSwiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {{ ($testimonialsConfig->autoplay ?? true) ? 'true' : 'false' }},
        autoplay: {
            delay: {{ $testimonialsConfig->autoplay_speed ?? 5000 }},
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 1,
            },
            1024: {
                slidesPerView: 1,
            },
        }
    });
});
</script>
@endpush