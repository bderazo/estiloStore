// Interfaz principal para Marca
export interface Marca {
    id: number;
    nombre: string;
    slug: string;
    descripcion: string | null;
    activo: boolean;
    created_at: string;
    updated_at: string;

    // Campos formateados del Resource
    formatted_created_at: string;
    formatted_updated_at: string;
    estado_texto: string;
}

// Interfaz para crear marca
export interface CreateMarcaRequest {
    nombre: string;
    descripcion?: string;
    activo?: boolean;
}

// Interfaz para actualizar marca
export interface UpdateMarcaRequest {
    nombre?: string;
    descripcion?: string;
    activo?: boolean;
}

// Interfaz para filtros
export interface MarcaFilters {
    search?: string;
    activo?: boolean | null;
    sort_by?: 'nombre' | 'created_at' | 'updated_at';
    sort_order?: 'asc' | 'desc';
    per_page?: number;
    page?: number;
}

// Interfaz para paginación
export interface MarcaPagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
}

// Interfaz para respuesta de la API
export interface MarcaApiResponse {
    success: boolean;
    message: string;
    data: Marca | Marca[] | null;
    pagination?: MarcaPagination;
    errors: any;
}

// Interfaces específicas para respuestas del servicio
export interface MarcaListResponse {
    data: Marca[];
    pagination?: MarcaPagination;
}

export interface MarcaResponse {
    data: Marca;
}

export interface MarcaSelectResponse {
    data: MarcaSelectOption[];
}

// Interfaz para opciones de select
export interface MarcaSelectOption {
    id: number;
    nombre: string;
    label: string;
    value: number;
}

// Estado del store
export interface MarcaState {
    marcas: Marca[];
    marca: Marca | null;
    selectOptions: MarcaSelectOption[];
    loading: boolean;
    filters: MarcaFilters;
    pagination: MarcaPagination | null;
}

// Para operaciones asíncronas
export interface AsyncMarcaOperation {
    success: boolean;
    data?: Marca;
    message?: string;
    errors?: any;
}
