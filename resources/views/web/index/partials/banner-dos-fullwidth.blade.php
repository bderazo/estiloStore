@if($publicidadDos && $publicidadDos->imagen_ruta)
<!-- Inicio banner ancho completo -->
        <section class="banner__section section--padding pt-0">
            <div class="container-fluid">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="banner__section--inner position__relative">
                            <a class="banner__items--thumbnail display-block" ><img
                                    class="banner__items--thumbnail__img banner__img--height__md display-block"
                                    src="{{asset('storage/' . $publicidadDos->imagen_ruta)}}" alt="banner-img">
                                <div class="banner__content--style2">
                                    <h2 class="banner__content--style2__title text-white">{{$publicidadDos->titulo}} </h2>
                                    <p class="banner__content--style2__desc">{{$publicidadDos->subtitulo}}</p>                                    
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Fin banner ancho completo -->
@endif