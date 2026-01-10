import { defineStore } from 'pinia';
import type {
  Talla,
  TallaForm,
  TallaFilters,
  TallaState,
  TallaOption
} from '../types/talla';
import { tallaService } from '../services/tallaService';
import Swal from 'sweetalert2';

export const useTallaStore = defineStore('talla', {
  state: (): TallaState => ({
    tallas: [],
    talla: null,
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
    tallasActivas: (state): Talla[] => {
      return state.tallas.filter((talla: Talla) => talla.activo);
    },

    tallaById: (state) => {
      return (id: number): Talla | undefined => {
        return state.tallas.find((talla: Talla) => talla.id === id);
      };
    },

    hasTallas: (state): boolean => {
      return state.tallas.length > 0;
    },

    totalPages: (state): number => {
      return state.pagination.last_page;
    },

    tallasConDescripcion: (state): Talla[] => {
      return state.tallas.filter((talla: Talla) =>
        talla.descripcion && talla.descripcion.trim().length > 0
      );
    },

    tallasSinDescripcion: (state): Talla[] => {
      return state.tallas.filter((talla: Talla) =>
        !talla.descripcion || talla.descripcion.trim().length === 0
      );
    },

    estadisticas: (state) => {
      return {
        total: state.tallas.length,
        activas: state.tallas.filter(t => t.activo).length,
        inactivas: state.tallas.filter(t => !t.activo).length,
        conDescripcion: state.tallas.filter(t => t.descripcion && t.descripcion.trim().length > 0).length,
        sinDescripcion: state.tallas.filter(t => !t.descripcion || t.descripcion.trim().length === 0).length
      };
    },
  },

  actions: {
    // Obtener lista de tallas con filtros
    async fetchTallas(filters?: Partial<TallaFilters>) {
      this.loading = true;
      this.error = null;

      try {
        // Actualizar filtros si se proporcionan
        if (filters) {
          this.filters = { ...this.filters, ...filters };
        }

        const response = await tallaService.getTallas(this.filters);

        this.tallas = response.data;
        this.pagination = response.pagination;

      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al obtener las tallas';
        console.error('Error fetching tallas:', error);
      } finally {
        this.loading = false;
      }
    },

    // Obtener talla específica
    async fetchTalla(id: number) {
      this.loading = true;
      this.error = null;

      try {
        const response = await tallaService.getTalla(id);
        this.talla = response.data;
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al obtener la talla';
        console.error('Error fetching talla:', error);
      } finally {
        this.loading = false;
      }
    },

    // Crear nueva talla
    async createTalla(tallaData: TallaForm) {
      this.loading = true;
      this.error = null;

      try {
        const response = await tallaService.createTalla(tallaData);

        // Agregar la nueva talla a la lista
        this.tallas.unshift(response.data);

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: 'Talla creada correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

        return response.data;
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al crear la talla';

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

    // Actualizar talla
    async updateTalla(id: number, tallaData: TallaForm) {
      this.loading = true;
      this.error = null;

      try {
        const response = await tallaService.updateTalla(id, tallaData);

        // Actualizar la talla en la lista
        const index = this.tallas.findIndex((t: Talla) => t.id === id);
        if (index !== -1) {
          this.tallas[index] = response.data;
        }

        // Actualizar la talla actual si es la misma
        if (this.talla?.id === id) {
          this.talla = response.data;
        }

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: 'Talla actualizada correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

        return response.data;
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al actualizar la talla';

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

    // Eliminar talla
    async deleteTalla(id: number) {
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
        await tallaService.deleteTalla(id);

        // Remover la talla de la lista
        this.tallas = this.tallas.filter((t: Talla) => t.id !== id);

        // Limpiar la talla actual si es la misma
        if (this.talla?.id === id) {
          this.talla = null;
        }

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Eliminado!',
          text: 'Talla eliminada correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al eliminar la talla';

        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: this.error || 'Error desconocido',
        });

        console.error('Error deleting talla:', error);
      } finally {
        this.loading = false;
      }
    },

    // Cambiar estado activo/inactivo
    async toggleActivo(id: number) {
      this.loading = true;
      this.error = null;

      try {
        const response = await tallaService.toggleActivo(id);

        // Actualizar la talla en la lista
        const index = this.tallas.findIndex((t: Talla) => t.id === id);
        if (index !== -1) {
          this.tallas[index] = response.data;
        }

        // Actualizar la talla actual si es la misma
        if (this.talla?.id === id) {
          this.talla = response.data;
        }

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: response.data.activo ? 'Talla activada correctamente' : 'Talla desactivada correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al cambiar el estado de la talla';

        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: this.error || 'Error desconocido',
        });

        console.error('Error toggling talla status:', error);
      } finally {
        this.loading = false;
      }
    },

    // Obtener tallas para selects
    async getTallasForSelect(): Promise<TallaOption[]> {
      try {
        const response = await tallaService.getTallasForSelect();
        return response.data;
      } catch (error: any) {
        console.error('Error getting tallas for select:', error);
        return [];
      }
    },

    // Limpiar estado
    clearState() {
      this.tallas = [];
      this.talla = null;
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
    updateFilters(newFilters: Partial<TallaFilters>) {
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

    // Buscar tallas por texto
    searchTallas(searchText: string) {
      this.updateFilters({ search: searchText });
      this.fetchTallas();
    },

    // Filtrar por estado
    filterByStatus(activo: boolean | '') {
      this.updateFilters({ activo });
      this.fetchTallas();
    },
  },
});
