@extends('web.layouts.base')

@section('content')
    <section class="breadcrumb__section breadcrumb__bg"
        style="background-image: url('{{ $bannerPage }}'); min-height: 280px; position: relative;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="breadcrumb__content--title text-white mb-10"
                        style="font-weight: 800; text-shadow: 2px 2px 10px rgba(0,0,0,0.5);">
                        {{ $categoria->nombre }}
                    </h1>
                    <p class="text-white">Explora nuestra colección exclusiva</p>
                </div>
            </div>
        </div>
    </section>

    <section class="shop__section section--padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 mb-4" style="position: relative; z-index: 10;">
                    <aside class="shop__sidebar--widget widget__area shadow-sm p-4"
                        style="background: #fdfdfd; border-radius: 20px;">
                        <h3 class="widget__title"
                            style="color: var(--primary-color); font-weight: 700; border-bottom: 2px solid var(--secondary-color-pastel); padding-bottom: 10px; margin-bottom: 20px;">
                            Categorías
                        </h3>
                        <ul class="widget__categories--menu">
                            @foreach ($categoriasMenu as $menuCat)
                                <li class="mb-2">
                                    <a href="{{ route('tienda.categoria', $menuCat->slug) }}"
                                        class="d-flex justify-content-between align-items-center p-2 {{ $categoria->slug == $menuCat->slug ? 'active-cat' : '' }}"
                                        style="text-decoration: none; color: #555; border-radius: 8px; transition: 0.3s;">
                                        {{ $menuCat->nombre }}
                                        <span
                                            class="badge rounded-pill bg-light text-dark border">{{ $menuCat->articulos_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <hr class="my-4">
                        <h3 class="widget__title" style="color: var(--primary-color); font-weight: 700;">Filtro de Precio
                        </h3>
                        <div class="price__filter--form mt-3">
                            <input type="number" class="form-control mb-2" placeholder="Min">
                            <input type="number" class="form-control mb-2" placeholder="Max">
                            <button class="btn w-100 mt-2"
                                style="background: var(--primary-color); color: white; border-radius: 10px;">Filtrar</button>
                        </div>
                    </aside>
                </div>

                <div class="col-xl-9 col-lg-8">
                    <div class="shop__header d-flex justify-content-between align-items-center mb-4 p-3 bg-light"
                        style="border-radius: 15px;">
                        <p class="mb-0">Mostrando artículos de <strong>{{ $categoria->nombre }}</strong></p>
                        <select class="form-select w-auto border-0 bg-transparent">
                            <option>Ordenar por: Novedades</option>
                            <option>Precio: Menor a Mayor</option>
                            <option>Precio: Mayor a Menor</option>
                        </select>
                    </div>

                    {{-- CORRECCIÓN: gx-4 (espacio lateral) y gy-5 (espacio vertical entre filas) --}}
                    <div class="row gx-4 gy-5">
                        @forelse($articulos as $prod)
                            {{-- CORRECCIÓN: Añadido mb-5 para asegurar que la siguiente fila baje --}}
                            <div class="col-12 col-md-12 col-xl-6 mb-5">

                                <div class="offer-card"
                                    style="
                                display: flex; 
                                background: white; 
                                border-radius: 15px; 
                                overflow: hidden; 
                                border: 2px solid var(--secondary-color); 
                                box-shadow: 0 8px 20px rgba(255, 0, 127, 0.1);
                                height: 210px;
                                transition: all 0.3s ease;
                                position: relative;
                            ">
                                    {{-- Lado Izquierdo: Imagen --}}
                                    <div style="width: 40%; background: #f9f9f9; position: relative; overflow: hidden;">
                                        <img src="{{ $prod->imagenes->first() ? asset('storage/' . $prod->imagenes->first()->url) : asset('web/assets/img/no-image.jpg') }}"
                                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                                            class="img-oferta">

                                        @if ($prod->porcentaje_descuento > 0)
                                            <div
                                                style="position: absolute; top: 0; left: 0; background: var(--secondary-color); color: white; padding: 5px 12px; font-weight: 800; font-size: 0.8rem; border-bottom-right-radius: 15px; z-index: 2;">
                                                -{{ $prod->porcentaje_descuento }}%
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Lado Derecho: Contenido --}}
                                    <div
                                        style="width: 60%; padding: 15px; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div>
                                            <span
                                                style="font-size: 0.7rem; color: var(--secondary-color); font-weight: 700; text-transform: uppercase;">
                                                {{ $prod->marca->nombre ?? 'Exclusivo' }}
                                            </span>
                                            <h3
                                                style="font-size: 1.1rem; margin: 5px 0; color: #333; font-weight: 700; line-height: 1.2;">
                                                {{ $prod->nombre }}
                                            </h3>
                                        </div>

                                        <div>
                                            <div class="prices" style="margin-bottom: 8px;">
                                                @if ($prod->precio_oferta > 0)
                                                    <div
                                                        style="color: #bbb; text-decoration: line-through; font-size: 0.85rem;">
                                                        ${{ number_format($prod->precio, 2) }}
                                                    </div>
                                                    <div
                                                        style="color: var(--secondary-color); font-size: 1.4rem; font-weight: 900;">
                                                        ${{ number_format($prod->precio_oferta, 2) }}
                                                    </div>
                                                @else
                                                    <div
                                                        style="color: var(--primary-color); font-size: 1.4rem; font-weight: 900;">
                                                        ${{ number_format($prod->precio, 2) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <a href="{{ route('web.articulo.detalle', $prod->slug) }}"
                                                class="btn-ver-oferta"
                                                style="width: 100%; background: var(--secondary-color); color: white; border: none; border-radius: 8px; padding: 8px; font-size: 0.8rem; font-weight: bold; text-transform: uppercase; transition: all 0.3s; display: block; text-align: center;">
                                                Ver Detalles
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <img src="{{ asset('web/assets/img/no-products.png') }}"
                                    style="max-width: 150px; opacity: 0.5;">
                                <h3 class="mt-4 text-muted">No encontramos productos en esta categoría</h3>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* ESPACIADO EXTRA PARA FILAS (REFORZADO) */
        .shop__section .row.gy-5 {
            row-gap: 50px !important;
            /* Fuerza el espacio vertical */
        }

        .active-cat {
            background: var(--secondary-color-pastel);
            color: var(--secondary-color) !important;
            font-weight: bold;
        }

        .offer-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(255, 0, 127, 0.3);
            background: #fffcfd;
        }

        .offer-card:hover .img-oferta {
            transform: scale(1.1);
        }

        .btn-ver-oferta:hover {
            filter: brightness(1.2);
            letter-spacing: 1px;
        }

        @media (max-width: 991px) {
            .shop__sidebar--widget {
                margin-bottom: 40px;
            }
        }
    </style>
@endsection
