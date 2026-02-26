<a href="https://www.youtube.com/watch?v={{ $video['youtube_id'] }}" target="_blank" class="video__card"
    style="text-decoration: none; color: inherit; cursor: pointer;">
    <div class="video__thumbnail">
        <img src="{{ $video['thumbnail'] }}" alt="{{ $video['titulo'] }}">
        <div class="video__play-icon">
            <!-- <i class="fas fa-play"></i> -->
        </div>
    </div>
    <div class="video__info">
        <h3 class="video__title">{{ $video['titulo'] }}</h3>
        <p class="video__person">{{ $video['nombre_persona'] }}</p>

    </div>
</a>