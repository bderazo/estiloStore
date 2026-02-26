<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoExito extends Model
{
    use HasFactory;

    protected $table = 'videos_exito';

    protected $fillable = [
        'titulo',
        'subtitulo',
        'descripcion',
        'url_youtube',
        'youtube_id',
        'thumbnail_url',
        'nombre_persona',
        'cargo_persona',
        'orden',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer'
    ];

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden');
    }

    // Accessors
    public function getThumbnailAttribute(): string
    {
        if ($this->thumbnail_url) {
            return asset('storage/' . $this->thumbnail_url);
        }
        
        // Thumbnail por defecto de YouTube
        return "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg";
    }

    public function getEmbedUrlAttribute(): string
    {
        return "https://www.youtube.com/embed/{$this->youtube_id}";
    }

    public function getWatchUrlAttribute(): string
    {
        return "https://www.youtube.com/watch?v={$this->youtube_id}";
    }

    // Mutators
    public function setUrlYoutubeAttribute($value)
    {
        $this->attributes['url_youtube'] = $value;
        $this->attributes['youtube_id'] = $this->extractYoutubeId($value);
    }

    // Helper para extraer ID de YouTube
    private function extractYoutubeId($url)
    {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return $matches[1] ?? null;
    }
}