// src/stores/transporteStore.ts

import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { useToast } from 'vue-toastification';
import type {
    Transporte,
    TransporteFiltros,
    TransporteFormData,
    PaginationMeta,
    EstadisticasTransporte
} from '../types/transporte';
import api from '../services/api';

export const useTransporteStore = defineStore('transporte', () => {
    const toast = useToast();

    // State
    const transportes = ref<Transporte[]>([]);
    const transporte = ref<Transporte | null>(null);
    const cooperativas = ref<string[]>([]);
    const estadisticas = ref<EstadisticasTransporte | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);
    const filtros = ref<TransporteFiltros>({
        search: '',
        cooperativa: '',
        estado: '',
        order_by: 'precio',
        order_dir: 'asc',
        per_page: 15,
        page: 1
    });
    const meta = ref<PaginationMeta | null>(null);

    // Getters
    const transportesActivos = computed(() =>
        transportes.value.filter(t => t.estado)
    );

    const transportesPorCooperativa = computed(() => {
        const grupos: Record<string, Transporte[]> = {};
        transportes.value.forEach(transporte => {
            if (!grupos[transporte.cooperativa]) {
                grupos[transporte.cooperativa] = [];
            }
            grupos[transporte.cooperativa].push(transporte);
        });
        return grupos;
    });

    const precioPromedio = computed(() => {
        if (transportes.value.length === 0) return 0;
        const suma = transportes.value.reduce((acc, t) => acc + t.precio, 0);
        return suma / transportes.value.length;
    });

    const rutasUnicas = computed(() => {
        return [...new Set(transportes.value.map(t => t.ruta))];
    });

    // Helper function for notifications
    const showSuccess = (message: string) => {
        toast.success(message);
    };

    const showError = (message: string) => {
        toast.error(message);
    };

    const showInfo = (message: string) => {
        toast.info(message);
    };

    const showWarning = (message: string) => {
        toast.warning(message);
    };

    // Actions
    const fetchTransportes = async (params?: TransporteFiltros) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.get('/transportes', {
                params: { ...filtros.value, ...params }
            });

            transportes.value = response.data.data;
            meta.value = response.data.meta;

            if (params) {
                filtros.value = { ...filtros.value, ...params };
            }

            return response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar transportes';
            //showError(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const fetchTransporte = async (id: number) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.get(`/transportes/${id}`);
            transporte.value = response.data.data;
            return transporte.value;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar transporte';
            //showError(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const createTransporte = async (formData: TransporteFormData) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.post('/transportes', formData);
            showSuccess('Transporte creado exitosamente');

            // Agregar al listado
            transportes.value.unshift(response.data.data);

            return response.data.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al crear transporte';

            // Mostrar errores de validación
            if (err.response?.status === 422) {
                const errors = err.response.data.errors;
                Object.keys(errors).forEach(key => {
                    showError(errors[key][0]);
                });
            } else {
                //showError(error.value);
            }

            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateTransporte = async (id: number, formData: TransporteFormData) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.put(`/transportes/${id}`, formData);
            showSuccess('Transporte actualizado exitosamente');

            // Actualizar en el listado
            const index = transportes.value.findIndex(t => t.id === id);
            if (index !== -1) {
                transportes.value[index] = response.data.data;
            }

            return response.data.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al actualizar transporte';

            if (err.response?.status === 422) {
                const errors = err.response.data.errors;
                Object.keys(errors).forEach(key => {
                    showError(errors[key][0]);
                });
            } else {
                //showError(error.value);
            }

            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteTransporte = async (id: number) => {
        loading.value = true;
        error.value = null;

        try {
            await api.delete(`/transportes/${id}`);
            showSuccess('Transporte eliminado exitosamente');

            // Eliminar del listado
            transportes.value = transportes.value.filter(t => t.id !== id);
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al eliminar transporte';
            //showError(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const toggleEstado = async (id: number) => {
        try {
            const response = await api.patch(`/transportes/${id}/toggle-estado`);
            showSuccess('Estado actualizado exitosamente');

            // Actualizar en el listado
            const index = transportes.value.findIndex(t => t.id === id);
            if (index !== -1) {
                transportes.value[index] = response.data.data;
            }

            return response.data.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cambiar estado';
            //showError(error.value);
            throw err;
        }
    };

    const fetchCooperativas = async () => {
        try {
            const response = await api.get('/transportes/cooperativas');
            cooperativas.value = response.data.data;
            return cooperativas.value;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar cooperativas';
            //showError(error.value);
            throw err;
        }
    };

    const fetchEstadisticas = async () => {
        try {
            const response = await api.get('/transportes/estadisticas');
            estadisticas.value = response.data.data;
            return estadisticas.value;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar estadísticas';
            //showError(error.value);
            throw err;
        }
    };

    const fetchRutasDisponibles = async () => {
        try {
            const response = await api.get('/transportes/rutas/disponibles');
            return response.data.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar rutas disponibles';
            //showError(error.value);
            throw err;
        }
    };

    const resetFiltros = () => {
        filtros.value = {
            search: '',
            cooperativa: '',
            estado: '',
            order_by: 'created_at',
            order_dir: 'desc',
            per_page: 15,
            page: 1
        };
    };

    const exportToExcel = async () => {
        try {
            const response = await api.get('/transportes', {
                params: { ...filtros.value, per_page: -1 },
                responseType: 'blob'
            });

            // Crear URL del blob
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `transportes_${new Date().toISOString().split('T')[0]}.xlsx`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showSuccess('Exportación completada');
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al exportar';
            //showError(error.value);
            throw err;
        }
    };

    return {
        // State
        transportes,
        transporte,
        cooperativas,
        estadisticas,
        loading,
        error,
        filtros,
        meta,

        // Getters
        transportesActivos,
        transportesPorCooperativa,
        precioPromedio,
        rutasUnicas,

        // Actions
        fetchTransportes,
        fetchTransporte,
        createTransporte,
        updateTransporte,
        deleteTransporte,
        toggleEstado,
        fetchCooperativas,
        fetchEstadisticas,
        fetchRutasDisponibles,
        resetFiltros,
        exportToExcel
    };
});