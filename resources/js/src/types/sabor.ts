/**
 * Tipos e interfaces para el módulo de Sabores
 */

/**
 * Interfaz principal para un sabor
 */
export interface Sabor {
  id: number;
  nombre: string;
  activo: boolean;
  created_at: string;
  updated_at: string;
  formatted_created_at: string;
  formatted_updated_at: string;
}

/**
 * Interfaz para los datos del formulario de sabor
 */
export interface SaborForm {
  nombre: string;
  activo: boolean;
}

/**
 * Interfaz para los filtros de búsqueda
 */
export interface SaborFilters {
  search?: string;
  activo?: boolean | string | '';
  sort_by?: string;
  sort_order?: 'asc' | 'desc';
  per_page?: number;
  current_page?: number;
}

/**
 * Interfaz para los parámetros de paginación
 */
export interface SaborPagination {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number;
  to: number;
}

/**
 * Interfaz para la respuesta de la API en listados
 */
export interface SaborListResponse {
  success: boolean;
  message: string;
  data: Sabor[];
  pagination: SaborPagination;
  errors: string | null;
}

/**
 * Interfaz para la respuesta de la API en operaciones individuales
 */
export interface SaborResponse {
  success: boolean;
  message: string;
  data: Sabor | null;
  errors: string | null;
}

/**
 * Interfaz para la respuesta de getForSelect
 */
export interface SaborSelectItem {
  id: number;
  nombre: string;
  label: string;
  value: number;
}

export interface SaborSelectResponse {
  success: boolean;
  message: string;
  data: SaborSelectItem[];
  errors: string | null;
}

/**
 * Interfaz para el estado del store de Pinia
 */
export interface SaborState {
  sabores: Sabor[];
  selectedSabor: Sabor | null;
  loading: boolean;
  error: string | null;
  pagination: SaborPagination | null;
  filters: SaborFilters;
  saborSelect: SaborSelectItem[];
}
