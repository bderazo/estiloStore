export interface Folleto {
    id: number;
    nombre: string;
    descripcion: string | null;
    archivo_pdf: string;
    archivo_url?: string;
    tamano_archivo?: string;
    descargas: number;
    estado: boolean;
    estado_label?: string;
    fecha_creacion_formateada?: string;
    created_at: string;
    updated_at: string;
}

export interface FolletoFormData {
    nombre: string;
    descripcion: string;
    archivo: File | null;
    estado: boolean;
    reset_descargas?: boolean;
}

export interface FolletoFilters {
    search?: string;
    estado?: boolean | string;
    order_by?: string;
    order_dir?: 'asc' | 'desc';
    per_page?: number;
    page?: number;
}

export interface FolletoEstadisticas {
    total_folletos: number;
    total_activos: number;
    total_descargas: number;
    folleto_popular: Folleto | null;
    descargas_por_mes: Array<{mes: string, total_descargas: number}>;
    promedio_descargas: number;
}

export interface PaginationMeta {
    current_page: number;
    from: number;
    last_page: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    path: string;
    per_page: number;
    to: number;
    total: number;
}

export interface FolletoResponse {
    data: Folleto[];
    links: PaginationMeta['links'];
    meta: PaginationMeta;
}