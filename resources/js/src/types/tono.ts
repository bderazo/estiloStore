export interface Tono {
    id: number;
    nombre: string;
    activo: boolean;
    created_at: string;
    updated_at: string;
    formatted_created_at: string;
    formatted_updated_at: string;
}

export interface TonoForm {
    nombre: string;
    activo: boolean;
}

export interface TonoFilters {
    search: string;
    activo: string;
    current_page: number;
    per_page: number;
}

export interface TonoPagination {
    current_page: number;
    total: number;
    per_page: number;
    last_page: number;
    from: number;
    to: number;
}

export interface TonoSelectItem {
    id: number;
    nombre: string;
}

export interface TonoResponse {
    data: Tono;
}

export interface TonoListResponse {
    data: Tono[];
    pagination: TonoPagination;
}
