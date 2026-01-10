<!-- Start Offcanvas header menu -->
<div class="offcanvas__header">
    <div class="offcanvas__inner">
        <div class="offcanvas__logo">
            <a class="offcanvas__logo_link" href="">
                <img src="{{asset('web/assets/imagenes/nav-log.png')}}" alt="Grocee Logo" width="158" height="36">
            </a>
            <button class="offcanvas__close--btn" data-offcanvas>Cerrar</button>
        </div>
        <nav class="offcanvas__menu">
            <ul class="offcanvas__menu_ul">
                <li class="offcanvas__menu_li">
                    <a class="offcanvas__menu_item" href="{{ url('/') }}">Inicio</a>
                </li>
                <li class="offcanvas__menu_li">
                    <a class="offcanvas__menu_item" href="#">Tienda</a>
                </li>

                <li class="offcanvas__menu_li"><a class="offcanvas__menu_item" href="/nosotros">Acerca de</a></li>
                </li>
            </ul>
            <div class="offcanvas__account--items">
                <a class="offcanvas__account--items__btn d-flex align-items-center" href="login.html">
                    <span class="offcanvas__account--items__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20.51" height="19.443" viewBox="0 0 512 512">
                            <path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="32" />
                            <path
                                d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z"
                                fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
                        </svg>
                    </span>
                    <span class="offcanvas__account--items__label">Iniciar sesión / Registrarse</span>
                </a>
            </div>
        </nav>
    </div>
</div>
<!-- End Offcanvas header menu -->
