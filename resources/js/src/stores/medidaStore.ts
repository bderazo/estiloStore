/**
 * Store de Medidas para Pinia
 * Gestiona el estado global de medidas
 */

import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';
import type {
  Medida,
  MedidaForm,
  MedidaFilters,
  MedidaPagination,
  MedidaSelectItem,
} from '@/types/medida';
import * as medidaService from '@/services/medidaService';

export const useMedidaStore = defineStore('medida', () => {
  // Estado
  const medidas = ref<Medida[]>([]);
  const selectedMedida = ref<Medida | null>(null);
  const loading = ref(false);
  const error = ref<string | null>(null);
  const pagination = ref<MedidaPagination>({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
    from: 0,
    to: 0,
  });
  const filters = ref<MedidaFilters>({
    search: '',
    activo: '',
    sort_by: 'nombre',
    sort_order: 'asc',
    per_page: 15,
  });
  const medidaSelect = ref<MedidaSelectItem[]>([]);
  const loadingSelect = ref(false);

  // Getters
  const totalMedidas = computed(() => pagination.value?.total || 0);
  const currentPage = computed(() => pagination.value?.current_page || 1);
  const lastPage = computed(() => pagination.value?.last_page || 1);
  const hasNextPage = computed(() => currentPage.value < lastPage.value);
  const hasPrevPage = computed(() => currentPage.value > 1);
  const activeMedidas = computed(() => medidas.value.filter(m => m.activo));
  const inactiveMedidas = computed(() => medidas.value.filter(m => !m.activo));

  // Acciones
  /**
   * Obtiene la lista de medidas
   */
  const fetchMedidas = async (customFilters?: Partial<MedidaFilters>) => {
    loading.value = true;
    error.value = null;
    try {
      if (customFilters) {
        filters.value = { ...filters.value, ...customFilters };
      }

      const response = await medidaService.getMedidas(filters.value);

      if (response.success) {
        medidas.value = response.data;
        pagination.value = response.pagination;
      } else {
        error.value = response.message || 'Error al obtener medidas';
      }
    } catch (err: any) {
      error.value = err.message || 'Error al obtener medidas';
    } finally {
      loading.value = false;
    }
  };

  /**
   * Obtiene una medida por ID
   */
  const fetchMedida = async (id: number) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await medidaService.getMedida(id);

      if (response.success && response.data) {
        selectedMedida.value = response.data;
        return response.data;
      } else {
        error.value = response.message || 'Error al obtener medida';
        return null;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al obtener medida';
      return null;
    } finally {
      loading.value = false;
    }
  };

  /**
   * Crea una nueva medida
   */
  const createMedida = async (data: MedidaForm) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await medidaService.createMedida(data);

      if (response.success && response.data) {
        medidas.value.unshift(response.data);
        return response;
      } else {
        error.value = response.message || 'Error al crear medida';
        return response;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al crear medida';
      return { success: false, message: err.message, data: null, errors: null };
    } finally {
      loading.value = false;
    }
  };

  /**
   * Actualiza una medida existente
   */
  const updateMedida = async (id: number, data: Partial<MedidaForm>) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await medidaService.updateMedida(id, data);

      if (response.success && response.data) {
        const index = medidas.value.findIndex(m => m.id === id);
        if (index !== -1) {
          medidas.value[index] = response.data;
        }
        selectedMedida.value = response.data;
        return response;
      } else {
        error.value = response.message || 'Error al actualizar medida';
        return response;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al actualizar medida';
      return { success: false, message: err.message, data: null, errors: null };
    } finally {
      loading.value = false;
    }
  };

  /**
   * Elimina una medida
   */
  const deleteMedida = async (id: number) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await medidaService.deleteMedida(id);

      if (response.success) {
        medidas.value = medidas.value.filter(m => m.id !== id);
        if (selectedMedida.value?.id === id) {
          selectedMedida.value = null;
        }
        return response;
      } else {
        error.value = response.message || 'Error al eliminar medida';
        return response;
      }
    } catch (err: any) {
      error.value = err.message || 'Error al eliminar medida';
      return { success: false, message: err.message, data: null, errors: null };
    } finally {
      loading.value = false;
    }
  };

  /**
   * Cambia el estado activo/inactivo de una medida
   */
  const toggleActivo = async (id: number) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await medidaService.toggleMedidaActivo(id);

      if (response.success && response.data) {
        const index = medidas.value.findIndex((m: Medida) => m.id === id);
        if (index !== -1) {
          medidas.value[index] = response.data;
        }
        selectedMedida.value = response.data;

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: response.data.activo ? 'Medida activada correctamente' : 'Medida desactivada correctamente',
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
   * Obtiene las medidas para componentes select
   */
  const fetchMedidasForSelect = async () => {
    loadingSelect.value = true;
    try {
      const response = await medidaService.getMedidasForSelect();

      if (response.success) {
        medidaSelect.value = response.data;
      } else {
        error.value = response.message || 'Error al obtener medidas';
      }
    } catch (err: any) {
      error.value = err.message || 'Error al obtener medidas';
    } finally {
      loadingSelect.value = false;
    }
  };

  /**
   * Actualiza los filtros
   */
  const setFilters = (newFilters: Partial<MedidaFilters>) => {
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
    await fetchMedidas({ ...filters.value, per_page: filters.value.per_page });
  };

  return {
    // Estado
    medidas,
    selectedMedida,
    loading,
    error,
    pagination,
    filters,
    medidaSelect,
    loadingSelect,

    // Getters
    totalMedidas,
    currentPage,
    lastPage,
    hasNextPage,
    hasPrevPage,
    activeMedidas,
    inactiveMedidas,

    // Acciones
    fetchMedidas,
    fetchMedida,
    createMedida,
    updateMedida,
    deleteMedida,
    toggleActivo,
    fetchMedidasForSelect,
    setFilters,
    clearFilters,
    clearError,
    goToPage,
  };
});
