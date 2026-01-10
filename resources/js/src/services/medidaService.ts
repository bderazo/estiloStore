/**
 * Servicio de API para el módulo de Medidas
 * Maneja todas las llamadas HTTP al backend
 */

import axios from 'axios';
import type {
  Medida,
  MedidaForm,
  MedidaFilters,
  MedidaListResponse,
  MedidaResponse,
  MedidaSelectResponse,
} from '@/types/medida';

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

const BASE_URL = '/medidas';

/**
 * Obtiene la lista paginada de medidas
 */
export async function getMedidas(filters: MedidaFilters = {}): Promise<MedidaListResponse> {
  const params = new URLSearchParams();

  if (filters.search) params.append('search', filters.search);
  if (filters.activo !== '' && filters.activo !== undefined && filters.activo !== null) {
    params.append('activo', String(filters.activo));
  }
  if (filters.sort_by) params.append('sort_by', filters.sort_by);
  if (filters.sort_order) params.append('sort_order', filters.sort_order);
  if (filters.per_page) params.append('per_page', String(filters.per_page));
  if (filters.current_page) params.append('page', String(filters.current_page));

  const response = await api.get<MedidaListResponse>(`${BASE_URL}?${params.toString()}`);
  return response.data;
}

/**
 * Obtiene una medida específica por ID
 */
export async function getMedida(id: number): Promise<MedidaResponse> {
  const response = await api.get<MedidaResponse>(`${BASE_URL}/${id}`);
  return response.data;
}

/**
 * Crea una nueva medida
 */
export async function createMedida(data: MedidaForm): Promise<MedidaResponse> {
  const response = await api.post<MedidaResponse>(BASE_URL, data);
  return response.data;
}

/**
 * Actualiza una medida existente
 */
export async function updateMedida(id: number, data: Partial<MedidaForm>): Promise<MedidaResponse> {
  const response = await api.put<MedidaResponse>(`${BASE_URL}/${id}`, data);
  return response.data;
}

/**
 * Elimina una medida
 */
export async function deleteMedida(id: number): Promise<MedidaResponse> {
  const response = await api.delete<MedidaResponse>(`${BASE_URL}/${id}`);
  return response.data;
}

/**
 * Cambia el estado activo/inactivo de una medida
 */
export async function toggleMedidaActivo(id: number): Promise<MedidaResponse> {
  const response = await api.patch<MedidaResponse>(`${BASE_URL}/${id}/toggle-activo`);
  return response.data;
}

/**
 * Obtiene la lista de medidas para componentes select
 */
export async function getMedidasForSelect(): Promise<MedidaSelectResponse> {
  const response = await api.get<MedidaSelectResponse>(`${BASE_URL}/for-select`);
  return response.data;
}
