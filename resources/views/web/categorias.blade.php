<!doctype html>
<html lang="zxx">

<head>
    @include('web.index.partials.head-recursos')
</head>

<body>
    @include('web.layouts.preloader')
    @include('web.layouts.header')

    <main class="main__content_wrapper">

        <section class="breadcrumb__section breadcrumb__bg" 
                 style="background-image: url('{{ $bannerCategorias ? asset('storage/'.$bannerCategorias->imagen) : asset('web/assets/img/other/breadcrumb-bg.jpg') }}'); min-height: 350px; display: flex; align-items: center; position: relative;">
            <div class="container" style="position: relative; z-index: 2;">
                <div class="row">
                    <div class="col-12 text-center">
                        <h1 class="breadcrumb__content--title text-white mb-20" style="font-size: 3rem; font-weight: 800;">Nuestras Colecciones</h1>
                        <nav class="breadcrumb__content--menu d-flex justify-content-center">
                            <ul class="d-flex align-items-center">
                                <li class="breadcrumb__content--menu__items"><a class="text-white" href="{{ route('home') }}">Inicio</a></li>
                                <li class="breadcrumb__content--menu__items"><span class="text-white">Categorías</span></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <section class="categories__page--section section--padding" style="background: #ffffff; padding-top: 80px; padding-bottom: 80px;">
            <div class="container">
                
                <div class="row mb-50 justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h2 class="mb-3" style="color: var(--primary-color); font-weight: 700;">Explora por Estilo</h2>
                        <div class="category__search-box">
                            <input type="text" id="catSearch" class="form-control" placeholder="Busca tu categoría favorita..." 
                                   style="border-radius: 50px; padding: 15px 30px; border: 2px solid var(--secondary-color-pastel); text-align: center;">
                        </div>
                    </div>
                </div>

                <div class="row row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-1 gx-5 gy-5" id="catGrid" style="margin-bottom: 50px;">
                    @foreach($categorias as $cat)
                        @php
                            // Determinar qué imagen mostrar (prioridad: logo > imagen)
                            $imageUrl = $cat->logo 
                                ? asset('storage/'.$cat->logo)
                                : ($cat->imagen 
                                    ? asset('storage/'.$cat->imagen)
                                    : asset('web/assets/img/placeholder-categoria.jpg'));
                            
                            // Clase CSS adicional para logos
                            $imgClass = $cat->logo ? 'has-logo' : 'has-image';
                        @endphp
                        
                        <div class="col cat-card-item" style="margin-bottom: 30px;">
                            <a href="{{ route('tienda.categoria', $cat->slug) }}" class="cat-magazine-card">
                                <div class="cat-magazine-wrapper">
                                    <img src="{{ $imageUrl }}" alt="{{ $cat->nombre }}" 
                                         class="cat-magazine-img {{ $imgClass }}">
                                    
                                    <div class="cat-magazine-overlay">
                                        <div class="cat-info-top">
                                            <span class="cat-badge">{{ $cat->articulos_count ?? 0 }} Artículos</span>
                                        </div>
                                        <div class="cat-info-bottom">
                                            <h3 class="cat-title">{{ $cat->nombre }}</h3>
                                            <div class="cat-explore-btn">Explorar Colección <i class="icon-arrow-right"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <style>
            /* Altura fija para evitar expansión */
            .cat-magazine-card {
                display: block;
                position: relative;
                height: 380px; /* Reducido un poco más */
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                transition: all 0.4s ease;
                background: #eee; /* Color de carga si no hay imagen */
            }

            .cat-magazine-wrapper {
                width: 100%;
                height: 100%;
                position: relative;
            }

            .cat-magazine-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.8s ease;
                display: block;
            }

            /* Estilos específicos para logos */
            .cat-magazine-img.has-logo {
                object-fit: contain;
                background: white;
                padding: 30px;
            }

            /* Estilos específicos para imágenes normales */
            .cat-magazine-img.has-image {
                object-fit: cover;
            }

            /* Overlay de Cristal Transparente por defecto */
            .cat-magazine-overlay {
                position: absolute;
                inset: 0; /* Ocupa todo el espacio */
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 25px;
                /* Gradiente sutil para que el texto sea legible sobre la imagen */
                background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 50%);
                transition: all 0.4s ease;
            }

            .cat-badge {
                background: var(--secondary-color);
                color: white;
                font-size: 0.75rem;
                padding: 6px 15px;
                border-radius: 50px;
                font-weight: 700;
                display: inline-block;
            }

            .cat-title {
                color: white;
                font-size: 1.8rem;
                font-weight: 800;
                margin-bottom: 8px;
                text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            }

            .cat-explore-btn {
                color: white;
                font-size: 0.9rem;
                font-weight: 600;
                opacity: 0;
                transform: translateY(15px);
                transition: all 0.3s ease;
            }

            /* HOVER: Aquí se activa el efecto de color/cristal */
            .cat-magazine-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            }

            .cat-magazine-card:hover .cat-magazine-img {
                transform: scale(1.1);
            }

            /* Ajuste de escala para logos en hover */
            .cat-magazine-card:hover .cat-magazine-img.has-logo {
                transform: scale(1.05); /* Menos zoom para logos */
            }

            .cat-magazine-card:hover .cat-magazine-overlay {
                /* Al pasar el mouse, se aplica el desenfoque y el color */
                background: rgba(var(--primary-color-rgb, 0, 0, 0), 0.6); 
                background-color: rgba(0,0,0,0.4); /* Fallback si no hay variable */
                backdrop-filter: blur(4px);
            }

            .cat-magazine-card:hover .cat-explore-btn {
                opacity: 1;
                transform: translateY(0);
            }

            /* Indicador visual para logos (opcional) */
            .cat-info-top::after {
                content: '';
                display: block;
                width: 20px;
                height: 20px;
                background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ffffff'%3E%3Cpath d='M11.5 2C6.81 2 3 5.81 3 10.5S6.81 19 11.5 19h.5v3c4.86-2.34 8-7 8-11.5C20 5.81 16.19 2 11.5 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z'/%3E%3C/svg%3E");
                background-size: contain;
                margin-left: 10px;
                opacity: 0.7;
                float: right;
                display: none; /* Oculto por defecto */
            }
            
            .has-logo .cat-info-top::after {
                display: inline-block; /* Mostrar solo para logos */
            }

            /* Espaciado de Grid */
            .gy-5 { margin-bottom: 40px; } 

            @media (max-width: 768px) {
                .cat-magazine-card { height: 300px; }
                .cat-magazine-img.has-logo {
                    padding: 20px;
                }
            }
        </style>

        <script>
            document.getElementById('catSearch').addEventListener('keyup', function() {
                let val = this.value.toLowerCase();
                document.querySelectorAll('.cat-card-item').forEach(item => {
                    let title = item.querySelector('.cat-title').innerText.toLowerCase();
                    item.style.display = title.includes(val) ? 'block' : 'none';
                });
            });
        </script>

    </main>

    @include('web.layouts.footer')
    @include('web.index.partials.modal-quickview')
    @include('web.index.partials.boton-scroll-top')
    @include('web.index.partials.scripts')
</body>

</html>