import { defineStore } from 'pinia';
import type {
  Color,
  ColorForm,
  ColorFilters,
  ColorState,
  ColorOption
} from '../types/color';
import { colorService } from '../services/colorService';
import Swal from 'sweetalert2';

export const useColorStore = defineStore('color', {
  state: (): ColorState => ({
    colores: [],
    color: null,
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
    coloresActivos: (state): Color[] => {
      return state.colores.filter((color: Color) => color.activo);
    },

    colorById: (state) => {
      return (id: number): Color | undefined => {
        return state.colores.find((color: Color) => color.id === id);
      };
    },

    hasColores: (state): boolean => {
      return state.colores.length > 0;
    },

    totalPages: (state): number => {
      return state.pagination.last_page;
    },
  },

  actions: {
    // Obtener lista de colores con filtros
    async fetchColores(filters?: Partial<ColorFilters>) {
      this.loading = true;
      this.error = null;

      try {
        // Actualizar filtros si se proporcionan
        if (filters) {
          this.filters = { ...this.filters, ...filters };
        }

        const response = await colorService.getColores(this.filters);

        this.colores = response.data;
        this.pagination = response.pagination;

      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al obtener los colores';
        console.error('Error fetching colores:', error);
      } finally {
        this.loading = false;
      }
    },

    // Obtener color específico
    async fetchColor(id: number) {
      this.loading = true;
      this.error = null;

      try {
        const response = await colorService.getColor(id);
        this.color = response.data;
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al obtener el color';
        console.error('Error fetching color:', error);
      } finally {
        this.loading = false;
      }
    },

    // Crear nuevo color
    async createColor(colorData: ColorForm) {
      this.loading = true;
      this.error = null;

      try {
        const response = await colorService.createColor(colorData);

        // Agregar el nuevo color a la lista
        this.colores.unshift(response.data);

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: 'Color creado correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

        return response.data;
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al crear el color';

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
            text: errorMessage,
          });
        } else {
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: this.error,
          });
        }

        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Actualizar color
    async updateColor(id: number, colorData: ColorForm) {
      this.loading = true;
      this.error = null;

      try {
        const response = await colorService.updateColor(id, colorData);

        // Actualizar el color en la lista
        const index = this.colores.findIndex((c: Color) => c.id === id);
        if (index !== -1) {
          this.colores[index] = response.data;
        }

        // Actualizar el color actual si es el mismo
        if (this.color?.id === id) {
          this.color = response.data;
        }

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: 'Color actualizado correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

        return response.data;
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al actualizar el color';

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
            text: this.error,
          });
        }

        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Eliminar color
    async deleteColor(id: number) {
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
        await colorService.deleteColor(id);

        // Remover el color de la lista
        this.colores = this.colores.filter(c => c.id !== id);

        // Limpiar el color actual si es el mismo
        if (this.color?.id === id) {
          this.color = null;
        }

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Eliminado!',
          text: 'Color eliminado correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al eliminar el color';

        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: this.error,
        });

        console.error('Error deleting color:', error);
      } finally {
        this.loading = false;
      }
    },

    // Cambiar estado activo/inactivo
    async toggleActivo(id: number) {
      this.loading = true;
      this.error = null;

      try {
        const response = await colorService.toggleActivo(id);

        // Actualizar el color en la lista
        const index = this.colores.findIndex(c => c.id === id);
        if (index !== -1) {
          this.colores[index] = response.data;
        }

        // Actualizar el color actual si es el mismo
        if (this.color?.id === id) {
          this.color = response.data;
        }

        // Mostrar mensaje de éxito
        await Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: response.data.activo ? 'Color activado correctamente' : 'Color desactivado correctamente',
          timer: 2000,
          showConfirmButton: false,
        });

      } catch (error: any) {
        this.error = error.response?.data?.message || 'Error al cambiar el estado del color';

        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: this.error,
        });

        console.error('Error toggling color status:', error);
      } finally {
        this.loading = false;
      }
    },

    // Obtener colores para selects
    async getColoresForSelect(): Promise<ColorOption[]> {
      try {
        const response = await colorService.getColoresForSelect();
        return response.data;
      } catch (error: any) {
        console.error('Error getting colores for select:', error);
        return [];
      }
    },

    // Limpiar estado
    clearState() {
      this.colores = [];
      this.color = null;
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
    updateFilters(newFilters: Partial<ColorFilters>) {
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
  },
});
