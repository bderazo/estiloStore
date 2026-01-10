import axios from 'axios';
import type {
  PlazaResponse,
  PlazaPaginatedResponse,
  PlazaSelectResponse,
  PlazaForm,
  PlazaFilters
} from '../types/plaza';

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

export const plazaService = {
  // Obtener lista de plazas con filtros y paginación
  async getPlazas(filters: PlazaFilters = {}): Promise<PlazaPaginatedResponse> {
    const params = new URLSearchParams();

    // Agregar filtros como parámetros de consulta
    if (filters.search) params.append('search', filters.search);
    if (filters.activo !== undefined && filters.activo !== '') {
      params.append('activo', String(filters.activo));
    }
    if (filters.sort_by) params.append('sort_by', filters.sort_by);
    if (filters.sort_order) params.append('sort_order', filters.sort_order);
    if (filters.per_page) params.append('per_page', String(filters.per_page));

    const response = await api.get(`/plazas?${params.toString()}`);
    return response.data;
  },

  // Obtener plaza específica por ID
  async getPlaza(id: number): Promise<PlazaResponse> {
    const response = await api.get(`/plazas/${id}`);
    return response.data;
  },

  // Crear nueva plaza
  async createPlaza(plazaData: PlazaForm): Promise<PlazaResponse> {
    const response = await api.post('/plazas', plazaData);
    return response.data;
  },

  // Actualizar plaza existente
  async updatePlaza(id: number, plazaData: PlazaForm): Promise<PlazaResponse> {
    const response = await api.put(`/plazas/${id}`, plazaData);
    return response.data;
  },

  // Eliminar plaza
  async deletePlaza(id: number): Promise<PlazaResponse> {
    const response = await api.delete(`/plazas/${id}`);
    return response.data;
  },

  // Cambiar estado activo/inactivo de la plaza
  async toggleActivo(id: number): Promise<PlazaResponse> {
    const response = await api.patch(`/plazas/${id}/toggle-activo`);
    return response.data;
  },

  // Obtener plazas activas para selects/dropdowns
  async getPlazasForSelect(): Promise<PlazaSelectResponse> {
    const response = await api.get('/plazas/for-select');
    return response.data;
  },

  // Validar nombre de plaza
  validateNombre(nombre: string): boolean {
    if (!nombre || nombre.trim().length === 0) {
      return false;
    }

    // Validar longitud
    if (nombre.trim().length > 255 || nombre.trim().length < 2) {
      return false;
    }

    // Validar caracteres especiales (solo letras, números, espacios, guiones y guiones bajos)
    const validPattern = /^[a-zA-Z0-9\s\-\_]+$/;
    return validPattern.test(nombre.trim());
  },

  // Formatear nombre de plaza
  formatNombre(nombre: string): string {
    if (!nombre) return '';

    // Limpiar espacios extra y convertir a formato título
    return nombre.trim()
      .split(' ')
      .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
      .join(' ');
  },

  // Filtrar plazas por texto de búsqueda
  filterPlazasBySearch(plazas: any[], searchText: string): any[] {
    if (!searchText || searchText.trim().length === 0) {
      return plazas;
    }

    const search = searchText.toLowerCase();

    return plazas.filter(plaza =>
      plaza.nombre.toLowerCase().includes(search) ||
      plaza.searchable_text?.includes(search)
    );
  },

  // Agrupar plazas por estado
  groupPlazasByStatus(plazas: any[]): { activas: any[], inactivas: any[] } {
    return {
      activas: plazas.filter(plaza => plaza.activo),
      inactivas: plazas.filter(plaza => !plaza.activo)
    };
  },

  // Obtener estadísticas de plazas
  getPlazasStats(plazas: any[]): {
    total: number;
    activas: number;
    inactivas: number;
  } {
    return {
      total: plazas.length,
      activas: plazas.filter(p => p.activo).length,
      inactivas: plazas.filter(p => !p.activo).length,
    };
  },
};
