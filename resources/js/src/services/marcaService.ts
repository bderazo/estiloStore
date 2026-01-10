import api from './api';
import {
    MarcaListResponse,
    MarcaResponse,
    MarcaSelectResponse,
    MarcaFilters,
    CreateMarcaRequest,
    UpdateMarcaRequest
} from '../types/marca';

class MarcaService {
    private readonly baseUrl = '/marcas';

    /**
     * Obtener lista de marcas con filtros y paginación
     */
    async getAll(filters?: MarcaFilters): Promise<MarcaListResponse> {
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
     * Obtener una marca por ID
     */
    async getById(id: number): Promise<MarcaResponse> {
        const response = await api.get(`${this.baseUrl}/${id}`);
        return response.data;
    }

    /**
     * Crear nueva marca
     */
    async create(data: CreateMarcaRequest): Promise<MarcaResponse> {
        const response = await api.post(this.baseUrl, data);
        return response.data;
    }

    /**
     * Actualizar marca existente
     */
    async update(id: number, data: UpdateMarcaRequest): Promise<MarcaResponse> {
        const response = await api.put(`${this.baseUrl}/${id}`, data);
        return response.data;
    }

    /**
     * Eliminar marca
     */
    async delete(id: number): Promise<void> {
        await api.delete(`${this.baseUrl}/${id}`);
    }

    /**
     * Cambiar estado activo de una marca
     */
    async toggleActivo(id: number): Promise<MarcaResponse> {
        const response = await api.patch(`${this.baseUrl}/${id}/toggle-activo`);
        return response.data;
    }

    /**
     * Obtener marcas para select
     */
    async getForSelect(): Promise<MarcaSelectResponse> {
        const response = await api.get(`${this.baseUrl}/for-select`);
        return response.data;
    }

    /**
     * Obtener marcas activas únicamente
     */
    async getActivas(filters?: Omit<MarcaFilters, 'activo'>): Promise<MarcaListResponse> {
        return this.getAll({ ...filters, activo: true });
    }

    /**
     * Obtener marcas inactivas únicamente
     */
    async getInactivas(filters?: Omit<MarcaFilters, 'activo'>): Promise<MarcaListResponse> {
        return this.getAll({ ...filters, activo: false });
    }

    /**
     * Buscar marcas por nombre
     */
    async searchByName(search: string, filters?: Omit<MarcaFilters, 'search'>): Promise<MarcaListResponse> {
        return this.getAll({ ...filters, search });
    }

    /**
     * Obtener estadísticas de marcas
     */
    async getStats(): Promise<{
        total: number;
        activas: number;
        inactivas: number;
    }> {
        const response = await api.get(`${this.baseUrl}/stats`);
        return response.data.data;
    }
}

export default new MarcaService();
