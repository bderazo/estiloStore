// Tipos base para el módulo de colores
export interface Color {
  id: number;
  nombre: string;
  codigo_hex: string;
  activo: boolean;
  created_at: string;
  updated_at: string;
  // Campos adicionales del Resource
  color_preview?: {
    background: string;
    contrast: string;
  };
  luminance?: number;
  is_dark?: boolean;
}

// Formulario para crear/editar color
export interface ColorForm {
  nombre: string;
  codigo_hex: string;
  activo: boolean;
}

// Filtros de búsqueda
export interface ColorFilters {
  search?: string;
  activo?: boolean | '';
  sort_by?: string;
  sort_order?: 'asc' | 'desc';
  per_page?: number;
  current_page?: number;
}

// Para selects/dropdowns
export interface ColorOption {
  id: number;
  nombre: string;
  codigo_hex: string;
  label: string;
  value: number;
  color_preview: {
    background: string;
    contrast: string;
  };
}

// Respuesta de la API
export interface ColorResponse {
  success: boolean;
  message: string;
  data: Color | Color[] | null;
  errors: string | null;
}

// Respuesta con paginación
export interface ColorPaginatedResponse {
  success: boolean;
  message: string;
  data: Color[];
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
export interface ColorSelectResponse {
  success: boolean;
  message: string;
  data: ColorOption[];
  errors: string | null;
}

// Estado del store
export interface ColorState {
  colores: Color[];
  color: Color | null;
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
  filters: ColorFilters;
}

// Datos para validación de formulario
export interface ColorValidationError {
  nombre?: string[];
  codigo_hex?: string[];
  activo?: string[];
}

// Respuesta de error de validación
export interface ColorValidationResponse {
  success: false;
  message: string;
  data: null;
  errors: ColorValidationError;
}
