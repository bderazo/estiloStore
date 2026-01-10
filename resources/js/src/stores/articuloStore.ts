import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { articuloService } from '@/services/articuloService';
import type {
    Articulo,
    ArticuloForm,
    ArticuloFilters,
    ArticuloPagination,
    ArticuloSelectItem,
} from '@/types/articulo';
import Swal from 'sweetalert2';

export const useArticuloStore = defineStore('articulo', () => {
    const articulos = ref<Articulo[]>([]);
    const selectedArticulo = ref<Articulo | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);
    const pagination = ref<ArticuloPagination>({
        current_page: 1,
        total: 0,
        per_page: 10,
        last_page: 1,
        from: 1,
        to: 1,
    });
    const filters = ref<ArticuloFilters>({
        search: '',
        categoria_id: '',
        marca_id: '',
        activo: '',
        current_page: 1,
        per_page: 10,
    });
    const articuloSelect = ref<ArticuloSelectItem[]>([]);
    const loadingSelect = ref(false);

    // Computed
    const totalArticulos = computed(() => pagination.value.total);
    const currentPage = computed(() => pagination.value.current_page);
    const lastPage = computed(() => pagination.value.last_page);
    const hasNextPage = computed(() => currentPage.value < lastPage.value);
    const hasPrevPage = computed(() => currentPage.value > 1);
    const activeArticulos = computed(() => articulos.value.filter((a) => a.activo));
    const inactiveArticulos = computed(() => articulos.value.filter((a) => !a.activo));

    // Actions
    const fetchArticulos = async () => {
        loading.value = true;
        error.value = null;
        try {
            const response = await articuloService.getArticulos(
                filters.value.current_page,
                filters.value.per_page,
                filters.value.search,
                filters.value.categoria_id,
                filters.value.marca_id,
                filters.value.activo
            );
            articulos.value = response.data;
            pagination.value = response.pagination;
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error fetching artículos';
        } finally {
            loading.value = false;
        }
    };

    const fetchArticulo = async (id: number) => {
        loading.value = true;
        error.value = null;
        try {
            selectedArticulo.value = await articuloService.getArticulo(id);
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error fetching artículo';
        } finally {
            loading.value = false;
        }
    };

    const createArticulo = async (data: ArticuloForm) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await articuloService.createArticulo(data);

            if (response) {
                articulos.value.unshift(response);
                return response;
            } else {
                error.value = 'Error al crear artículo';
                return response;
            }
        } catch (err: any) {
            error.value = err.message || 'Error al crear artículo';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateArticulo = async (id: number, data: ArticuloForm) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await articuloService.updateArticulo(id, data);

            if (response) {
                const index = articulos.value.findIndex((a: Articulo) => a.id === id);
                if (index !== -1) {
                    articulos.value[index] = response;
                }
                selectedArticulo.value = response;
                return response;
            } else {
                error.value = 'Error al actualizar artículo';
                return response;
            }
        } catch (err: any) {
            error.value = err.message || 'Error al actualizar artículo';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteArticulo = async (id: number) => {
        loading.value = true;
        error.value = null;
        try {
            await articuloService.deleteArticulo(id);
            articulos.value = articulos.value.filter((a: Articulo) => a.id !== id);
            pagination.value.total--;
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error deleting artículo';
            throw error.value;
        } finally {
            loading.value = false;
        }
    };

    const toggleActivo = async (id: number) => {
        try {
            const updatedArticulo = await articuloService.toggleActivo(id);
            const index = articulos.value.findIndex((a: Articulo) => a.id === id);
            if (index !== -1) {
                articulos.value[index] = updatedArticulo;
            }

            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: `Artículo ${updatedArticulo.activo ? 'activado' : 'desactivado'}`,
                timer: 2000,
                showConfirmButton: false,
            });

            return updatedArticulo;
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error toggling artículo status';
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.value || 'Error desconocido',
            });
            throw error.value;
        }
    };

    const toggleDestacado = async (id: number) => {
        try {
            const updatedArticulo = await articuloService.toggleDestacado(id);
            const index = articulos.value.findIndex((a: Articulo) => a.id === id);
            if (index !== -1) {
                articulos.value[index] = updatedArticulo;
            }

            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: `Artículo ${updatedArticulo.destacado ? 'destacado' : 'des-destacado'}`,
                timer: 2000,
                showConfirmButton: false,
            });

            return updatedArticulo;
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error toggling artículo destacado';
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.value || 'Error desconocido',
            });
            throw error.value;
        }
    };

    const fetchArticuloForSelect = async () => {
        loadingSelect.value = true;
        error.value = null;
        try {
            articuloSelect.value =
                await articuloService.getArticulosForSelect();
        } catch (err: any) {
            error.value =
                err.response?.data?.message ||
                err.message ||
                'Error fetching artículos for select';
        } finally {
            loadingSelect.value = false;
        }
    };

    const setFilters = (newFilters: Partial<ArticuloFilters>) => {
        filters.value = { ...filters.value, ...newFilters };
        filters.value.current_page = 1;
    };

    const clearFilters = () => {
        filters.value = {
            search: '',
            categoria_id: '',
            marca_id: '',
            activo: '',
            current_page: 1,
            per_page: 10,
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
        articulos,
        selectedArticulo,
        loading,
        error,
        pagination,
        filters,
        articuloSelect,
        loadingSelect,

        // Computed
        totalArticulos,
        currentPage,
        lastPage,
        hasNextPage,
        hasPrevPage,
        activeArticulos,
        inactiveArticulos,

        // Actions
        fetchArticulos,
        fetchArticulo,
        createArticulo,
        updateArticulo,
        deleteArticulo,
        toggleActivo,
        toggleDestacado,
        fetchArticuloForSelect,
        setFilters,
        clearFilters,
        clearError,
        goToPage,
    };
});
