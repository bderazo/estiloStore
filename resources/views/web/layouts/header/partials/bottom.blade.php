    <div class="header__bottom">
        <div class="container-fluid">
            <div class="header__bottom--inner position__relative d-none d-lg-flex justify-content-between align-items-center">
                <div class="header__menu">
                    <nav class="header__menu--navigation">
                        <ul class="d-flex">
                            <li class="header__menu--items">
                                <a class="header__menu--link" href="{{ url('/') }}">Inicio
                                </a>

                            </li>
                            <li class="header__menu--items mega__menu--items">
                                <a class="header__menu--link" href="#">Tienda
                                    <svg class="menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12" height="7.41" viewBox="0 0 12 7.41">
                                        <path d="M16.59,8.59,12,13.17,7.41,8.59,6,10l6,6,6-6Z" transform="translate(-6 -8.59)" fill="currentColor" opacity="0.7" />
                                    </svg>
                                </a>
                                <ul class="header__mega--menu d-flex">
                                    
                                    @foreach($categoriasMenu as $padre)
                                    <li class="header__mega--menu__li">
                                        <span class="header__mega--subtitle">{{ $padre->nombre }}</span>
                                
                                        @if($padre->children->count() > 0)
                                        <ul class="header__mega--sub__menu">
                                            @foreach($padre->children as $hijo)
                                            <li class="header__mega--sub__menu_li">
                                                <a class="header__mega--sub__menu--title" href="{{ route('tienda.categoria', $hijo->slug) }}">
                                                    {{ $hijo->nombre }}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                                {{--
                                <ul class="header__mega--menu d-flex">
                                    <li class="header__mega--menu__li">
                                        <span class="header__mega--subtitle">Columna Uno</span>
                                        <ul class="header__mega--sub__menu">
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="shop.html">Tienda con barra lateral izquierda</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="shop-right-sidebar.html">Tienda con barra lateral derecha</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="shop-grid.html">Tienda en cuadrícula</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="shop-grid-list.html">Tienda lista y cuadrícula</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="shop-list.html">Tienda en lista</a></li>
                                        </ul>
                                    </li>
                                    <li class="header__mega--menu__li">
                                        <span class="header__mega--subtitle">Columna Dos</span>
                                        <ul class="header__mega--sub__menu">
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="product-details.html">Detalles del producto</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="product-video.html">Producto con vídeo</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="product-details.html">Producto variable</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="product-left-sidebar.html">Producto con barra lateral izquierda</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="product-gallery.html">Galería de productos</a></li>
                                        </ul>
                                    </li>
                                        <li class="header__mega--menu__li">
                                        <span class="header__mega--subtitle">Columna Tres</span>
                                        <ul class="header__mega--sub__menu">
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="my-account.html">Mi cuenta</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="my-account-2.html">Mi cuenta 2</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="404.html">Página 404</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="login.html">Página de inicio de sesión</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="faq.html">Página de preguntas frecuentes</a></li>
                                        </ul>
                                    </li>
                                    <li class="header__mega--menu__li">
                                        <span class="header__mega--subtitle">Columna Cuatro</span>
                                        <ul class="header__mega--sub__menu">
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="compare.html">Páginas de comparación</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="checkout.html">Página de pago</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="checkout-2.html">Pago estilo 2</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="checkout-3.html">Pago estilo 3</a></li>
                                            <li class="header__mega--sub__menu_li"><a class="header__mega--sub__menu--title" href="checkout-4.html">Pago estilo 4</a></li>
                                        </ul>
                                    </li>
                                </ul>--}}
                            </li>

                            <li class="header__menu--items d-none d-xl-block">
                                <a class="header__menu--link" href="{{route('categorias')}}">Categorías </a>
                            </li>
                            
                            <li class="header__menu--items">
                                <a class="header__menu--link" href="{{route('nosotros')}}">Sobre nosotros </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
