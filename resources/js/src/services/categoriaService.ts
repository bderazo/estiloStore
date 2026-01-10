import api from './api';
import {
    CategoriaListResponse,
    CategoriaResponse,
    CategoriaSelectResponse,
    CategoriaFilters,
    CreateCategoriaRequest,
    UpdateCategoriaRequest,
    ReorderCategoriesRequest
} from '../types/categoria';class CategoriaService {
    private readonly baseUrl = '/categorias';

    /**
     * Obtener lista de categorías con filtros y paginación
     */
    async getAll(filters?: CategoriaFilters): Promise<CategoriaListResponse> {
        const params = new URLSearchParams();

        if (filters) {
            Object.entries(filters).forEach(([key, value]) => {
                if (value !== null && value !== undefined && value !== '') {
                    params.append(key, String(value));
                }
            });
        }

        const response = await api.get(`${this.baseUrl}?${params.toString()}`);
        return response.data;
    }

    /**
     * Obtener una categoría por ID
     */
    async getById(id: number): Promise<CategoriaResponse> {
        const response = await api.get(`${this.baseUrl}/${id}`);
        return response.data;
    }

    /**
     * Crear nueva categoría
     */
    async create(data: CreateCategoriaRequest): Promise<CategoriaResponse> {
        const response = await api.post(this.baseUrl, data);
        return response.data;
    }

    /**
     * Actualizar categoría existente
     */
    async update(id: number, data: UpdateCategoriaRequest): Promise<CategoriaResponse> {
        const response = await api.put(`${this.baseUrl}/${id}`, data);
        return response.data;
    }

    /**
     * Eliminar categoría
     */
    async delete(id: number): Promise<void> {
        await api.delete(`${this.baseUrl}/${id}`);
    }

    /**
     * Cambiar estado activo de una categoría
     */
    async toggleActivo(id: number): Promise<CategoriaResponse> {
        const response = await api.patch(`${this.baseUrl}/${id}/toggle-activo`);
        return response.data;
    }

    /**
     * Obtener categorías para select (formato jerárquico)
     */
    async getForSelect(excludeId?: number): Promise<CategoriaSelectResponse> {
        const params = new URLSearchParams();

        if (excludeId) {
            params.append('exclude', String(excludeId));
        }

        const response = await api.get(`${this.baseUrl}/for-select?${params.toString()}`);
        return response.data;
    }

    /**
     * Reordenar categorías
     */
    async reorder(data: ReorderCategoriesRequest): Promise<void> {
        await api.post(`${this.baseUrl}/reorder`, data);
    }

    /**
     * Obtener categorías en estructura jerárquica
     */
    async getHierarchical(filters?: Omit<CategoriaFilters, 'hierarchical'>): Promise<CategoriaListResponse> {
        return this.getAll({ ...filters, hierarchical: true });
    }

    /**
     * Obtener categorías principales (sin padre)
     */
    async getPrincipales(filters?: Omit<CategoriaFilters, 'parent_id'>): Promise<CategoriaListResponse> {
        return this.getAll({ ...filters, parent_id: null });
    }

    /**
     * Obtener subcategorías de una categoría padre
     */
    async getSubcategorias(parentId: number, filters?: Omit<CategoriaFilters, 'parent_id'>): Promise<CategoriaListResponse> {
        return this.getAll({ ...filters, parent_id: parentId });
    }
}

export default new CategoriaService();
