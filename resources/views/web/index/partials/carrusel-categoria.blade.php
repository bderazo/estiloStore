<div class="category__section" style="padding: 40px 0; background: #fff;">
    <div class="container">
        <div class="section__heading text-center mb-40">
            <h2 class="section__heading--title" style="font-weight: 800; color: var(--primary-color);;">Nuestras Líneas</h2>
            <div style="width: 60px; height: 3px; background: var(--secondary-color); margin: 10px auto;"></div>
        </div>

        {{-- Contenedor: Recto arriba, Curvo abajo --}}
        <div class="category__arc-container" style="
            display: flex; 
            justify-content: center; 
            align-items: stretch; 
            overflow: hidden; 
            border-radius: 0 0 50% 50% / 0 0 15% 15%; {{-- Recto arriba (0 0), Curvo abajo (15% 15%) --}}
            border: 1px solid #eee;
            background: #fff;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        ">
            @foreach($categoriasMenu->whereNull('parent_id')->take(5) as $cat)
                <div class="category__item" style="
                    flex: 1; 
                    height: 400px; 
                    position: relative;
                    border-right: 1px solid #eee;
                    overflow: hidden;
                    background: #fff;
                ">
                    <a href="{{ route('tienda.categoria', $cat->slug) }}" style="display: block; height: 100%; width: 100%; text-decoration: none;">
                        
                        {{-- Texto pegado al margen superior (Recto) --}}
                        <div style="
                            position: absolute; 
                            top: 0; 
                            width: 100%; 
                            background: white; 
                            padding: 20px 10px; 
                            text-align: center;
                            z-index: 10;
                            border-bottom: 1px solid #f5f5f5;
                        ">
                            <span style="color: var(--secondary-color); font-weight: 700; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">
                                {{ $cat->nombre }}
                            </span>
                        </div>

                        {{-- Contenedor de Imagen --}}
                        <div style="
                            width: 100%; 
                            height: 100%; 
                            background-image: url('{{ $cat->imagen ? asset('storage/'.$cat->imagen) : 'https://via.placeholder.com/400x600?text='.$cat->nombre }}');
                            background-size: cover;
                            background-position: center;
                            transition: transform 0.7s ease;
                            padding-top: 60px; {{-- Espacio para que la imagen no empiece debajo del texto --}}
                        " class="category-bg">
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
