    <div class="main__header header__sticky">
        <div class="container-fluid">
            <div class="main__header--inner position__relative d-flex justify-content-between align-items-center">
                <div class="offcanvas__header--menu__open ">
                    <a class="offcanvas__header--menu__open--btn" href="javascript:void(0)" data-offcanvas>
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon offcanvas__header--menu__open--svg" viewBox="0 0 512 512">
                            <path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 160h352M80 256h352M80 352h352" />
                        </svg>
                        <span class="visually-hidden">Abrir menú</span>
                    </a>
                </div>
                <div class="main__logo">
                    <h1 class="main__logo--title"><a class="main__logo--link" href="{{ url('/') }}"><img class="main__logo--img" src="{{asset('web/assets/imagenes/nav-log.png')}}" alt="logo-img"></a>
                    </h1>
                </div>
                <div class="header__search--widget header__sticky--none d-none d-lg-block">
                    <form class="d-flex header__search--form" action="#">
                        <div class="header__select--categories select">
                            <select class="header__select--inner">
                                <option selected value="1">Todas las categorías</option>
                                <option value="2">Accesorios</option>
                                <option value="3">Accesorios y más</option>
                                <option value="4">Cámaras y vídeo</option>
                                <option value="5">Mantequillas y huevos</option>
                            </select>
                        </div>
                        <div class="header__search--box">
                            <label>
                                <input class="header__search--input" placeholder="Palabra clave..." type="text">
                            </label>
                            <button class="header__search--button bg__secondary text-white" type="submit" aria-label="botón de búsqueda">
                                <svg class="header__search--button__svg" xmlns="http://www.w3.org/2000/svg" width="27.51" height="26.443" viewBox="0 0 512 512">
                                    <path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32">
                                    </path>
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
 <div class="header__account header__sticky--none">
    <ul class="d-flex align-items-center">
        <li class="header__account--items">
            <a class="header__account--btn" href="{{ route('login')}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="26.51" height="23.443" viewBox="0 0 512 512">
                    <path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                    <path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
                </svg>
                <span class="header__account--btn__text">Mi cuenta</span>
            </a>
        </li>

        <li class="header__account--items">
            <a class="header__account--btn " href="{{Auth::id() ? '#':route('web.carrito.index')}}" data-offcanvas>
                <svg xmlns="http://www.w3.org/2000/svg" width="26.51" height="23.443" viewBox="0 0 14.706 13.534">
                    <g transform="translate(0 0)">
                        <path data-name="Path 16787" d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z" transform="translate(0 -463.248)" fill="currentColor" />
                    </g>
                </svg>
                <span class="header__account--btn__text">Mi carrito</span>
                <!--<span class="items__count">02</span>-->
            </a>
        </li>

        @if(isset($folletoPrincipal))
        <li class="header__account--items">
            <a class="header__account--btn" href="{{ route('folleto.descargar', $folletoPrincipal->id) }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="26.51" height="23.443" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span class="header__account--btn__text">Catálogo</span>
            </a>
        </li>
        @endif

        <li class="header__account--items">
            <a class="header__account--btn" target="_blank" href="https://wa.me/593990273691">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#25D366" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.012 2c-5.508 0-9.987 4.479-9.987 9.987 0 1.757.463 3.471 1.341 4.977L2 22l5.192-1.362c1.441.785 3.059 1.2 4.82 1.2 5.508 0 9.987-4.479 9.987-9.987 0-5.508-4.479-9.987-9.987-9.987Zm4.665 14.126c-.211.594-1.214 1.134-1.674 1.201-.46.066-1.042.083-1.674-.121a8.423 8.423 0 0 1-3.692-2.316 9.3 9.3 0 0 1-2.203-3.64c-.26-.732-.236-1.282.115-1.724.163-.205.358-.403.537-.604.179-.202.239-.345.358-.574.12-.23.06-.431-.03-.632-.09-.201-.806-1.941-1.105-2.662-.29-.703-.584-.608-.806-.619-.205-.01-.44-.011-.673-.011-.233 0-.613.087-.933.437-.321.35-1.223 1.196-1.223 2.91 0 1.714 1.253 3.37 1.428 3.607.175.237 2.463 3.759 5.964 5.271.833.36 1.483.575 1.99.736.837.266 1.598.229 2.198.14.67-.1 2.059-.841 2.352-1.652.293-.811.293-1.506.205-1.652-.088-.146-.321-.233-.674-.409s-2.074-1.024-2.396-1.141c-.322-.116-.557-.174-.792.174s-.91 1.141-1.115 1.375c-.205.233-.409.26-.763.085s-1.49-.55-2.839-1.754c-1.05-.936-1.759-2.092-1.964-2.443-.205-.351-.022-.54.154-.714.158-.156.351-.409.526-.614.175-.205.233-.35.351-.584.117-.234.058-.439-.029-.614-.088-.175-.792-1.914-1.085-2.617-.285-.688-.577-.595-.792-.606-.21-.01-.447-.01-.683-.01-.237 0-.623.088-.95.441-.326.353-1.244 1.206-1.244 2.936 0 1.729 1.264 3.399 1.442 3.637.177.238 2.485 3.791 6.014 5.316.84.363 1.495.58 2.007.742.843.268 1.611.231 2.217.141.676-.1 2.077-.849 2.372-1.666.296-.818.296-1.52.207-1.666-.088-.147-.326-.235-.683-.412Z"/>
                </svg>
                <span class="header__account--btn__text">WhatsApp</span>
            </a>
        </li>
    </ul>
</div>
                <div class="header__menu d-none header__sticky--block d-lg-block">
                    <nav class="header__menu--navigation">
                        <ul class="d-flex">
                            <li class="header__menu--items style2">
                                <a class="header__menu--link" href="{{ url('/') }}">Inicio</a>

                            </li>
                            <li class="header__menu--items mega__menu--items style2">
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

                            <li class="header__menu--items style2 d-none d-xl-block">
                                <a class="header__menu--link" href="{{route('categorias')}}">Categorías </a>
                            </li>
                            <li class="header__menu--items style2">
                                <a class="header__menu--link" href="{{route('nosotros')}}">Sobre nosotros </a>
                            </li>

                        </ul>
                    </nav>
                </div>
                <div class="header__account header__account2 header__sticky--block">
                    <ul class="d-flex">
                        <li class="header__account--items header__account2--items  header__account--search__items d-none d-lg-block">
                            <a class="header__account--btn search__open--btn" href="javascript:void(0)" data-offcanvas>
                                <svg class="header__search--button__svg" xmlns="http://www.w3.org/2000/svg" width="26.51" height="23.443" viewBox="0 0 512 512">
                                    <path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448" />
                                </svg>
                                <span class="visually-hidden">Buscar</span>
                            </a>
                        </li>
                        <li class="header__account--items header__account2--items">
                            <a class="header__account--btn" href="{{ route('login')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26.51" height="23.443" viewBox="0 0 512 512">
                                    <path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                    <path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
                                </svg>
                                <span class="visually-hidden">Mi cuenta</span>
                            </a>
                        </li>

                        <li class="header__account--items header__account2--items">
                            <a class="header__account--btn --btn" href="route('web.carrito.index')" data-offcanvas>
                                <svg xmlns="http://www.w3.org/2000/svg" width="26.51" height="23.443" viewBox="0 0 14.706 13.534">
                                    <g transform="translate(0 0)">
                                        <g>
                                            <path data-name="Path 16787" d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z" transform="translate(0 -463.248)" fill="currentColor" />
                                            <path data-name="Path 16788" d="M5.5,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,5.5,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,6.793,478.352Z" transform="translate(-1.191 -466.622)" fill="currentColor" />
                                            <path data-name="Path 16789" d="M13.273,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,13.273,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,14.566,478.352Z" transform="translate(-2.875 -466.622)" fill="currentColor" />
                                        </g>
                                    </g>
                                </svg>
                                <!--<span class="items__count style2">02</span>-->
                            </a>
                        </li>
                        <li class="header__account--items header__account2--items">
    <a class="header__account--btn" target="_blank" href="https://wa.me/593990273691">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="#25D366" xmlns="http://www.w3.org/2000/svg" style="margin-top: 2px;">
            <path d="M12.012 2c-5.508 0-9.987 4.479-9.987 9.987 0 1.757.463 3.471 1.341 4.977L2 22l5.192-1.362c1.441.785 3.059 1.2 4.82 1.2 5.508 0 9.987-4.479 9.987-9.987 0-5.508-4.479-9.987-9.987-9.987Zm4.665 14.126c-.211.594-1.214 1.134-1.674 1.201-.46.066-1.042.083-1.674-.121a8.423 8.423 0 0 1-3.692-2.316 9.3 9.3 0 0 1-2.203-3.64c-.26-.732-.236-1.282.115-1.724.163-.205.358-.403.537-.604.179-.202.239-.345.358-.574.12-.23.06-.431-.03-.632-.09-.201-.806-1.941-1.105-2.662-.29-.703-.584-.608-.806-.619-.205-.01-.44-.011-.673-.011-.233 0-.613.087-.933.437-.321.35-1.223 1.196-1.223 2.91 0 1.714 1.253 3.37 1.428 3.607.175.237 2.463 3.759 5.964 5.271.833.36 1.483.575 1.99.736.837.266 1.598.229 2.198.14.67-.1 2.059-.841 2.352-1.652.293-.811.293-1.506.205-1.652-.088-.146-.321-.233-.674-.409s-2.074-1.024-2.396-1.141c-.322-.116-.557-.174-.792.174s-.91 1.141-1.115 1.375c-.205.233-.409.26-.763.085s-1.49-.55-2.839-1.754c-1.05-.936-1.759-2.092-1.964-2.443-.205-.351-.022-.54.154-.714.158-.156.351-.409.526-.614.175-.205.233-.35.351-.584.117-.234.058-.439-.029-.614-.088-.175-.792-1.914-1.085-2.617-.285-.688-.577-.595-.792-.606-.21-.01-.447-.01-.683-.01-.237 0-.623.088-.95.441-.326.353-1.244 1.206-1.244 2.936 0 1.729 1.264 3.399 1.442 3.637.177.238 2.485 3.791 6.014 5.316.84.363 1.495.58 2.007.742.843.268 1.611.231 2.217.141.676-.1 2.077-.849 2.372-1.666.296-.818.296-1.52.207-1.666-.088-.147-.326-.235-.683-.412Z"/>
        </svg>
        <span class="visually-hidden">WhatsApp</span>
    </a>
</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
