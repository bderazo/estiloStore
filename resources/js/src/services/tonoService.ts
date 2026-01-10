import axios from 'axios';
import type { Tono, TonoForm, TonoListResponse } from '@/types/tono';

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

export const tonoService = {
    async getTonos(
        page: number = 1,
        perPage: number = 10,
        search: string = '',
        activo: string = ''
    ): Promise<TonoListResponse> {
        const response = await instance.get<TonoListResponse>('/tonos', {
            params: {
                page,
                per_page: perPage,
                search,
                activo,
            },
        });
        return response.data;
    },

    async getTono(id: number): Promise<Tono> {
        const response = await instance.get<Tono>(`/tonos/${id}`);
        return response.data.data;
    },

    async createTono(data: TonoForm): Promise<Tono> {
        const response = await instance.post<Tono>('/tonos', data);
        return response.data.data;
    },

    async updateTono(id: number, data: TonoForm): Promise<Tono> {
        const response = await instance.put<Tono>(`/tonos/${id}`, data);
        return response.data.data;
    },

    async deleteTono(id: number): Promise<void> {
        await instance.delete(`/tonos/${id}`);
    },

    async toggleActivo(id: number): Promise<Tono> {
        const response = await instance.patch<Tono>(`/tonos/${id}/toggle-activo`);
        return response.data.data;
    },

    async TonosForSelect() {
        const response = await instance.get('/tonos/for-select');
        return response.data.data;
    },
};
