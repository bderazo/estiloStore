import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { modeloService } from '@/services/modeloService';
import type {
    Modelo,
    ModeloForm,
    ModeloFilters,
    ModeloPagination,
    ModeloSelectItem,
} from '@/types/modelo';
import Swal from 'sweetalert2';

export const useModeloStore = defineStore('modelo', () => {
    const modelos = ref<Modelo[]>([]);
    const selectedModelo = ref<Modelo | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);
    const pagination = ref<ModeloPagination>({
        current_page: 1,
        total: 0,
        per_page: 10,
        last_page: 1,
        from: 1,
        to: 1,
    });
    const filters = ref<ModeloFilters>({
        search: '',
        activo: '',
        current_page: 1,
        per_page: 10,
    });
    const modeloSelect = ref<ModeloSelectItem[]>([]);
    const loadingSelect = ref(false);

    // Computed
    const totalModelos = computed(() => pagination.value.total);
    const currentPage = computed(() => pagination.value.current_page);
    const lastPage = computed(() => pagination.value.last_page);
    const hasNextPage = computed(() => currentPage.value < lastPage.value);
    const hasPrevPage = computed(() => currentPage.value > 1);
    const activeModelos = computed(() => modelos.value.filter((m) => m.activo));
    const inactiveModelos = computed(() => modelos.value.filter((m) => !m.activo));

    // Actions
    const fetchModelos = async () => {
        loading.value = true;
        error.value = null;
        try {
            const response = await modeloService.getModelos(
                filters.value.current_page,
                filters.value.per_page,
                filters.value.search,
                filters.value.activo
            );
            modelos.value = response.data;
            pagination.value = response.pagination;
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error fetching modelos';
        } finally {
            loading.value = false;
        }
    };

    const fetchModelo = async (id: number) => {
        loading.value = true;
        error.value = null;
        try {
            selectedModelo.value = await modeloService.getModelo(id);
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error fetching modelo';
        } finally {
            loading.value = false;
        }
    };

    const createModelo = async (data: ModeloForm) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await modeloService.createModelo(data);

            if (response.success && response.data) {
                modelos.value.unshift(response.data);
                return response;
            } else {
                error.value = response.message || 'Error al crear modelo';
                return response;
            }
        } catch (err: any) {
            error.value = err.message || 'Error al crear modelo';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateModelo = async (id: number, data: ModeloForm) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await modeloService.updateModelo(id, data);

            if (response.success && response.data) {
                const index = modelos.value.findIndex((m: Modelo) => m.id === id);
                if (index !== -1) {
                    modelos.value[index] = response.data;
                }
                selectedModelo.value = response.data;
                return response;
            } else {
                error.value = response.message || 'Error al actualizar modelo';
                return response;
            }
        } catch (err: any) {
            error.value = err.message || 'Error al actualizar modelo';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteModelo = async (id: number) => {
        loading.value = true;
        error.value = null;
        try {
            await modeloService.deleteModelo(id);
            modelos.value = modelos.value.filter((m: Modelo) => m.id !== id);
            pagination.value.total--;
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error deleting modelo';
            throw error.value;
        } finally {
            loading.value = false;
        }
    };

    const toggleActivo = async (id: number) => {
        try {
            const updatedModelo = await modeloService.toggleActivo(id);
            const index = modelos.value.findIndex((m: Modelo) => m.id === id);
            if (index !== -1) {
                modelos.value[index] = updatedModelo;
            }

            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: `Modelo ${updatedModelo.activo ? 'activado' : 'desactivado'}`,
                timer: 2000,
                showConfirmButton: false,
            });

            return updatedModelo;
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error toggling modelo status';
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.value || 'Error desconocido',
            });
            throw error.value;
        }
    };

    const fetchModeloForSelect = async () => {
        loadingSelect.value = true;
        error.value = null;
        try {
            modeloSelect.value =
                await modeloService.getModelosForSelect();
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error fetching modelos for select';
        } finally {
            loadingSelect.value = false;
        }
    };

    const setFilters = (newFilters: Partial<ModeloFilters>) => {
        filters.value = { ...filters.value, ...newFilters };
        filters.value.current_page = 1;
    };

    const clearFilters = () => {
        filters.value = {
            search: '',
            activo: '',
            current_page: 1,
        };
    };

    const clearError = () => {
        error.value = null;
    };

    const goToPage = (page: number) => {
        filters.value.current_page = page;
    };

    return {
        // State
        modelos,
        selectedModelo,
        loading,
        error,
        pagination,
        filters,
        modeloSelect,
        loadingSelect,

        // Computed
        totalModelos,
        currentPage,
        lastPage,
        hasNextPage,
        hasPrevPage,
        activeModelos,
        inactiveModelos,

        // Actions
        fetchModelos,
        fetchModelo,
        createModelo,
        updateModelo,
        deleteModelo,
        toggleActivo,
        fetchModeloForSelect,
        setFilters,
        clearFilters,
        clearError,
        goToPage,
    };
});
