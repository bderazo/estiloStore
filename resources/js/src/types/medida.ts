/**
 * Tipos e interfaces para el módulo de Medidas
 */

/**
 * Interfaz principal para una medida
 */
export interface Medida {
  id: number;
  nombre: string;
  activo: boolean;
  created_at: string;
  updated_at: string;
  formatted_created_at: string;
  formatted_updated_at: string;
}

/**
 * Interfaz para los datos del formulario de medida
 */
export interface MedidaForm {
  nombre: string;
  activo: boolean;
}

/**
 * Interfaz para los filtros de búsqueda
 */
export interface MedidaFilters {
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
export interface MedidaPagination {
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
export interface MedidaListResponse {
  success: boolean;
  message: string;
  data: Medida[];
  pagination: MedidaPagination;
  errors: string | null;
}

/**
 * Interfaz para la respuesta de la API en operaciones individuales
 */
export interface MedidaResponse {
  success: boolean;
  message: string;
  data: Medida | null;
  errors: string | null;
}

/**
 * Interfaz para la respuesta de getForSelect
 */
export interface MedidaSelectItem {
  id: number;
  nombre: string;
  label: string;
  value: number;
}

export interface MedidaSelectResponse {
  success: boolean;
  message: string;
  data: MedidaSelectItem[];
  errors: string | null;
}

/**
 * Interfaz para el estado del store de Pinia
 */
export interface MedidaState {
  medidas: Medida[];
  selectedMedida: Medida | null;
  loading: boolean;
  error: string | null;
  pagination: MedidaPagination | null;
  filters: MedidaFilters;
  medidaSelect: MedidaSelectItem[];
}

/**
 * Interfaz para errores de validación
 */
export interface MedidaValidationErrors {
  nombre?: string[];
  activo?: string[];
}
