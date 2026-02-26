// types/testimonial.ts
export interface Testimonial {
    id: number;
    nombre: string;
    cargo: string | null;
    testimonio: string;
    rating: number;
    imagen: string;
    imagen_url: string;
    orden: number;
    activo: boolean;
    created_at: string;
    updated_at: string;
}

export interface TestimonialFormData {
    nombre: string;
    cargo?: string;
    testimonio: string;
    rating: number;
    orden: number;
    activo: boolean;
    imagen?: File | null;
}

export interface TestimonialConfig {
    titulo: string;
    subtitulo: string;
    autoplay: boolean;
    autoplay_speed: number;
    mostrar_controles: boolean;
    mostrar_indicadores: boolean;
}

export interface FiltrosTestimonial {
    search?: string;
    rating?: number | string;
    activo?: string | boolean;
    sort_by?: string;
    sort_dir?: "asc" | "desc";
    per_page?: number;
}

export const RATINGS = [
    { value: 5, label: "5 Estrellas" },
    { value: 4, label: "4 Estrellas" },
    { value: 3, label: "3 Estrellas" },
    { value: 2, label: "2 Estrellas" },
    { value: 1, label: "1 Estrella" },
];

// Para estadísticas
export interface EstadisticasTestimonios {
    total: number;
    activos: number;
    inactivos: number;
    promedio_rating: number;
    por_rating: Record<number, number>;
}
