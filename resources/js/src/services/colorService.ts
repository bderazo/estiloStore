import axios from 'axios';
import type {
  ColorResponse,
  ColorPaginatedResponse,
  ColorSelectResponse,
  ColorForm,
  ColorFilters
} from '../types/color';

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

export const colorService = {
  // Obtener lista de colores con filtros y paginación
  async getColores(filters: ColorFilters = {}): Promise<ColorPaginatedResponse> {
    const params = new URLSearchParams();

    // Agregar filtros como parámetros de consulta
    if (filters.search) params.append('search', filters.search);
    if (filters.activo !== undefined && filters.activo !== '') {
      params.append('activo', String(filters.activo));
    }
    if (filters.sort_by) params.append('sort_by', filters.sort_by);
    if (filters.sort_order) params.append('sort_order', filters.sort_order);
    if (filters.per_page) params.append('per_page', String(filters.per_page));

    const response = await api.get(`/colores?${params.toString()}`);
    return response.data;
  },

  // Obtener color específico por ID
  async getColor(id: number): Promise<ColorResponse> {
    const response = await api.get(`/colores/${id}`);
    return response.data;
  },

  // Crear nuevo color
  async createColor(colorData: ColorForm): Promise<ColorResponse> {
    const response = await api.post('/colores', colorData);
    return response.data;
  },

  // Actualizar color existente
  async updateColor(id: number, colorData: ColorForm): Promise<ColorResponse> {
    const response = await api.put(`/colores/${id}`, colorData);
    return response.data;
  },

  // Eliminar color
  async deleteColor(id: number): Promise<ColorResponse> {
    const response = await api.delete(`/colores/${id}`);
    return response.data;
  },

  // Cambiar estado activo/inactivo del color
  async toggleActivo(id: number): Promise<ColorResponse> {
    const response = await api.patch(`/colores/${id}/toggle-activo`);
    return response.data;
  },

  // Obtener colores activos para selects/dropdowns
  async getColoresForSelect(): Promise<ColorSelectResponse> {
    const response = await api.get('/colores/for-select');
    return response.data;
  },

  // Validar formato de código hexadecimal
  validateHexColor(hex: string): boolean {
    const hexRegex = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
    return hexRegex.test(hex);
  },

  // Formatear código hex (agregar # si no lo tiene)
  formatHexColor(hex: string): string {
    if (!hex) return '';

    // Remover espacios y convertir a minúsculas
    hex = hex.trim().toLowerCase();

    // Agregar # si no lo tiene
    if (!hex.startsWith('#')) {
      hex = '#' + hex;
    }

    return hex;
  },

  // Calcular color de contraste para texto
  getContrastColor(hexColor: string): string {
    // Remover el #
    const hex = hexColor.replace('#', '');

    // Convertir a RGB
    const r = parseInt(hex.substr(0, 2), 16);
    const g = parseInt(hex.substr(2, 2), 16);
    const b = parseInt(hex.substr(4, 2), 16);

    // Calcular luminancia
    const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;

    // Retornar blanco o negro según la luminancia
    return luminance > 0.5 ? '#000000' : '#FFFFFF';
  },

  // Verificar si un color es oscuro
  isDarkColor(hexColor: string): boolean {
    return this.getContrastColor(hexColor) === '#FFFFFF';
  },

  // Generar colores aleatorios para pruebas
  generateRandomColor(): string {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  },

  // Obtener nombre del color más cercano (básico)
  getColorName(hexColor: string): string {
    const colorNames: { [key: string]: string } = {
      '#FF0000': 'Rojo',
      '#00FF00': 'Verde',
      '#0000FF': 'Azul',
      '#FFFF00': 'Amarillo',
      '#FF00FF': 'Magenta',
      '#00FFFF': 'Cian',
      '#000000': 'Negro',
      '#FFFFFF': 'Blanco',
      '#808080': 'Gris',
      '#FFA500': 'Naranja',
      '#800080': 'Púrpura',
      '#FFC0CB': 'Rosa',
      '#A52A2A': 'Marrón',
      '#008000': 'Verde oscuro',
      '#000080': 'Azul marino',
    };

    // Buscar coincidencia exacta
    const upperHex = hexColor.toUpperCase();
    if (colorNames[upperHex]) {
      return colorNames[upperHex];
    }

    // Si no hay coincidencia exacta, retornar el código hex
    return hexColor;
  },
};
