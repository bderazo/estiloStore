/**
 * Servicio de API para el módulo de Sabores
 * Maneja todas las llamadas HTTP al backend
 */

import axios from 'axios';
import type {
  Sabor,
  SaborForm,
  SaborFilters,
  SaborListResponse,
  SaborResponse,
  SaborSelectResponse,
} from '@/types/sabor';

// Instancia de axios configurada
const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Interceptor para agregar token de autenticación
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

const BASE_URL = '/sabores';

/**
 * Obtiene la lista paginada de sabores
 */
export async function getSabores(filters: SaborFilters = {}): Promise<SaborListResponse> {
  const params = new URLSearchParams();

  if (filters.search) params.append('search', filters.search);
  if (filters.activo !== '' && filters.activo !== undefined && filters.activo !== null) {
    params.append('activo', String(filters.activo));
  }
  if (filters.sort_by) params.append('sort_by', filters.sort_by);
  if (filters.sort_order) params.append('sort_order', filters.sort_order);
  if (filters.per_page) params.append('per_page', String(filters.per_page));
  if (filters.current_page) params.append('page', String(filters.current_page));

  const response = await api.get<SaborListResponse>(`${BASE_URL}?${params.toString()}`);
  return response.data;
}

/**
 * Obtiene un sabor específico por ID
 */
export async function getSabor(id: number): Promise<SaborResponse> {
  const response = await api.get<SaborResponse>(`${BASE_URL}/${id}`);
  return response.data;
}

/**
 * Crea un nuevo sabor
 */
export async function createSabor(data: SaborForm): Promise<SaborResponse> {
  const response = await api.post<SaborResponse>(BASE_URL, data);
  return response.data;
}

/**
 * Actualiza un sabor existente
 */
export async function updateSabor(id: number, data: Partial<SaborForm>): Promise<SaborResponse> {
  const response = await api.put<SaborResponse>(`${BASE_URL}/${id}`, data);
  return response.data;
}

/**
 * Elimina un sabor
 */
export async function deleteSabor(id: number): Promise<SaborResponse> {
  const response = await api.delete<SaborResponse>(`${BASE_URL}/${id}`);
  return response.data;
}

/**
 * Cambia el estado activo/inactivo de un sabor
 */
export async function toggleSaborActivo(id: number): Promise<SaborResponse> {
  const response = await api.patch<SaborResponse>(`${BASE_URL}/${id}/toggle-activo`);
  return response.data;
}

/**
 * Obtiene la lista de sabores para componentes select
 */
export async function getSaboresForSelect(): Promise<SaborSelectResponse> {
  const response = await api.get<SaborSelectResponse>(`${BASE_URL}/for-select`);
  return response.data;
}
