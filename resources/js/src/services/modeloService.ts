import axios from 'axios';
import type { Modelo, ModeloForm, ModeloListResponse } from '@/types/modelo';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';

const instance = axios.create({
    baseURL: API_BASE_URL,
});

// Interceptor to add JWT token to requests
instance.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export const modeloService = {
    async getModelos(
        page: number = 1,
        perPage: number = 10,
        search: string = '',
        activo: string = ''
    ): Promise<ModeloListResponse> {
        const response = await instance.get<ModeloListResponse>('/modelos', {
            params: {
                page,
                per_page: perPage,
                search,
                activo,
            },
        });
        return response.data;
    },

    async getModelo(id: number): Promise<Modelo> {
        const response = await instance.get<Modelo>(`/modelos/${id}`);
        return response.data.data;
    },

    async createModelo(data: ModeloForm): Promise<Modelo> {
        const response = await instance.post<Modelo>('/modelos', data);
        return response.data.data;
    },

    async updateModelo(id: number, data: ModeloForm): Promise<Modelo> {
        const response = await instance.put<Modelo>(`/modelos/${id}`, data);
        return response.data.data;
    },

    async deleteModelo(id: number): Promise<void> {
        await instance.delete(`/modelos/${id}`);
    },

    async toggleActivo(id: number): Promise<Modelo> {
        const response = await instance.patch<Modelo>(`/modelos/${id}/toggle-activo`);
        return response.data.data;
    },

    async getModelosForSelect() {
        const response = await instance.get('/modelos/for-select');
        return response.data.data;
    },
};
