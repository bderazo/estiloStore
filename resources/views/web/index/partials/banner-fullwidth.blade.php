@if($publicidadUno && $publicidadUno->imagen_ruta)
<!-- Inicio banner ancho completo -->
        <section class="banner__section section--padding pt-0">
            <div class="container-fluid">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="banner__section--inner position__relative">
                            <a class="banner__items--thumbnail display-block" href="{{route('login')}}"><img
                                    class="banner__items--thumbnail__img banner__img--height__md display-block"
                                    src="{{asset('storage/' . $publicidadUno->imagen_ruta)}}" alt="banner-img">
                                <div class="banner__content--style2">
                                    <h2 class="banner__content--style2__title text-white">{{$publicidadUno->titulo}} </h2>
                                    <p class="banner__content--style2__desc">{{$publicidadUno->subtitulo}}</p>
                                    <span class="primary__btn">Empieza ahora
                                        <svg class="primary__btn--arrow__icon" xmlns="http://www.w3.org/2000/svg"
                                            width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                            <path
                                                d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z"
                                                transform="translate(-4 -4)" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Fin banner ancho completo -->
@endif