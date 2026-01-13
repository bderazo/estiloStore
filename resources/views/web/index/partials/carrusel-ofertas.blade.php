@if(count($ofertas)>0)
<div class="ofertas__section section--padding" style="background: var(--secondary-color-pastel);">
        <div class="container">
        <div class="section__heading mb-40">
            <h2 class="section__heading--title text-center" style="color: var(--primary-color); font-weight: 800;">Ofertas Especiales</h2>
            <p style="color: #666;">Grandes descuentos por tiempo limitado</p>
        </div>

        <div class="row g-4">
            @foreach($ofertas as $prod)
            <div class="col-md-6 col-lg-4">
                {{-- La Tarjeta con el Borde Fucsia --}}
                <div class="offer-card" style="
                    display: flex; 
                    background: white; 
                    border-radius: 15px; 
                    overflow: hidden; 
                    border: 2px solid var(--secondary-color); {{-- AQUÍ EL BORDE FUCSIA --}}
                    box-shadow: 0 8px 20px rgba(255, 0, 127, 0.1);
                    height: 190px;
                    transition: all 0.3s ease;
                    position: relative;
                ">
                    {{-- Lado Izquierdo: Imagen --}}
                    <div style="width: 40%; background: #f9f9f9; position: relative; overflow: hidden;">
                        <img src="{{ ($prod->getImagenPrincipalAttribute() && $prod->getImagenPrincipalAttribute()!='default.png') ? asset('storage/articulos/'.$prod->id.'/'.$prod->getImagenPrincipalAttribute()) : 'https://via.placeholder.com/200' }}" 
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                             class="img-oferta">
                        
                        {{-- Badge de Descuento --}}
                        <div style="
                            position: absolute; 
                            top: 0; 
                            left: 0; 
                            background: var(--secondary-color); 
                            color: white; 
                            padding: 5px 12px; 
                            font-weight: 800; 
                            font-size: 0.8rem; 
                            border-bottom-right-radius: 15px;
                            z-index: 2;
                        ">
                            -{{ $prod->porcentaje_descuento }}%
                        </div>
                    </div>

                    {{-- Lado Derecho: Contenido --}}
                    <div style="width: 60%; padding: 15px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <span style="font-size: 0.7rem; color: var(--secondary-color); font-weight: 700; text-transform: uppercase;">
                                {{ $prod->marca->nombre ?? 'Exclusivo' }}
                            </span>
                            <h3 style="font-size: 1rem; margin: 3px 0; color: #333; font-weight: 700; line-height: 1.2;">
                                {{ $prod->nombre }}
                            </h3>
                        </div>
                        
                        <div>
                            <div class="prices" style="margin-bottom: 10px;">
                                <div style="color: #bbb; text-decoration: line-through; font-size: 0.85rem;">
                                    ${{ number_format($prod->precio, 2) }}
                                </div>
                                <div style="color: var(--secondary-color); font-size: 1.3rem; font-weight: 900;">
                                    ${{ number_format($prod->precio_oferta, 2) }}
                                </div>
                            </div>

                            <a class="btn-ver-oferta" 
                                    href="{{route('web.articulo.detalle', $prod->slug)}}"
                                    style="
                                        width: 100%;
                                        background: var(--secondary-color); 
                                        color: white; 
                                        border: none; 
                                        border-radius: 8px; 
                                        padding: 6px; 
                                        font-size: 0.8rem; 
                                        font-weight: bold; 
                                        text-transform: uppercase;
                                        transition: all 0.3s;
                                    ">
                                Aprovechar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<style>
    /* Efectos de Interacción */
    .offer-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(255, 0, 127, 0.2);
        background: #fffcfd; /* Un fondo rosado casi imperceptible al hover */
    }

    .offer-card:hover .img-oferta {
        transform: scale(1.1);
    }

    .btn-ver-oferta:hover {
        filter: brightness(1.2);
        letter-spacing: 1px;
    }
</style>


 

 