export interface EmpresaDato {
    id: number;
    clave: string;
    clave_label: string;
    titulo: string | null;
    contenido: string | null;
    imagen: string | null;
    imagen_url: string | null;
    activo: boolean;
    activo_label: string;
    orden: number;
    created_at: string;
    updated_at: string;
}

export interface EmpresaDatoFormData {
    clave: string;
    titulo: string;
    contenido: string;
    imagen: File | null;
    remove_imagen?: boolean;
    activo: boolean;
    orden: number;
}

export interface EmpresaDatoFilter {
    search?: string;
    activo?: boolean | null;
    sort_by?: string;
    sort_order?: 'asc' | 'desc';
    page?: number;
    per_page?: number;
}

export const CLAVES_EMPRESA = {
    quienes_somos: 'Quiénes Somos',
    vision: 'Visión',
    mision: 'Misión',
    valores: 'Valores',
    equipo: 'Nuestro Equipo',
    contacto_info: 'Información de Contacto'
} as const;

export type ClaveEmpresa = keyof typeof CLAVES_EMPRESA;

export interface PaginationMeta {
    total: number;
    current_page: number;
    last_page: number;
    per_page: number;
}

export interface ApiResponse<T> {
    success: boolean;
    message?: string;
    data: T;
    meta?: PaginationMeta;
    errors?: Record<string, string[]>;
}