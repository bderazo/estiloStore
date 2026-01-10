/**
 * Store de Sabores para Pinia
 * Gestiona el estado global de sabores
 */

import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';
import type {
  Sabor,
  SaborForm,
  SaborFilters,
  SaborPagination,
  SaborSelectItem,
} from '@/types/sabor';
import * as saborService from '@/services/saborService';

export const useSaborStore = defineStore('sabor', () => {
  // Estado
  const sabores = ref<Sabor[]>([]);
  const selectedSabor = ref<Sabor | null>(null);
  const loading = ref(false);
  const error = ref<string | null>(null);
  const pagination = ref<SaborPagination>({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
    from: 0,
    to: 0,
  });
  const filters = ref<SaborFilters>({
    search: '',
    activo: '',
    sort_by: 'nombre',
    sort_order: 'asc',
    per_page: 15,
  });
  const saborSelect = ref<SaborSelectItem[]>([]);
  const loadingSelect = ref(false);

  // Getters
  const totalSabores = computed(() => pagination.value?.total || 0);
  const currentPage = computed(() => pagination.value?.current_page || 1);
  const lastPage = computed(() => pagination.value?.last_page || 1);
  const hasNextPage = computed(() => currentPage.value < lastPage.value);
  const hasPrevPage = computed(() => currentPage.value > 1);
  const activeSabores = computed(() => sabores.value.filter((s: Sabor) => s.activo));
  const inactiveSabores = computed(() => sabores.value.filter((s: Sabor) => !s.activo));

  // Acciones
  /**
   * Obtiene la lista de sabores
   */
  const fetchSabores = async (customFilters?: Partial<SaborFilters>) => {
    loading.value = true;
    error.value = null;
    try {
      if (customFilters) {
        filters.value = { ...filters.value, ...customFilters };
      }

      const response = await saborService.getSabores(filters.value);

      if (response.success) {
        sabores.value = response.data;
        pagination.value = response.pagination;
      } else {
        error.value = response.message || 'Error al obtener sabores';
      }
    } catch (err: any) {
      error.value = err.message || 'Error al obtener sabores';
    } finally {
      loading.value = false;
    }
  };

  /**
   * Obtiene un sabor por ID
   */
  const fetchSabor = async (id: number) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await saborService.getSabor(id);

      if (response.success && response.data) {
        selectedSabor.value = response.data;
        return response.data;
      } else {
        error.value = response.message || 'Error al obtener sabor';
        return null;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al obtener sabor';
      return null;
    } finally {
      loading.value = false;
    }
  };

  /**
   * Crea un nuevo sabor
   */
  const createSabor = async (data: SaborForm) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await saborService.createSabor(data);

      if (response.success && response.data) {
        sabores.value.unshift(response.data);
        return response;
      } else {
        error.value = response.message || 'Error al crear sabor';
        return response;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al crear sabor';
      return { success: false, message: err.message, data: null, errors: null };
    } finally {
      loading.value = false;
    }
  };

  /**
   * Actualiza un sabor existente
   */
  const updateSabor = async (id: number, data: Partial<SaborForm>) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await saborService.updateSabor(id, data);

      if (response.success && response.data) {
        const index = sabores.value.findIndex((s: Sabor) => s.id === id);
        if (index !== -1) {
          sabores.value[index] = response.data;
        }
        selectedSabor.value = response.data;
        return response;
      } else {
        error.value = response.message || 'Error al actualizar sabor';
        return response;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al actualizar sabor';
      return { success: false, message: err.message, data: null, errors: null };
    } finally {
      loading.value = false;
    }
  };

  /**
   * Elimina un sabor
   */
  const deleteSabor = async (id: number) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await saborService.deleteSabor(id);

      if (response.success) {
        sabores.value = sabores.value.filter((s: Sabor) => s.id !== id);
        if (selectedSabor.value?.id === id) {
          selectedSabor.value = null;
        }
        return response;
      } else {
        error.value = response.message || 'Error al eliminar sabor';
        return response;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al eliminar sabor';
      return { success: false, message: err.message, data: null, errors: null };
    } finally {
      loading.value = false;
    }
  };

  /**
   * Cambia el estado activo/inactivo de un sabor
   */
  const toggleActivo = async (id: number) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await saborService.toggleSaborActivo(id);

      if (response.success && response.data) {
        const index = sabores.value.findIndex((s: Sabor) => s.id === id);
        if (index !== -1) {
          sabores.value[index] = response.data;
        }
        selectedSabor.value = response.data;

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: response.data.activo ? 'Sabor activado correctamente' : 'Sabor desactivado correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

        return response;
      } else {
        error.value = response.message || 'Error al cambiar estado';
        return response;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al cambiar estado';
      return { success: false, message: err.message, data: null, errors: null };
    } finally {
      loading.value = false;
    }
  };

  /**
   * Obtiene los sabores para componentes select
   */
  const fetchSaboresForSelect = async () => {
    loadingSelect.value = true;
    try {
      const response = await saborService.getSaboresForSelect();

      if (response.success) {
        saborSelect.value = response.data;
      } else {
        error.value = response.message || 'Error al obtener sabores';
      }
    } catch (err: any) {
      error.value = err.message || 'Error al obtener sabores';
    } finally {
      loadingSelect.value = false;
    }
  };

  /**
   * Actualiza los filtros
   */
  const setFilters = (newFilters: Partial<SaborFilters>) => {
    filters.value = { ...filters.value, ...newFilters };
  };

  /**
   * Limpia los filtros
   */
  const clearFilters = () => {
    filters.value = {
      search: '',
      activo: '',
      sort_by: 'nombre',
      sort_order: 'asc',
      per_page: 15,
    };
  };

  /**
   * Limpia el error
   */
  const clearError = () => {
    error.value = null;
  };

  /**
   * Cambia de página
   */
  const goToPage = async (page: number) => {
    await fetchSabores({ ...filters.value, per_page: filters.value.per_page });
  };

  return {
    // Estado
    sabores,
    selectedSabor,
    loading,
    error,
    pagination,
    filters,
    saborSelect,
    loadingSelect,

    // Getters
    totalSabores,
    currentPage,
    lastPage,
    hasNextPage,
    hasPrevPage,
    activeSabores,
    inactiveSabores,

    // Acciones
    fetchSabores,
    fetchSabor,
    createSabor,
    updateSabor,
    deleteSabor,
    toggleActivo,
    fetchSaboresForSelect,
    setFilters,
    clearFilters,
    clearError,
    goToPage,
  };
});
