// src/types/transporte.ts

export interface Transporte {
    id: number;
    ruta: string;
    precio: number;
    cooperativa: string;
    estado: boolean;
    tiempo_estimado?: number | null;
    created_at?: string;
    updated_at?: string;
    precio_formateado?: string;
    estado_label?: string;
    tiempo_estimado_formateado?: string;
    links?: {
        self: string;
        edit: string;
        delete: string;
    };
}

export interface TransporteFormData {
    ruta: string;
    precio: number | string;
    cooperativa: string;
    estado: boolean;
    tiempo_estimado?: number | null;
}

export interface TransporteFiltros {
    search?: string;
    cooperativa?: string;
    precio_min?: number | null;
    precio_max?: number | null;
    estado?: boolean | string;
    order_by?: 'ruta' | 'precio' | 'cooperativa' | 'created_at';
    order_dir?: 'asc' | 'desc';
    per_page?: number;
    page?: number;
}

export interface PaginationMeta {
    total: number;
    current_page: number;
    last_page: number;
    per_page: number;
    from: number;
    to: number;
}

export interface TransporteResponse {
    data: Transporte[];
    meta: PaginationMeta;
    links: {
        first: string | null;
        last: string | null;
        prev: string | null;
        next: string | null;
    };
}

export interface EstadisticasTransporte {
    total: number;
    activos: number;
    inactivos: number;
    precio_promedio: number;
    cooperativas_unicas: number;
    tiempo_promedio: number | null;
}