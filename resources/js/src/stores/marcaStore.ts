import { defineStore } from 'pinia';
import {
    Marca,
    MarcaFilters,
    CreateMarcaRequest,
    UpdateMarcaRequest,
    MarcaSelectOption,
    MarcaState
} from '../types/marca';
import marcaService from '../services/marcaService';

export const useMarcaStore = defineStore('marca', {
    state: (): MarcaState => ({
        marcas: [],
        marca: null,
        selectOptions: [],
        loading: false,
        filters: {
            search: '',
            activo: null,
            sort_by: 'nombre',
            sort_order: 'asc',
            per_page: 15,
            page: 1
        } as MarcaFilters,
        pagination: null
    }),

    getters: {
        // Obtener marcas activas
        marcasActivas: (state): Marca[] => {
            return state.marcas.filter(marca => marca.activo);
        },

        // Obtener marcas inactivas
        marcasInactivas: (state): Marca[] => {
            return state.marcas.filter(marca => !marca.activo);
        },

        // Contar total de marcas
        totalMarcas: (state): number => {
            return state.marcas.length;
        },

        // Contar marcas activas
        totalMarcasActivas: (state): number => {
            return state.marcas.filter(marca => marca.activo).length;
        },

        // Contar marcas inactivas
        totalMarcasInactivas: (state): number => {
            return state.marcas.filter(marca => !marca.activo).length;
        },

        // Obtener marca por ID
        getMarcaById: (state) => (id: number): Marca | undefined => {
            return state.marcas.find(marca => marca.id === id);
        },

        // Buscar marcas por nombre
        getMarcasByName: (state) => (searchTerm: string): Marca[] => {
            const term = searchTerm.toLowerCase();
            return state.marcas.filter(marca =>
                marca.nombre.toLowerCase().includes(term) ||
                marca.slug.toLowerCase().includes(term)
            );
        },

        // Verificar si existe una marca con el mismo nombre
        existeMarcaConNombre: (state) => (nombre: string, excludeId?: number): boolean => {
            return state.marcas.some(marca =>
                marca.nombre.toLowerCase() === nombre.toLowerCase() &&
                marca.id !== excludeId
            );
        }
    },

    actions: {
        // Obtener lista de marcas
        async fetchMarcas(filters?: Partial<MarcaFilters>) {
            this.loading = true;
            try {
                if (filters) {
                    this.filters = { ...this.filters, ...filters };
                }

                const response = await marcaService.getAll(this.filters);
                this.marcas = response.data;

                if (response.pagination) {
                    this.pagination = response.pagination;
                }
            } catch (error) {
                console.error('Error al obtener marcas:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Obtener una marca específica
        async fetchMarca(id: number) {
            this.loading = true;
            try {
                const response = await marcaService.getById(id);
                this.marca = response.data;
                return response.data;
            } catch (error) {
                console.error('Error al obtener marca:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Crear nueva marca
        async createMarca(data: CreateMarcaRequest): Promise<Marca> {
            this.loading = true;
            try {
                const response = await marcaService.create(data);

                // Agregar la nueva marca a la lista
                this.marcas.push(response.data);

                return response.data;
            } catch (error) {
                console.error('Error al crear marca:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Actualizar marca
        async updateMarca(id: number, data: UpdateMarcaRequest): Promise<Marca> {
            this.loading = true;
            try {
                const response = await marcaService.update(id, data);

                // Actualizar en la lista
                const index = this.marcas.findIndex(m => m.id === id);
                if (index !== -1) {
                    this.marcas[index] = response.data;
                }

                // Actualizar marca actual si es la misma
                if (this.marca?.id === id) {
                    this.marca = response.data;
                }

                return response.data;
            } catch (error) {
                console.error('Error al actualizar marca:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Eliminar marca
        async deleteMarca(id: number) {
            this.loading = true;
            try {
                await marcaService.delete(id);

                // Remover de la lista
                this.marcas = this.marcas.filter(m => m.id !== id);

                // Limpiar marca actual si es la misma
                if (this.marca?.id === id) {
                    this.marca = null;
                }
            } catch (error) {
                console.error('Error al eliminar marca:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Cambiar estado activo
        async toggleActivo(id: number): Promise<Marca> {
            this.loading = true;
            try {
                const response = await marcaService.toggleActivo(id);

                // Actualizar en la lista
                const index = this.marcas.findIndex(m => m.id === id);
                if (index !== -1) {
                    this.marcas[index] = response.data;
                }

                // Actualizar marca actual si es la misma
                if (this.marca?.id === id) {
                    this.marca = response.data;
                }

                return response.data;
            } catch (error) {
                console.error('Error al cambiar estado de marca:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Obtener opciones para select
        async fetchSelectOptions() {
            try {
                const response = await marcaService.getForSelect();
                this.selectOptions = response.data;
            } catch (error) {
                console.error('Error al obtener opciones de marcas:', error);
                throw error;
            }
        },

        // Establecer filtros
        setFilters(filters: Partial<MarcaFilters>) {
            this.filters = { ...this.filters, ...filters };
        },

        // Limpiar filtros
        clearFilters() {
            this.filters = {
                search: '',
                activo: null,
                sort_by: 'nombre',
                sort_order: 'asc',
                per_page: 15,
                page: 1
            };
        },

        // Limpiar marca actual
        clearMarca() {
            this.marca = null;
        },

        // Limpiar todo el estado
        clearAll() {
            this.marcas = [];
            this.marca = null;
            this.selectOptions = [];
            this.pagination = null;
            this.clearFilters();
        },

        // Buscar marcas por texto
        searchMarcas(searchTerm: string) {
            this.setFilters({ search: searchTerm, page: 1 });
            return this.fetchMarcas();
        },

        // Filtrar por estado activo
        filterByActivo(activo: boolean | null) {
            this.setFilters({ activo, page: 1 });
            return this.fetchMarcas();
        },

        // Cambiar página
        changePage(page: number) {
            this.setFilters({ page });
            return this.fetchMarcas();
        },

        // Cambiar elementos por página
        changePerPage(perPage: number) {
            this.setFilters({ per_page: perPage, page: 1 });
            return this.fetchMarcas();
        },

        // Cambiar ordenamiento
        changeSorting(sortBy: string, sortOrder: 'asc' | 'desc') {
            this.setFilters({
                sort_by: sortBy as any,
                sort_order: sortOrder,
                page: 1
            });
            return this.fetchMarcas();
        },

        // Obtener marcas activas
        async fetchMarcasActivas() {
            return this.fetchMarcas({ activo: true });
        },

        // Obtener marcas inactivas
        async fetchMarcasInactivas() {
            return this.fetchMarcas({ activo: false });
        },

        // Refrescar datos
        async refresh() {
            await this.fetchMarcas();
            await this.fetchSelectOptions();
        }
    }
});
