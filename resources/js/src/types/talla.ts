// Tipos base para el módulo de tallas
export interface Talla {
  id: number;
  nombre: string;
  descripcion: string | null;
  activo: boolean;
  created_at: string;
  updated_at: string;
  // Campos adicionales del Resource
  nombre_completo?: string;
  estado_texto?: string;
  badge_class?: string;
  created_at_formatted?: string;
  updated_at_formatted?: string;
  created_ago?: string;
  updated_ago?: string;
  searchable_text?: string;
  nombre_length?: number;
  descripcion_length?: number;
}

// Formulario para crear/editar talla
export interface TallaForm {
  nombre: string;
  descripcion: string | null;
  activo: boolean;
}

// Request para crear talla
export interface CreateTallaRequest {
  nombre: string;
  descripcion: string | null;
  activo: boolean;
}

// Request para actualizar talla
export interface UpdateTallaRequest {
  nombre: string;
  descripcion: string | null;
  activo: boolean;
}

// Filtros de búsqueda
export interface TallaFilters {
  search?: string;
  activo?: boolean | '';
  sort_by?: string;
  sort_order?: 'asc' | 'desc';
  per_page?: number;
  current_page?: number;
}

// Para selects/dropdowns
export interface TallaOption {
  id: number;
  nombre: string;
  descripcion: string | null;
  label: string;
  value: number;
  nombre_completo: string;
}

// Respuesta de la API
export interface TallaResponse {
  success: boolean;
  message: string;
  data: Talla;
  errors: string | null;
}

// Respuesta con paginación
export interface TallaPaginatedResponse {
  success: boolean;
  message: string;
  data: Talla[];
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
export interface TallaSelectResponse {
  success: boolean;
  message: string;
  data: TallaOption[];
  errors: string | null;
}

// Estado del store
export interface TallaState {
  tallas: Talla[];
  talla: Talla | null;
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
  filters: TallaFilters;
}

// Datos para validación de formulario
export interface TallaValidationError {
  nombre?: string[];
  descripcion?: string[];
  activo?: string[];
}

// Respuesta de error de validación
export interface TallaValidationResponse {
  success: false;
  message: string;
  data: null;
  errors: TallaValidationError;
}
