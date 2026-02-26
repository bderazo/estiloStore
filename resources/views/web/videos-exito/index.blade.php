<!doctype html>
<html lang="zxx">

<head>
    @include('web.index.partials.head-recursos')
</head>

<body>
    @include('web.layouts.preloader')
    @include('web.layouts.header')

    <main class="main__content_wrapper">
        <!-- Videos Grid -->
        <section class="videos__section section--padding" style="background: #fff; padding: 30px 0;">
            <div class="container">
                <div class="section__heading text-center mb-50">
                    <h2 class="section__heading--title"
                        style="font-size: 2.5rem; font-weight: 800; color: var(--primary-color);">
                        {{ $config->titulo }}
                    </h2>
                    @if($config->subtitulo)
                        <p class="section__heading--desc" style="font-size: 1.2rem; color: #666; margin-top: 15px;">
                            {{ $config->subtitulo }}
                        </p>
                    @endif
                </div>

                <div class="videos__grid" id="videosGrid"
                    style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px;">
                    @foreach($videos as $video)
                        @include('web.videos-exito.partials.video-card', ['video' => $video])
                    @endforeach
                </div>

                @if($config->mostrar_cargar_mas && count($videos) >= $config->videos_por_pagina)
                    <div class="text-center mt-50" id="loadMoreContainer">
                        <button id="loadMoreBtn" class="btn btn-primary"
                            style="padding: 15px 40px; font-size: 1.1rem; border-radius: 50px; background: var(--secondary-color); border: none;"
                            data-next-page="2">
                            Cargar más videos
                        </button>
                    </div>
                @endif

                <div class="text-center mt-50" id="loadingSpinner" style="display: none;">
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
        <style>
            .videos__grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 30px;
            }

            .video__card {
                display: block;
                background: white;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
                text-decoration: none;
                color: inherit;
                cursor: pointer;
                height: 100%;
            }

            .video__card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }

            .video__thumbnail {
                position: relative;
                width: 100%;
                height: 200px;
                overflow: hidden;
            }

            .video__thumbnail img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .video__card:hover .video__thumbnail img {
                transform: scale(1.1);
            }

            .video__play-icon {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.9);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--primary-color);
                font-size: 24px;
                transition: all 0.3s ease;
            }

            .video__card:hover .video__play-icon {
                background: var(--primary-color);
                color: white;
                transform: translate(-50%, -50%) scale(1.1);
            }

            .video__info {
                padding: 20px;
            }

            .video__title {
                font-size: 0.6rem;
                font-weight: 300;
                color: var(--primary-color);
                margin-bottom: 8px;
                line-height: 1.4;
            }

            .video__person {
                font-size: 1rem;
                font-weight: 600;
                color: #333;
                margin-bottom: 4px;
            }

            .video__cargo {
                font-size: 0.9rem;
                color: #666;
            }

            /* Responsive */
            @media (max-width: 1200px) {
                .videos__grid {
                    grid-template-columns: repeat(3, 1fr) !important;
                }
            }

            @media (max-width: 992px) {
                .videos__grid {
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 20px;
                }

                .video__title {
                    font-size: 1.1rem;
                }
            }

            @media (max-width: 576px) {
                .videos__grid {
                    grid-template-columns: 1fr !important;
                }

                .video__thumbnail {
                    height: 180px;
                }
            }

            .mt-50 {
                margin-top: 50px;
            }

            .btn-primary:hover {
                background: var(--primary-color) !important;
                transform: scale(1.05);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                console.log('Videos de éxito inicializados');

                // Cargar más videos
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
                           class="video__card" 
                           style="text-decoration: none; color: inherit; cursor: pointer;">
                            <div class="video__thumbnail">
                                <img src="${video.thumbnail}" alt="${video.titulo}">
                                <div class="video__play-icon">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <div class="video__info">
                                <h3 class="video__title">${video.titulo}</h3>
                                <p class="video__person">${video.nombre_persona}</p>
                                <p class="video__cargo">${video.cargo_persona || ''}</p>
                            </div>
                        </a>
                    `;
            }
        </script>
    @endpush
</body>

</html>