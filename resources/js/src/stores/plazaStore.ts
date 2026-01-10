import { defineStore } from 'pinia';
import type {
  Plaza,
  PlazaForm,
  PlazaFilters,
  PlazaState,
  PlazaOption
} from '../types/plaza';
import { plazaService } from '../services/plazaService';
import Swal from 'sweetalert2';

export const usePlazaStore = defineStore('plaza', {
  state: (): PlazaState => ({
    plazas: [],
    plaza: null,
    loading: false,
    error: null,
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 15,
      total: 0,
      from: null,
      to: null,
    },
    filters: {
      search: '',
      activo: '',
      sort_by: 'nombre',
      sort_order: 'asc',
      per_page: 15,
    },
  }),

  getters: {
    plazasActivas: (state): Plaza[] => {
      return state.plazas.filter((plaza: Plaza) => plaza.activo);
    },

    plazaById: (state) => {
      return (id: number): Plaza | undefined => {
        return state.plazas.find((plaza: Plaza) => plaza.id === id);
      };
    },

    hasPlazas: (state): boolean => {
      return state.plazas.length > 0;
    },

    totalPages: (state): number => {
      return state.pagination.last_page;
    },

    estadisticas: (state) => {
      return {
        total: state.plazas.length,
        activas: state.plazas.filter(p => p.activo).length,
        inactivas: state.plazas.filter(p => !p.activo).length,
      };
    },
  },

  actions: {
    // Obtener lista de plazas con filtros
    async fetchPlazas(filters?: Partial<PlazaFilters>) {
      this.loading = true;
      this.error = null;

      try {
        // Actualizar filtros si se proporcionan
        if (filters) {
          this.filters = { ...this.filters, ...filters };
        }

        const response = await plazaService.getPlazas(this.filters);

        this.plazas = response.data;
        this.pagination = response.pagination;

      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al obtener las plazas';
        console.error('Error fetching plazas:', error);
      } finally {
        this.loading = false;
      }
    },

    // Obtener plaza específica
    async fetchPlaza(id: number) {
      this.loading = true;
      this.error = null;

      try {
        const response = await plazaService.getPlaza(id);
        this.plaza = response.data;
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al obtener la plaza';
        console.error('Error fetching plaza:', error);
      } finally {
        this.loading = false;
      }
    },

    // Crear nueva plaza
    async createPlaza(plazaData: PlazaForm) {
      this.loading = true;
      this.error = null;

      try {
        const response = await plazaService.createPlaza(plazaData);

        // Agregar la nueva plaza a la lista
        this.plazas.unshift(response.data);

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: 'Plaza creada correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

        return response.data;
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al crear la plaza';

        // Mostrar errores de validación
        if (error.response?.status === 422) {
          const errors = error.response.data.errors;
          let errorMessage = 'Errores de validación:\n';

          Object.keys(errors).forEach(field => {
            errorMessage += `• ${errors[field].join(', ')}\n`;
          });

          await Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            text: errorMessage || 'Error de validación',
          });
        } else {
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: this.error || 'Error desconocido',
          });
        }

        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Actualizar plaza
    async updatePlaza(id: number, plazaData: PlazaForm) {
      this.loading = true;
      this.error = null;

      try {
        const response = await plazaService.updatePlaza(id, plazaData);

        // Actualizar la plaza en la lista
        const index = this.plazas.findIndex((p: Plaza) => p.id === id);
        if (index !== -1) {
          this.plazas[index] = response.data;
        }

        // Actualizar la plaza actual si es la misma
        if (this.plaza?.id === id) {
          this.plaza = response.data;
        }

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: 'Plaza actualizada correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

        return response.data;
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al actualizar la plaza';

        // Mostrar errores de validación
        if (error.response?.status === 422) {
          const errors = error.response.data.errors;
          let errorMessage = 'Errores de validación:\n';

          Object.keys(errors).forEach(field => {
            errorMessage += `• ${errors[field].join(', ')}\n`;
          });

          await Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            text: errorMessage || 'Error de validación',
          });
        } else {
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: this.error || 'Error desconocido',
          });
        }

        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Eliminar plaza
    async deletePlaza(id: number) {
      const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
      });

      if (!result.isConfirmed) return;

      this.loading = true;
      this.error = null;

      try {
        await plazaService.deletePlaza(id);

        // Remover la plaza de la lista
        this.plazas = this.plazas.filter((p: Plaza) => p.id !== id);

        // Limpiar la plaza actual si es la misma
        if (this.plaza?.id === id) {
          this.plaza = null;
        }

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Eliminado!',
          text: 'Plaza eliminada correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al eliminar la plaza';

        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: this.error || 'Error desconocido',
        });

        console.error('Error deleting plaza:', error);
      } finally {
        this.loading = false;
      }
    },

    // Cambiar estado activo/inactivo
    async toggleActivo(id: number) {
      this.loading = true;
      this.error = null;

      try {
        const response = await plazaService.toggleActivo(id);

        // Actualizar la plaza en la lista
        const index = this.plazas.findIndex((p: Plaza) => p.id === id);
        if (index !== -1) {
          this.plazas[index] = response.data;
        }

        // Actualizar la plaza actual si es la misma
        if (this.plaza?.id === id) {
          this.plaza = response.data;
        }

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: response.data.activo ? 'Plaza activada correctamente' : 'Plaza desactivada correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al cambiar el estado de la plaza';

        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: this.error || 'Error desconocido',
        });

        console.error('Error toggling plaza status:', error);
      } finally {
        this.loading = false;
      }
    },

    // Obtener plazas para selects
    async getPlazasForSelect(): Promise<PlazaOption[]> {
      try {
        const response = await plazaService.getPlazasForSelect();
        return response.data;
      } catch (error: any) {
        console.error('Error getting plazas for select:', error);
        return [];
      }
    },

    // Limpiar estado
    clearState() {
      this.plazas = [];
      this.plaza = null;
      this.error = null;
      this.pagination = {
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0,
        from: null,
        to: null,
      };
    },

    // Actualizar filtros
    updateFilters(newFilters: Partial<PlazaFilters>) {
      this.filters = { ...this.filters, ...newFilters };
    },

    // Resetear filtros
    resetFilters() {
      this.filters = {
        search: '',
        activo: '',
        sort_by: 'nombre',
        sort_order: 'asc',
        per_page: 15,
      };
    },

    // Buscar plazas por texto
    searchPlazas(searchText: string) {
      this.updateFilters({ search: searchText });
      this.fetchPlazas();
    },

    // Filtrar por estado
    filterByStatus(activo: boolean | '') {
      this.updateFilters({ activo });
      this.fetchPlazas();
    },
  },
});
