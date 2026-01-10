<!-- Start header area -->
<header class="header__section">
    {{-- Parte inicial del header --}}
    @include('web.layouts.header.partials.initial-header')
    
    {{-- Encabezado principal sticky con navegación superior flotante--}}
    @include('web.layouts.header.partials.main-header')
    @include('web.layouts.header.partials.bottom')
    {{-- Menú offcanvas lateral completo --}}
    @include('web.layouts.header.partials.offcanvas-menu')
    {{-- Barra flotante offcanvas para accesos rápidos --}}
    @include('web.layouts.header.partials.offcanvas-toolbar')
    {{-- Contenedor del minicart offcanvas --}}
    @include('web.layouts.header.partials.minicart')
    {{-- Caja de búsqueda predictiva --}}
    @include('web.layouts.header.partials.search-box')
</header>
<!-- End header area -->
