export interface Modelo {
    id: number;
    nombre: string;
    activo: boolean;
    created_at: string;
    updated_at: string;
    formatted_created_at: string;
    formatted_updated_at: string;
}

export interface ModeloForm {
    nombre: string;
    activo: boolean;
}

export interface ModeloFilters {
    search: string;
    activo: string;
    current_page: number;
    per_page: number;
}

export interface ModeloPagination {
    current_page: number;
    total: number;
    per_page: number;
    last_page: number;
    from: number;
    to: number;
}

export interface ModeloSelectItem {
    id: number;
    nombre: string;
}

export interface ModeloResponse {
    data: Modelo;
}

export interface ModeloListResponse {
    data: Modelo[];
    pagination: ModeloPagination;
}
