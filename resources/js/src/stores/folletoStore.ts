// src/stores/folleto.ts
import { defineStore } from 'pinia';
import { ref, computed, type ComputedRef } from 'vue';
import type {
    Folleto,
    FolletoFormData,
    FolletoFilters,
    FolletoEstadisticas as Estadisticas,
    FolletoResponse
} from '../types/folleto';
import api from '../services/api';

export const useFolletoStore = defineStore('folleto', () => {
    // Estado reactivo
    const folletos = ref<Folleto[]>([]);
    const folleto = ref<Folleto | null>(null);
    const estadisticas = ref<Estadisticas | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);
    const filters = ref<FolletoFilters>({
        search: '',
        order_by: 'created_at',
        order_dir: 'desc',
        per_page: 15
    });
    const pagination = ref({
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0
    });

    // Getters computados
    const totalDescargas = computed((): number =>
        folletos.value.reduce((sum, folleto) => sum + folleto.descargas, 0)
    );

    const folletosActivos = computed((): Folleto[] =>
        folletos.value.filter(f => f.estado)
    );

    const folletosInactivos = computed((): Folleto[] =>
        folletos.value.filter(f => !f.estado)
    );

    const folletosPopulares = computed((): Folleto[] =>
        [...folletos.value].sort((a, b) => b.descargas - a.descargas).slice(0, 5)
    );

    const filteredFolletos = computed((): Folleto[] => {
        let filtered = [...folletos.value];

        // Filtro por búsqueda
        if (filters.value.search) {
            const search = filters.value.search.toLowerCase();
            filtered = filtered.filter(f =>
                f.nombre.toLowerCase().includes(search) ||
                (f.descripcion && f.descripcion.toLowerCase().includes(search))
            );
        }

        // Filtro por estado
        if (filters.value.estado !== undefined && filters.value.estado !== null) {
            const estado = filters.value.estado === 'true' || filters.value.estado === true;
            filtered = filtered.filter(f => f.estado === estado);
        }

        // Ordenamiento
        filtered.sort((a, b) => {
            const order = filters.value.order_dir === 'asc' ? 1 : -1;
            const field = filters.value.order_by as keyof Folleto;

            if (field === 'nombre') {
                return order * a.nombre.localeCompare(b.nombre);
            }
            if (field === 'descargas') {
                return order * (a.descargas - b.descargas);
            }
            if (field === 'created_at') {
                return order * (new Date(a.created_at).getTime() - new Date(b.created_at).getTime());
            }
            return 0;
        });

        return filtered;
    });

    const chartData = computed(() => {
        const labels = folletos.value.map(f => f.nombre);
        const data = folletos.value.map(f => f.descargas);
        const backgroundColors = folletos.value.map(f =>
            f.estado ? 'rgba(34, 197, 94, 0.5)' : 'rgba(239, 68, 68, 0.5)'
        );

        return {
            labels,
            datasets: [{
                label: 'Descargas',
                data,
                backgroundColor: backgroundColors,
                borderColor: folletos.value.map(f =>
                    f.estado ? 'rgb(34, 197, 94)' : 'rgb(239, 68, 68)'
                ),
                borderWidth: 1
            }]
        };
    });

    // Configurar headers para autenticación
    const getApiConfig = () => {
        const token = localStorage.getItem('token');
        return {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'multipart/form-data'
            }
        };
    };

    // Acciones
    const fetchFolletos = async (page: number = 1) => {
        try {
            loading.value = true;
            error.value = null;

            const params = {
                ...filters.value,
                page
            };

            // Convertir booleanos a string si es necesario para Laravel
            if (params.estado !== undefined && params.estado !== null) {
                params.estado = params.estado.toString();
            }

            const response = await api.get('/folletos', { params });
            const data = response.data;

            folletos.value = data.data || data;
            pagination.value = {
                current_page: data.meta?.current_page || 1,
                last_page: data.meta?.last_page || 1,
                per_page: data.meta?.per_page || 15,
                total: data.meta?.total || 0
            };

            return data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar los folletos';
            console.error('Error fetching folletos:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const fetchFolleto = async (id: number) => {
        try {
            loading.value = true;
            error.value = null;

            const response = await api.get(`/folletos/${id}`);
            folleto.value = response.data.data || response.data;

            return folleto.value;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar el folleto';
            console.error('Error fetching folleto:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const createFolleto = async (formData: FolletoFormData) => {
        try {
            loading.value = true;
            error.value = null;

            const data = new FormData();
            data.append('nombre', formData.nombre);
            data.append('descripcion', formData.descripcion);
            const activoString = formData.estado ? '1' : '0'
            data.append('estado', activoString);

            if (formData.archivo) {
                data.append('archivo_pdf', formData.archivo);
            }

            const config = getApiConfig();
            const response = await api.post('/folletos', data, config);

            const newFolleto = response.data.data || response.data;
            folletos.value.unshift(newFolleto);

            return response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al crear el folleto';
            console.error('Error creating folleto:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateFolleto = async (id: number, formData: Partial<FolletoFormData>) => {
        try {
            loading.value = true;
            error.value = null;

            const data = new FormData();

            if (formData.nombre !== undefined) {
                data.append('nombre', formData.nombre);
            }
            if (formData.descripcion !== undefined) {
                data.append('descripcion', formData.descripcion);
            }
            if (formData.estado !== undefined) {
                const activoString = formData.estado ? '1' : '0'
                data.append('estado', activoString);
            }
            if (formData.reset_descargas !== undefined) {
                const activoString = formData.reset_descargas ? '1' : '0'
                data.append('reset_descargas', activoString);
            }

            if (formData.archivo) {
                data.append('archivo_pdf', formData.archivo);
            }

            // Para Laravel, usar _method para PUT/PATCH
            data.append('_method', 'PUT');

            const config = getApiConfig();
            const response = await api.post(`/folletos/${id}`, data, config);

            const updatedFolleto = response.data.data || response.data;

            // Actualizar en la lista
            const index = folletos.value.findIndex(f => f.id === id);
            if (index !== -1) {
                folletos.value[index] = updatedFolleto;
            }

            // Actualizar el folleto actual si es el mismo
            if (folleto.value?.id === id) {
                folleto.value = updatedFolleto;
            }

            return response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al actualizar el folleto';
            console.error('Error updating folleto:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteFolleto = async (id: number) => {
        try {
            loading.value = true;
            error.value = null;

            const config = getApiConfig();
            await api.delete(`/folletos/${id}`, config);

            // Eliminar de la lista
            folletos.value = folletos.value.filter(f => f.id !== id);

            // Limpiar folleto actual si es el eliminado
            if (folleto.value?.id === id) {
                folleto.value = null;
            }

            return true;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al eliminar el folleto';
            console.error('Error deleting folleto:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const toggleEstado = async (id: number) => {
        try {
            loading.value = true;
            error.value = null;

            const config = getApiConfig();
            const response = await api.post(`/folletos/${id}/toggle-estado`, {}, config);

            const updatedFolleto = response.data.data || response.data;

            // Actualizar en la lista
            const index = folletos.value.findIndex(f => f.id === id);
            if (index !== -1) {
                folletos.value[index] = updatedFolleto;
            }

            // Actualizar folleto actual si es el mismo
            if (folleto.value?.id === id) {
                folleto.value = updatedFolleto;
            }

            return response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cambiar el estado';
            console.error('Error toggling folleto status:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const incrementarDescargas = async (id: number) => {
        try {
            const config = getApiConfig();
            await api.post(`/folletos/${id}/registrar-descarga`, {}, config);

            // Actualizar en la lista
            const index = folletos.value.findIndex(f => f.id === id);
            if (index !== -1) {
                folletos.value[index].descargas += 1;
            }

            // Actualizar folleto actual si es el mismo
            if (folleto.value?.id === id) {
                folleto.value.descargas += 1;
            }

            return true;
        } catch (err: any) {
            console.error('Error al incrementar descargas:', err);
            return false;
        }
    };

    const fetchEstadisticas = async () => {
        try {
            loading.value = true;
            error.value = null;

            const response = await api.get('/folletos/estadisticas');
            estadisticas.value = response.data.data || response.data;

            return estadisticas.value;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar estadísticas';
            console.error('Error fetching statistics:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const descargarArchivo = async (id: number, nombre: string) => {
        try {
            // Primero registrar la descarga
            await incrementarDescargas(id);

            // Luego descargar el archivo
            const config = {
                // ...getApiConfig(),
                responseType: 'blob' as const
            };

            const response = await api.get(`/folletos/descargar/${id}`, config);

            // Crear URL para descarga
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `${nombre}.pdf`);
            document.body.appendChild(link);
            link.click();
            link.remove();

            return true;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al descargar el archivo';
            console.error('Error downloading file:', err);
            throw err;
        }
    };

    const setFilters = (newFilters: Partial<FolletoFilters>) => {
        filters.value = { ...filters.value, ...newFilters };
        fetchFolletos(1);
    };

    const resetFilters = () => {
        filters.value = {
            search: '',
            order_by: 'created_at',
            order_dir: 'desc',
            per_page: 15
        };
        fetchFolletos(1);
    };

    const clearState = () => {
        folleto.value = null;
        error.value = null;
    };

    // Retornar todo lo necesario
    return {
        // Estado
        folletos,
        folleto,
        estadisticas,
        loading,
        error,
        filters,
        pagination,

        // Getters
        totalDescargas,
        folletosActivos,
        folletosInactivos,
        folletosPopulares,
        filteredFolletos,
        chartData,

        // Acciones
        fetchFolletos,
        fetchFolleto,
        createFolleto,
        updateFolleto,
        deleteFolleto,
        toggleEstado,
        incrementarDescargas,
        fetchEstadisticas,
        descargarArchivo,
        setFilters,
        resetFilters,
        clearState
    };
});