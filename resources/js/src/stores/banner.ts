// src/stores/banner.ts
import { defineStore } from 'pinia';
import { ref } from 'vue';
import type { Banner, BannerFormData } from '../types/banner';
import api from '../services/api';

export const useBannerStore = defineStore('banner', () => {
    const banners = ref<Banner[]>([]);
    const banner = ref<Banner | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);

    // Obtener todos los banners
    const fetchBanners = async () => {
        try {
            loading.value = true;
            error.value = null;
            console.log('Fetching banners from:', '/api/banners'); // <-- Agrega esto
            const response = await api.get('/banners'); // <-- Sin /api porque ya está en baseURL
            console.log('Banners response:', response); // <-- Agrega esto
            banners.value = response.data.data || response.data;
        } catch (err: any) {
            console.error('Error fetching banners:', err); // <-- Agrega esto
            console.error('Error response:', err.response); // <-- Y esto
            error.value = err.response?.data?.message || 'Error al cargar banners';
        } finally {
            loading.value = false;
        }
    };

    // Obtener un banner por ID
    const fetchBanner = async (id: number) => {
        try {
            loading.value = true;
            error.value = null;
            const response = await api.get(`/banners/${id}`);
            banner.value = response.data.data || response.data;
            return banner.value;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar el banner';
            console.error('Error fetching banner:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Crear un nuevo banner
    const createBanner = async (formData: FormData) => {
        try {
            loading.value = true;
            error.value = null;
            const response = await api.post('/banners', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            banners.value.push(response.data.data || response.data);
            return response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al crear el banner';
            console.error('Error creating banner:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Actualizar un banner
    const updateBanner = async (id: number, formData: FormData) => {
        try {
            loading.value = true;
            error.value = null;
            // Si es Laravel, usar _method para PUT
            formData.append('_method', 'PUT');
            
            const response = await api.post(`/banners/${id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            
            // Actualizar en la lista
            const index = banners.value.findIndex(b => b.id === id);
            if (index !== -1) {
                banners.value[index] = response.data.data || response.data;
            }
            
            // Actualizar el banner actual
            if (banner.value && banner.value.id === id) {
                banner.value = response.data.data || response.data;
            }
            
            return response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al actualizar el banner';
            console.error('Error updating banner:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Eliminar un banner
    const deleteBanner = async (id: number) => {
        try {
            loading.value = true;
            error.value = null;
            await api.delete(`/banners/${id}`);
            
            // Remover de la lista
            banners.value = banners.value.filter(b => b.id !== id);
            
            // Limpiar banner actual si es el eliminado
            if (banner.value && banner.value.id === id) {
                banner.value = null;
            }
            
            return true;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al eliminar el banner';
            console.error('Error deleting banner:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Cambiar estado del banner
    const toggleStatus = async (id: number) => {
        try {
            loading.value = true;
            error.value = null;
            const response = await api.post(`/banners/${id}/toggle-status`);
            
            // Actualizar en la lista
            const index = banners.value.findIndex(b => b.id === id);
            if (index !== -1) {
                banners.value[index].estado = response.data.data?.estado || response.data.estado;
            }
            
            // Actualizar el banner actual
            if (banner.value && banner.value.id === id) {
                banner.value.estado = response.data.data?.estado || response.data.estado;
            }
            
            return response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cambiar estado';
            console.error('Error toggling banner status:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return {
        banners,
        banner,
        loading,
        error,
        fetchBanners,
        fetchBanner,
        createBanner,
        updateBanner,
        deleteBanner,
        toggleStatus,
    };
});