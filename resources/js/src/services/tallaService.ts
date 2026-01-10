import axios from 'axios';
import type {
  TallaResponse,
  TallaPaginatedResponse,
  TallaSelectResponse,
  TallaForm,
  TallaFilters
} from '../types/talla';

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

export const tallaService = {
  // Obtener lista de tallas con filtros y paginación
  async getTallas(filters: TallaFilters = {}): Promise<TallaPaginatedResponse> {
    const params = new URLSearchParams();

    // Agregar filtros como parámetros de consulta
    if (filters.search) params.append('search', filters.search);
    if (filters.activo !== undefined && filters.activo !== '') {
      params.append('activo', String(filters.activo));
    }
    if (filters.sort_by) params.append('sort_by', filters.sort_by);
    if (filters.sort_order) params.append('sort_order', filters.sort_order);
    if (filters.per_page) params.append('per_page', String(filters.per_page));

    const response = await api.get(`/tallas?${params.toString()}`);
    return response.data;
  },

  // Obtener talla específica por ID
  async getTalla(id: number): Promise<TallaResponse> {
    const response = await api.get(`/tallas/${id}`);
    return response.data;
  },

  // Crear nueva talla
  async createTalla(tallaData: TallaForm): Promise<TallaResponse> {
    const response = await api.post('/tallas', tallaData);
    return response.data;
  },

  // Actualizar talla existente
  async updateTalla(id: number, tallaData: TallaForm): Promise<TallaResponse> {
    const response = await api.put(`/tallas/${id}`, tallaData);
    return response.data;
  },

  // Eliminar talla
  async deleteTalla(id: number): Promise<TallaResponse> {
    const response = await api.delete(`/tallas/${id}`);
    return response.data;
  },

  // Cambiar estado activo/inactivo de la talla
  async toggleActivo(id: number): Promise<TallaResponse> {
    const response = await api.patch(`/tallas/${id}/toggle-activo`);
    return response.data;
  },

  // Obtener tallas activas para selects/dropdowns
  async getTallasForSelect(): Promise<TallaSelectResponse> {
    const response = await api.get('/tallas/for-select');
    return response.data;
  },

  // Validar nombre de talla (evitar duplicados)
  validateNombre(nombre: string): boolean {
    if (!nombre || nombre.trim().length === 0) {
      return false;
    }

    // Validar longitud
    if (nombre.trim().length > 100) {
      return false;
    }

    // Validar caracteres especiales (solo letras, números, espacios y algunos caracteres)
    const validPattern = /^[a-zA-Z0-9\s\-\.\/]+$/;
    return validPattern.test(nombre.trim());
  },

  // Formatear nombre de talla
  formatNombre(nombre: string): string {
    if (!nombre) return '';

    // Limpiar espacios extra y convertir a formato título
    return nombre.trim()
      .split(' ')
      .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
      .join(' ');
  },

  // Validar descripción
  validateDescripcion(descripcion: string | null): boolean {
    if (!descripcion) return true; // Es opcional

    // Validar longitud máxima
    if (descripcion.trim().length > 500) {
      return false;
    }

    return true;
  },

  // Limpiar descripción
  cleanDescripcion(descripcion: string | null): string | null {
    if (!descripcion || descripcion.trim().length === 0) {
      return null;
    }

    return descripcion.trim();
  },

  // Generar nombre completo de la talla
  generateNombreCompleto(nombre: string, descripcion: string | null): string {
    if (!descripcion) {
      return nombre;
    }

    return `${nombre} - ${descripcion}`;
  },

  // Filtrar tallas por texto de búsqueda
  filterTallasBySearch(tallas: any[], searchText: string): any[] {
    if (!searchText || searchText.trim().length === 0) {
      return tallas;
    }

    const search = searchText.toLowerCase();

    return tallas.filter(talla =>
      talla.nombre.toLowerCase().includes(search) ||
      (talla.descripcion && talla.descripcion.toLowerCase().includes(search)) ||
      talla.searchable_text?.includes(search)
    );
  },

  // Agrupar tallas por estado
  groupTallasByStatus(tallas: any[]): { activas: any[], inactivas: any[] } {
    return {
      activas: tallas.filter(talla => talla.activo),
      inactivas: tallas.filter(talla => !talla.activo)
    };
  },

  // Obtener estadísticas de tallas
  getTallasStats(tallas: any[]): {
    total: number;
    activas: number;
    inactivas: number;
    conDescripcion: number;
    sinDescripcion: number;
  } {
    return {
      total: tallas.length,
      activas: tallas.filter(t => t.activo).length,
      inactivas: tallas.filter(t => !t.activo).length,
      conDescripcion: tallas.filter(t => t.descripcion && t.descripcion.trim().length > 0).length,
      sinDescripcion: tallas.filter(t => !t.descripcion || t.descripcion.trim().length === 0).length
    };
  },
};
