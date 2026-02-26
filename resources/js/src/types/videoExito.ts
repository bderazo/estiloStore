// types/videoExito.ts
export interface VideoExito {
    id: number;
    titulo: string;
    subtitulo: string | null;
    descripcion: string | null;
    url_youtube: string;
    youtube_id: string;
    thumbnail: string | null;
    nombre_persona: string;
    cargo_persona: string | null;
    orden: number;
    activo: boolean;
    created_at: string;
    updated_at: string;
}

export interface VideoExitoFormData {
    titulo: string;
    subtitulo?: string;
    descripcion?: string;
    url_youtube: string;
    youtube_id: string;
    nombre_persona: string;
    cargo_persona?: string;
    orden: number;
    activo: boolean;
    thumbnail?: File | null;
}

export interface VideoExitoConfig {
    titulo: string;
    subtitulo: string;
    videos_por_pagina: number;
    mostrar_cargar_mas: boolean;
}

export interface FiltrosVideoExito {
    search?: string;
    activo?: string | boolean;
    sort_by?: string;
    sort_dir?: 'asc' | 'desc';
    per_page?: number;
}

export interface EstadisticasVideos {
    total: number;
    activos: number;
    inactivos: number;
}