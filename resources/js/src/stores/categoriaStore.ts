import { defineStore } from "pinia";
import {
    Categoria,
    CategoriaFilters,
    CreateCategoriaRequest,
    UpdateCategoriaRequest,
    CategoriaSelectOption,
    ReorderCategoryItem,
    CategoriaState,
} from "../types/categoria";
import categoriaService from "../services/categoriaService";
import { isApiCallSafe } from "../utils/authGuard";
export const useCategoriaStore = defineStore("categoria", {
    state: (): CategoriaState => ({
        categorias: [],
        categoria: null,
        parentOptions: [],
        loading: false,
        filters: {
            search: "",
            activo: null,
            parent_id: null,
            sort_by: "orden",
            sort_order: "asc",
            per_page: 15,
            page: 1,
            hierarchical: false,
        },
        pagination: null,
    }),

    getters: {
        // Obtener categorías principales (sin parent_id)
        categoriasprincipales: (state): Categoria[] => {
            return state.categorias.filter(
                (categoria) => categoria.parent_id === null,
            );
        },

        // Obtener subcategorías de una categoría padre
        getSubcategorias:
            (state) =>
            (parentId: number): Categoria[] => {
                return state.categorias.filter(
                    (categoria) => categoria.parent_id === parentId,
                );
            },

        // Verificar si una categoría tiene hijos
        hasChildren:
            (state) =>
            (categoriaId: number): boolean => {
                return state.categorias.some(
                    (categoria) => categoria.parent_id === categoriaId,
                );
            },

        // Obtener categorías activas
        categoriasActivas: (state): Categoria[] => {
            return state.categorias.filter((categoria) => categoria.activo);
        },

        // Obtener categorías por nivel
        getCategoriasByLevel:
            (state) =>
            (level: number): Categoria[] => {
                return state.categorias.filter(
                    (categoria) => categoria.level === level,
                );
            },

        // Contar total de categorías
        totalCategorias: (state): number => {
            return state.categorias.length;
        },

        // Contar categorías activas
        totalCategoriasActivas: (state): number => {
            return state.categorias.filter((categoria) => categoria.activo)
                .length;
        },
    },

    actions: {
        // Obtener lista de categorías
        async fetchCategorias(filters?: Partial<CategoriaFilters>) {
            // Verificar si es seguro hacer la llamada API
            if (!isApiCallSafe()) {
                return;
            }

            this.loading = true;
            try {
                if (filters) {
                    this.filters = { ...this.filters, ...filters };
                }

                const response = await categoriaService.getAll(this.filters);
                this.categorias = response.data;

                if (response.pagination) {
                    this.pagination = response.pagination;
                }
            } catch (error) {
                console.error("Error al obtener categorías:", error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Obtener una categoría específica
        async fetchCategoria(id: number) {
            this.loading = true;
            try {
                const response = await categoriaService.getById(id);
                this.categoria = response.data;
                return response.data;
            } catch (error) {
                console.error("Error al obtener categoría:", error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Crear nueva categoría
        async createCategoria(
            data: CreateCategoriaRequest | FormData,
        ): Promise<Categoria> {
            this.loading = true;
            try {
                const response = await categoriaService.create(data);

                // Agregar la nueva categoría a la lista
                if (response.data) {
                    this.categorias.push(response.data);
                }

                return response.data!;
            } catch (error) {
                console.error("Error al crear categoría:", error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateCategoria(
            id: number,
            data: UpdateCategoriaRequest | FormData,
        ): Promise<Categoria> {
            this.loading = true;
            try {
                const response = await categoriaService.update(id, data);

                // Actualizar en la lista
                const index = this.categorias.findIndex((c) => c.id === id);
                if (index !== -1) {
                    this.categorias[index] = response.data!;
                }

                return response.data!;
            } catch (error) {
                console.error("Error al actualizar categoría:", error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Eliminar categoría
        async deleteCategoria(id: number) {
            this.loading = true;
            try {
                await categoriaService.delete(id);

                // Remover de la lista
                this.categorias = this.categorias.filter((c) => c.id !== id);

                // Limpiar categoría actual si es la misma
                if (this.categoria?.id === id) {
                    this.categoria = null;
                }
            } catch (error) {
                console.error("Error al eliminar categoría:", error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Cambiar estado activo
        async toggleActivo(id: number): Promise<Categoria> {
            this.loading = true;
            try {
                const response = await categoriaService.toggleActivo(id);

                // Actualizar en la lista
                const index = this.categorias.findIndex((c) => c.id === id);
                if (index !== -1 && response.data) {
                    this.categorias[index] = response.data;
                }

                // Actualizar categoría actual si es la misma
                if (this.categoria?.id === id) {
                    this.categoria = response.data;
                }

                return response.data!;
            } catch (error) {
                console.error("Error al cambiar estado de categoría:", error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Obtener opciones para select de categorías padre
        async fetchParentOptions(excludeId?: number) {
            // Verificar si es seguro hacer la llamada API
            if (!isApiCallSafe()) {
                return;
            }

            try {
                const response = await categoriaService.getForSelect(excludeId);
                this.parentOptions = response.data;
            } catch (error) {
                console.error(
                    "Error al obtener opciones de categorías padre:",
                    error,
                );
                throw error;
            }
        },

        // Reordenar categorías
        async reorderCategorias(categories: ReorderCategoryItem[]) {
            this.loading = true;
            try {
                await categoriaService.reorder({ categories });

                // Actualizar el orden en el estado local
                categories.forEach(({ id, orden }) => {
                    const categoria = this.categorias.find((c) => c.id === id);
                    if (categoria) {
                        categoria.orden = orden;
                    }
                });

                // Reordenar la lista local
                this.categorias.sort((a, b) => a.orden - b.orden);
            } catch (error) {
                console.error("Error al reordenar categorías:", error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Establecer filtros
        setFilters(filters: Partial<CategoriaFilters>) {
            this.filters = { ...this.filters, ...filters };
        },

        // Limpiar filtros
        clearFilters() {
            this.filters = {
                search: "",
                activo: null,
                parent_id: null,
                sort_by: "orden",
                sort_order: "asc",
                per_page: 15,
                page: 1,
                hierarchical: false,
            };
        },

        // Limpiar categoría actual
        clearCategoria() {
            this.categoria = null;
        },

        // Limpiar todo el estado
        clearAll() {
            this.categorias = [];
            this.categoria = null;
            this.parentOptions = [];
            this.pagination = null;
            this.clearFilters();
        },

        // Buscar categorías por texto
        searchCategorias(searchTerm: string) {
            this.setFilters({ search: searchTerm, page: 1 });
            return this.fetchCategorias();
        },

        // Filtrar por estado activo
        filterByActivo(activo: boolean | null) {
            this.setFilters({ activo, page: 1 });
            return this.fetchCategorias();
        },

        // Filtrar por categoría padre
        filterByParent(parentId: number | null) {
            this.setFilters({ parent_id: parentId, page: 1 });
            return this.fetchCategorias();
        },

        // Cambiar página
        changePage(page: number) {
            this.setFilters({ page });
            return this.fetchCategorias();
        },

        // Cambiar elementos por página
        changePerPage(perPage: number) {
            this.setFilters({ per_page: perPage, page: 1 });
            return this.fetchCategorias();
        },

        // Cambiar ordenamiento
        changeSorting(sortBy: string, sortOrder: "asc" | "desc") {
            this.setFilters({
                sort_by: sortBy as any,
                sort_order: sortOrder,
                page: 1,
            });
            return this.fetchCategorias();
        },

        // Obtener categorías en estructura jerárquica
        async fetchHierarchicalCategorias() {
            return this.fetchCategorias({ hierarchical: true });
        },
    },
});
