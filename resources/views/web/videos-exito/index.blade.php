<!doctype html>
<html lang="zxx">

<head>
    @include('web.index.partials.head-recursos')
</head>

<body>
    @include('web.layouts.preloader')
    @include('web.layouts.header')

    <main class="main__content_wrapper">
        <section style="background: #fff; padding: 30px 0;">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
                <div class="text-center mb-5">
                    <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--primary-color); margin-bottom: 15px;">
                        {{ $config->titulo }}
                    </h2>
                    @if($config->subtitulo)
                        <p style="font-size: 1.2rem; color: #666;">
                            {{ $config->subtitulo }}
                        </p>
                    @endif
                </div>

                <!-- Grid con estilos directos para probar -->
                <div id="videosGrid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; width: 100%;">
                    @foreach($videos as $video)
                        <a href="https://www.youtube.com/watch?v={{ $video['youtube_id'] }}" 
                           target="_blank" 
                           style="display: block; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-decoration: none; color: inherit; width: 100%;">
                            <div style="position: relative; width: 100%; height: 200px; overflow: hidden;">
                                <img src="{{ $video['thumbnail'] }}" alt="{{ $video['titulo'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 60px; height: 60px; background: rgba(255,255,255,0.9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 24px;">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <div style="padding: 20px;">
                                <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--primary-color); margin-bottom: 8px; line-height: 1.4;">{{ $video['titulo'] }}</h3>
                                <p style="font-size: 1rem; font-weight: 600; color: #333; margin-bottom: 0;">{{ $video['nombre_persona'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if($config->mostrar_cargar_mas && count($videos) >= $config->videos_por_pagina)
                    <div class="text-center mt-5" id="loadMoreContainer">
                        <button id="loadMoreBtn" style="padding: 15px 40px; font-size: 1.1rem; border-radius: 50px; background: var(--secondary-color); border: none; color: white; cursor: pointer;"
                            data-next-page="2">
                            Cargar más videos
                        </button>
                    </div>
                @endif

                <div class="text-center mt-5" id="loadingSpinner" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('web.layouts.footer')
    @include('web.index.partials.modal-quickview')
    @include('web.index.partials.boton-scroll-top')
    @include('web.index.partials.scripts')

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                console.log('Videos de éxito inicializados');

                const loadMoreBtn = document.getElementById('loadMoreBtn');
                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', async function () {
                        const nextPage = this.dataset.nextPage;
                        const loadMoreContainer = document.getElementById('loadMoreContainer');
                        const loadingSpinner = document.getElementById('loadingSpinner');
                        const videosGrid = document.getElementById('videosGrid');

                        loadMoreContainer.style.display = 'none';
                        loadingSpinner.style.display = 'block';

                        try {
                            const response = await fetch(`{{ route('videos.exito.load-more') }}?page=${nextPage}`);
                            const data = await response.json();

                            data.videos.forEach(video => {
                                const videoCard = createVideoCard(video);
                                videosGrid.insertAdjacentHTML('beforeend', videoCard);
                            });

                            if (data.has_more) {
                                loadMoreBtn.dataset.nextPage = data.next_page;
                                loadMoreContainer.style.display = 'block';
                            }

                        } catch (error) {
                            console.error('Error cargando videos:', error);
                        } finally {
                            loadingSpinner.style.display = 'none';
                        }
                    });
                }
            });

            function createVideoCard(video) {
                return `
                    <a href="https://www.youtube.com/watch?v=${video.youtube_id}" 
                       target="_blank" 
                       style="display: block; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-decoration: none; color: inherit; width: 100%;">
                        <div style="position: relative; width: 100%; height: 200px; overflow: hidden;">
                            <img src="${video.thumbnail}" alt="${video.titulo}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 60px; height: 60px; background: rgba(255,255,255,0.9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 24px;">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div style="padding: 20px;">
                            <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--primary-color); margin-bottom: 8px; line-height: 1.4;">${video.titulo}</h3>
                            <p style="font-size: 1rem; font-weight: 600; color: #333; margin-bottom: 0;">${video.nombre_persona}</p>
                            ${video.cargo_persona ? `<p style="font-size: 0.9rem; color: #666; margin-top: 4px;">${video.cargo_persona}</p>` : ''}
                        </div>
                    </a>
                `;
            }
        </script>
    @endpush
</body>

</html>