// Tipos base para el módulo de plazas
export interface Plaza {
  id: number;
  nombre: string;
  activo: boolean;
  created_at: string;
  updated_at: string;
  // Campos adicionales del Resource
  estado_texto?: string;
  badge_class?: string;
  created_at_formatted?: string;
  updated_at_formatted?: string;
  created_ago?: string;
  updated_ago?: string;
  searchable_text?: string;
  nombre_length?: number;
}

// Formulario para crear/editar plaza
export interface PlazaForm {
  nombre: string;
  activo: boolean;
}

// Request para crear plaza
export interface CreatePlazaRequest {
  nombre: string;
  activo: boolean;
}

// Request para actualizar plaza
export interface UpdatePlazaRequest {
  nombre: string;
  activo: boolean;
}

// Filtros de búsqueda
export interface PlazaFilters {
  search?: string;
  activo?: boolean | '';
  sort_by?: string;
  sort_order?: 'asc' | 'desc';
  per_page?: number;
  current_page?: number;
}

// Para selects/dropdowns
export interface PlazaOption {
  id: number;
  nombre: string;
  label: string;
  value: number;
}

// Respuesta de la API
export interface PlazaResponse {
  success: boolean;
  message: string;
  data: Plaza;
  errors: string | null;
}

// Respuesta con paginación
export interface PlazaPaginatedResponse {
  success: boolean;
  message: string;
  data: Plaza[];
  pagination: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
  };
  errors: string | null;
}

// Respuesta para selects
export interface PlazaSelectResponse {
  success: boolean;
  message: string;
  data: PlazaOption[];
  errors: string | null;
}

// Estado del store
export interface PlazaState {
  plazas: Plaza[];
  plaza: Plaza | null;
  loading: boolean;
  error: string | null;
  pagination: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
  };
  filters: PlazaFilters;
}

// Datos para validación de formulario
export interface PlazaValidationError {
  nombre?: string[];
  activo?: string[];
}

// Respuesta de error de validación
export interface PlazaValidationResponse {
  success: false;
  message: string;
  data: null;
  errors: PlazaValidationError;
}
