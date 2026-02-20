<div class="category__section" style="padding: 40px 0; background: #fff;">
    <div class="container">
        <div class="section__heading text-center mb-40">
            <h2 class="section__heading--title" style="font-weight: 800; color: var(--primary-color);">Nuestras Líneas</h2>
            <div style="width: 60px; height: 3px; background: var(--secondary-color); margin: 10px auto;"></div>
        </div>

        <div class="category__arc-container" style="
            display: flex; 
            justify-content: center; 
            align-items: stretch; 
            overflow: hidden; 
            border-radius: 0 0 50% 50% / 0 0 15% 15%;
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
                    background: #fcfcfc;
                ">
                    <a href="{{ route('tienda.categoria', $cat->slug) }}" style="display: flex; flex-direction: column; height: 100%; text-decoration: none;">
                        
                        {{-- Texto superior fijo --}}
                        <div style="
                            width: 100%; 
                            background: white; 
                            padding: 15px 5px; 
                            text-align: center;
                            z-index: 10;
                            border-bottom: 1px solid #f5f5f5;
                            flex-shrink: 0;
                        ">
                            <span style="color: var(--secondary-color); font-weight: 700; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px;">
                                {{ $cat->nombre }}
                            </span>
                        </div>

                        {{-- Contenedor de Logo ajustado --}}
                        <div style="
                            flex-grow: 1; 
                            width: 100%; 
                            background-image: url('{{ 
                                $cat->logo 
                                    ? asset('storage/'.$cat->logo) 
                                    : ($cat->imagen 
                                        ? asset('storage/'.$cat->imagen) 
                                        : 'https://via.placeholder.com/400x600?text='.$cat->nombre)
                            }}');
                            background-size: contain; 
                            background-repeat: no-repeat;
                            background-position: center center; {{-- Cambiado a center para logo --}}
                            transition: transform 0.5s ease;
                            margin-bottom: 20px;
                        " class="category-bg">
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>