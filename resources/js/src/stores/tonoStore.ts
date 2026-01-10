import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { tonoService } from '@/services/tonoService';
import type {
    Tono,
    TonoForm,
    TonoFilters,
    TonoPagination,
    TonoSelectItem,
} from '@/types/tono';
import Swal from 'sweetalert2';

export const useTonoStore = defineStore('tono', () => {
    const tonos = ref<Tono[]>([]);
    const selectedTono = ref<Tono | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);
    const pagination = ref<TonoPagination>({
        current_page: 1,
        total: 0,
        per_page: 10,
        last_page: 1,
        from: 1,
        to: 1,
    });
    const filters = ref<TonoFilters>({
        search: '',
        activo: '',
        current_page: 1,
        per_page: 10,
    });
    const tonoSelect = ref<TonoSelectItem[]>([]);
    const loadingSelect = ref(false);

    // Computed
    const totalTonos = computed(() => pagination.value.total);
    const currentPage = computed(() => pagination.value.current_page);
    const lastPage = computed(() => pagination.value.last_page);
    const hasNextPage = computed(() => currentPage.value < lastPage.value);
    const hasPrevPage = computed(() => currentPage.value > 1);
    const activeTonos = computed(() => tonos.value.filter((t) => t.activo));
    const inactiveTonos = computed(() => tonos.value.filter((t) => !t.activo));

    // Actions
    const fetchTonos = async () => {
        loading.value = true;
        error.value = null;
        try {
            const response = await tonoService.getTonos(
                filters.value.current_page,
                filters.value.per_page,
                filters.value.search,
                filters.value.activo
            );
            tonos.value = response.data;
            pagination.value = response.pagination;
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error fetching tonos';
        } finally {
            loading.value = false;
        }
    };

    const fetchTono = async (id: number) => {
        loading.value = true;
        error.value = null;
        try {
            selectedTono.value = await tonoService.getTono(id);
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error fetching tono';
        } finally {
            loading.value = false;
        }
    };

  const createTono = async (data: TonoForm) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await tonoService.createTono(data);

      if (response.success && response.data) {
        tonos.value.unshift(response.data);
        return response;
      } else {
        error.value = response.message || 'Error al crear tono';
        return response;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al crear tono';
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updateTono = async (id: number, data: TonoForm) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await tonoService.updateTono(id, data);

      if (response.success && response.data) {
        const index = tonos.value.findIndex((t: Tono) => t.id === id);
        if (index !== -1) {
          tonos.value[index] = response.data;
        }
        selectedTono.value = response.data;
        return response;
      } else {
        error.value = response.message || 'Error al actualizar tono';
        return response;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al actualizar tono';
      throw err;
    } finally {
      loading.value = false;
    }
  };

    const deleteTono = async (id: number) => {
        loading.value = true;
        error.value = null;
        try {
            await tonoService.deleteTono(id);
            tonos.value = tonos.value.filter((t) => t.id !== id);
            pagination.value.total--;
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error deleting tono';
            throw error.value;
        } finally {
            loading.value = false;
        }
    };

    const toggleActivo = async (id: number) => {
        try {
            const updatedTono = await tonoService.toggleActivo(id);
            const index = tonos.value.findIndex((t) => t.id === id);
            if (index !== -1) {
                tonos.value[index] = updatedTono;
            }

            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: `Tono ${updatedTono.activo ? 'activado' : 'desactivado'}`,
                timer: 2000,
                showConfirmButton: false,
            });

            return updatedTono;
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error toggling tono status';
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.value,
            });
            throw error.value;
        }
    };

    const fetchTonoForSelect = async () => {
        loadingSelect.value = true;
        error.value = null;
        try {
            tonoSelect.value = await tonoService.TonosForSelect();
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error fetching tonos for select';
        } finally {
            loadingSelect.value = false;
        }
    };

    const setFilters = (newFilters: Partial<TonoFilters>) => {
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
        tonos,
        selectedTono,
        loading,
        error,
        pagination,
        filters,
        tonoSelect,
        loadingSelect,

        // Computed
        totalTonos,
        currentPage,
        lastPage,
        hasNextPage,
        hasPrevPage,
        activeTonos,
        inactiveTonos,

        // Actions
        fetchTonos,
        fetchTono,
        createTono,
        updateTono,
        deleteTono,
        toggleActivo,
        fetchTonoForSelect,
        setFilters,
        clearFilters,
        clearError,
        goToPage,
    };
});
