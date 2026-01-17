@extends('web.layouts.base')

@section('title', $articulo->nombre)

@push('styles')
<style>
    .product__details--wrapper { padding: 60px 0; background: #fff; }
    
    .product__media--container {
        border: 1px solid #f0f0f0;
        border-radius: 20px;
        overflow: hidden;
        position: sticky;
        top: 20px;
        background: #fff;
    }

    .badge__puntos {
        background: var(--secondary-color-pastel);
        color: var(--secondary-color);
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        border: 1px dashed var(--secondary-color);
    }

    .product__price--value {
        font-size: 3rem;
        font-weight: 800;
        color: var(--primary-color);
        display: block;
        margin: 15px 0;
    }

    /* Estilos para los selectores de variantes */
    .variant__label {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        color: #888;
        display: block;
        margin-bottom: 8px;
    }

    .form-select-custom {
        border-radius: 12px;
        padding: 12px;
        border: 1.5px solid #eee;
        font-weight: 600;
        color: var(--primary-color);
    }

    .form-select-custom:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(255, 0, 127, 0.1);
    }

    .info__table { width: 100%; border-collapse: collapse; }
    .info__table td { padding: 12px 0; border-bottom: 1px solid #f5f5f5; }
    .info__label { color: #888; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; width: 35%; }
    .info__value { color: var(--primary-color); font-weight: 700; }

    .btn__add--cart {
        background: var(--secondary-color);
        color: white;
        border: none;
        padding: 18px 30px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        width: 100%;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn__add--cart:hover:not(:disabled) { 
        filter: brightness(1.1); 
        transform: translateY(-3px); 
    }

    .btn__add--cart:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<main class="main__content_wrapper">
    <section class="product__details--wrapper">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="product__media--container">
                        <img src="{{ asset('storage/' . ($articulo->imagen_full ?? $articulo->imagen)) }}" 
                             alt="{{ $articulo->nombre }}" 
                             class="img-fluid w-100"
                             onerror="this.src='{{ asset('assets/img/no-product.png') }}'">
                    </div>
                </div>

                <div class="col-lg-6 ps-lg-5">
                    <div class="product__info--content">
                        
                        <div class="badge__puntos mb-3">
                            <i class="fas fa-gem me-2"></i> Gana {{ floor($articulo->precio_venta) }} puntos Estilo Store
                        </div>

                        <h1 class="fw-bold mb-2" style="color: var(--primary-color);">{{ $articulo->nombre }}</h1>
                        <p class="text-muted mb-4 small fs-4">CÓDIGO: {{ $articulo->sku }}</p>

                        <span class="product__price--value">
                            ${{ number_format($articulo->precio_venta, 2) }}
                        </span>

                        <form id="form-add-to-cart" action="{{route('web.carrito.add')}}" method="POST">
                            @csrf
                            <input type="hidden" name="articulo_id" value="{{ $articulo->id }}">

                            <div class="row mb-4">
                                @if(isset($articulo->opciones['colores']) && $articulo->opciones['colores']->count() > 0)
                                <div class="col-md-6 mb-3">
                                    <label class="variant__label fs-4">Color</label>
                                    <select name="color_id" class="form-select form-select-custom fs-4" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($articulo->opciones['colores'] as $color)
                                            <option value="{{ $color->id }}">{{ $color->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                @if(isset($articulo->opciones['tallas']) && $articulo->opciones['tallas']->count() > 0)
                                <div class="col-md-6 mb-3">
                                    <label class="variant__label fs-4">Talla</label>
                                    <select name="talla_id" class="form-select form-select-custom fs-4" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($articulo->opciones['tallas'] as $talla)
                                            <option value="{{ $talla->id }}">{{ $talla->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>

                            <div class="row g-3 align-items-center mb-5">
                                <div class="col-md-4">
                                    <label class="variant__label fs-5">Cantidad</label>
                                    <div class="input-group border rounded-pill p-1">
                                        <button type="button" class="btn btn-sm fs-5" onclick="document.getElementById('qty').stepDown()">-</button>
                                        <input type="number" id="qty" name="cantidad" class="form-control border-0 text-center bg-transparent fw-bold fs-5" value="1" min="1">
                                        <button type="button" class="btn btn-sm fs-5" onclick="document.getElementById('qty').stepUp()">+</button>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="variant__label d-none d-md-block">&nbsp;</label>
                                    <button type="submit" class="btn__add--cart" {{ ($articulo->stock ?? 1) <= 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-shopping-basket"></i> 
                                        <span class="btn-text">Añadir al Pedido</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="product__specs mb-5">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">Detalles del Producto</h5>
                            <table class="info__table">
                                <tr>
                                    <td class="info__label fs-5">Categoría</td>
                                    <td class="info__value fs-5">{{ $articulo->categoria->nombre ?? 'General' }}</td>
                                </tr>
                                <tr>
                                    <td class="info__label fs-5">Marca</td>
                                    <td class="info__value fs-5">{{ $articulo->marca->nombre ?? 'Ser Dama' }}</td>
                                </tr>
                                <tr>
                                    <td class="info__label fs-5">Descripción</td>
                                    <td class="info__value fs-5" style="font-weight: 400; color: #666;">
                                        {{ $articulo->descripcion ?? 'Producto Ser Dama.' }}
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div> </div>
        </div>
    </section>
</main>

@push('scripts')
<script>
    // Clonamos el stock inicial para manejarlo localmente
    let stockData = @json($articulo->mapa_stock);
    const form = document.getElementById('form-add-to-cart');
    const btnAdd = document.querySelector('.btn__add--cart');
    const btnText = btnAdd.querySelector('.btn-text');
    const qtyInput = document.getElementById('qty');

    function actualizarStock() {
        const colorId = document.querySelector('select[name="color_id"]')?.value || 0;
        const tallaId = document.querySelector('select[name="talla_id"]')?.value || 0;
        const key = `${colorId}-${tallaId}`;
        
        const stockDisponible = stockData[key] || 0;

        if (stockDisponible > 0) {
            qtyInput.max = stockDisponible;
            if(parseInt(qtyInput.value) > stockDisponible) qtyInput.value = stockDisponible;
            if(parseInt(qtyInput.value) <= 0) qtyInput.value = 1;
            
            btnAdd.disabled = false;
            btnText.innerText = `Añadir al Pedido (${stockDisponible} disp.)`;
        } else {
            qtyInput.value = 0;
            btnAdd.disabled = true;
            btnText.innerText = `Agotado`;
        }
    }

    // Escuchar cambios en los selects
    document.querySelectorAll('.form-select-custom').forEach(select => {
        select.addEventListener('change', actualizarStock);
    });

    // Manejar el envío AJAX
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        const colorId = formData.get('color_id') || 0;
        const tallaId = formData.get('talla_id') || 0;
        const cantSolicitada = parseInt(formData.get('cantidad'));
        const key = `${colorId}-${tallaId}`;

        // Bloquear botón mientras procesa
        btnAdd.disabled = true;
        btnText.innerText = "Procesando...";

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                // RESTAR STOCK LOCALMENTE
                if (stockData[key]) {
                    stockData[key] -= cantSolicitada;
                }
                
                // Actualizar interfaz
                actualizarStock();
                
                // Opcional: Notificar éxito (puedes cambiar esto por un Toast)
                alert("¡Producto añadido con éxito!");
            } else {
                alert(result.error || "Error al añadir al carrito");
                actualizarStock();
            }
        } catch (error) {
            console.error(error);
            alert("Error de conexión al servidor");
            actualizarStock();
        }
    });

    // Ejecutar al cargar por si hay pre-selección
    actualizarStock();
</script>
@endpush
@endsection