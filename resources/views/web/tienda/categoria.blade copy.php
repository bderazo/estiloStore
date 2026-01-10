@extends('web.layouts.base')

@section('title', 'Categoría: ' . $categoria->nombre)
@section('content')
<style>
    /* Efecto de presión al hacer clic */
    .btn-cerrar-modal:active {
        transform: scale(0.98);
        filter: brightness(0.9);
    }
    /* Asegurarnos que la X de arriba también sea fácil de ver */
    .btn-close {
        opacity: 0.8;
        transition: transform 0.2s;
    }
    .btn-close:hover {
        transform: rotate(90deg);
        opacity: 1;
    }
</style>
<section class="shop__section section--padding">
    <div class="container">
        <div class="shop__header bg__gray--color d-flex align-items-center justify-content-between mb-30">
            <h2 class="shop__header--title">{{ $categoria->nombre }}</h2>
            <p class="shop__header--desc">Mostrando {{ $articulos->count() }} productos</p>
        </div>

        <div class="shop__product--wrapper">
            {{-- Fila principal: 3 columnas en pantallas grandes (xl), 2 en medianas (md) --}}
            <div class="row row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-1 g-4">

                @forelse($articulos as $articulo)
                <div class="col">
                    {{-- Tarjeta Horizontal Compacta --}}
                    <div class="product__items"
                        style="border: 2px solid var(--secondary-color); border-radius: 10px; overflow: hidden; background: #fff; height: 100%; transition: all 0.3s ease;">
                        <div class="d-flex align-items-center" style="height: 100%;">

                            {{-- Lado Izquierdo: Imagen --}}
                            <div style="width: 40%; min-width: 120px; background: #fff; padding: 10px;">
                                <div class="product__items--thumbnail" style="position: relative; padding-top: 100%;">
                                    <div
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <img class="product__items--img"
                                            src="{{ asset('storage/' . $articulo->imagen_principal) }}"
                                            alt="{{ $articulo->nombre }}"
                                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                </div>
                            </div>

                            {{-- Lado Derecho: Info y Botón --}}
                            <div
                                style="width: 60%; padding: 15px; display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                <div>
                                    <h3 class="product__items--content__title"
                                        style="font-size: 0.95rem; margin-bottom: 5px; line-height: 1.2; font-weight: 700;">
                                        <a href="#" style="color: #333;">{{ Str::limit($articulo->nombre, 40) }}</a>
                                    </h3>
                                    <p style="color: #888; font-size: 0.75rem; margin-bottom: 8px;">
                                        SKU: {{ $articulo->id }}
                                    </p>
                                </div>

                                <div>
                                    <div class="product__items--price" style="margin-bottom: 10px;">
                                        <span class="current__price"
                                            style="font-weight: 800; font-size: 1.1rem; color: var(--secondary-color);">
                                            ${{ number_format($articulo->precio, 2) }}
                                        </span>
                                    </div>

                                    {{-- AQUÍ SUSTITUIMOS EL ENLACE POR TU NUEVO BOTÓN --}}
                                    <button type="button" class="btn btn-view-image"
                                        data-nombre="{{ $articulo->nombre }}"
                                        data-url="{{ asset('storage/' . $articulo->imagen_principal) }}"
                                        style="width: 100%; background-color: var(--secondary-color); color: white; border-radius: 4px; padding: 6px 0; font-size: 0.8rem; font-weight: bold; border: none; cursor: pointer;">
                                        Ver Imagen +
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p>No hay productos en esta categoría.</p>
                </div>
                @endforelse

            </div>
        </div>

        <div class="pagination__area mt-40">
            <nav class="pagination justify-content-center">
                {{ $articulos->links() }}
            </nav>
        </div>
    </div>
</section>
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content"
            style="border: 3px solid var(--secondary-color); border-radius: 15px; overflow: hidden;">
            <div class="modal-header" style="border-bottom: 1px solid #eee; padding: 10px 20px; background: #fff;">
                <h5 class="modal-title" id="modalProductoNombre"
                    style="color: var(--secondary-color); font-weight: bold; font-size: 1rem;"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="filter: contrast(0.5);"></button>
            </div>
            <div class="modal-body text-center"
                style="padding: 20px; background: #f9f9f9; min-height: 300px; display: flex; align-items: center; justify-content: center;">

                <img id="modalImagenSource" src="" alt=""
                    style="max-width: 100%; max-height: 400px; border-radius: 8px; display: none; object-fit: contain;">

                <div id="noImageData" style="display: none; color: #999;">
                    <i class="fas fa-image" style="font-size: 3rem; display: block; margin-bottom: 10px;"></i>
                    <p style="font-weight: 500;">Imagen no disponible</p>
                </div>

            </div>
            <div class="modal-footer" style="border-top: none; padding: 10px 20px;">
                <button type="button" class="btn" data-bs-dismiss="modal"
                    style="background: var(--secondary-color); color: white; width: 100%; font-weight: bold;">CERRAR</button>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('imagePreviewModal');
    const modalImg = document.getElementById('modalImagenSource');
    const modalTitle = document.getElementById('modalProductoNombre');
    const noImageDiv = document.getElementById('noImageData');

    // Inicializar Modal
    const imageModal = new bootstrap.Modal(modalElement);

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-view-image');
        
        if (btn) {
            e.preventDefault();
            const nombre = btn.getAttribute('data-nombre');
            const url = btn.getAttribute('data-url');

            modalTitle.innerText = nombre;
            
            // Validar si la URL existe o no es la por defecto
            if (url && !url.includes('default.png')) {
                modalImg.src = url;
                modalImg.style.display = 'block';
                noImageDiv.style.display = 'none';
            } else {
                modalImg.style.display = 'none';
                noImageDiv.style.display = 'block';
            }

            imageModal.show();
        }
    });

    // Resetear al cerrar para evitar "flashes" de la imagen anterior
    modalElement.addEventListener('hidden.bs.modal', function () {
        modalImg.src = '';
        modalImg.style.display = 'none';
        noImageDiv.style.display = 'none';
    });
});
</script>