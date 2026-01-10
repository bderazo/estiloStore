// Interfaz principal para Categoria
export interface Categoria {
    id: number;
    nombre: string;
    descripcion: string | null;
    slug: string;
    parent_id: number | null;
    activo: boolean;
    orden: number;
    created_at: string;
    updated_at: string;

    // Relaciones
    parent: Categoria | null;
    children: Categoria[];

    // Atributos computados
    level: number;
    path: string;
    has_children: boolean;
    children_count: number;
}

// Interfaz para crear categoría
export interface CreateCategoriaRequest {
    nombre: string;
    descripcion?: string;
    parent_id?: number | null;
    activo?: boolean;
    orden?: number;
}

// Interfaz para actualizar categoría
export interface UpdateCategoriaRequest {
    nombre?: string;
    descripcion?: string;
    parent_id?: number | null;
    activo?: boolean;
    orden?: number;
}

// Interfaz para filtros en el listado
export interface CategoriaFilters {
    search?: string;
    activo?: boolean | null;
    parent_id?: number | null | 'null';
    sort_by?: 'nombre' | 'orden' | 'created_at' | 'updated_at';
    sort_order?: 'asc' | 'desc';
    per_page?: number;
    page?: number;
    hierarchical?: boolean;
}

// Interfaz para opciones de select
export interface CategoriaSelectOption {
    id: number;
    nombre: string;
    value: number;
    label: string;
}

// Interfaz para respuesta de API
export interface CategoriaResponse {
    success: boolean;
    message: string;
    data: Categoria | null;
    errors: string | null;
}

// Interfaz para listado con paginación
export interface CategoriaListResponse {
    success: boolean;
    message: string;
    data: Categoria[];
    pagination?: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number | null;
        to: number | null;
    };
    errors: string | null;
}

// Interfaz para opciones de select
export interface CategoriaSelectResponse {
    success: boolean;
    message: string;
    data: CategoriaSelectOption[];
    errors: string | null;
}

// Interfaz para reordenar categorías
export interface ReorderCategoryItem {
    id: number;
    orden: number;
}

export interface ReorderCategoriesRequest {
    categories: ReorderCategoryItem[];
}

// Interfaz para estadísticas o resumen de categorías
export interface CategoriaStats {
    total: number;
    activas: number;
    inactivas: number;
    principales: number; // Sin parent_id
    subcategorias: number; // Con parent_id
}

// Interfaz para árbol jerárquico
export interface CategoriaTreeNode extends Categoria {
    expanded?: boolean;
    selected?: boolean;
    indeterminate?: boolean;
}

// Props para componentes
export interface CategoriaTableProps {
    categorias: Categoria[];
    loading?: boolean;
    hierarchical?: boolean;
    showActions?: boolean;
    allowReorder?: boolean;
}

export interface CategoriaFormProps {
    categoria?: Categoria | null;
    parentOptions?: CategoriaSelectOption[];
    loading?: boolean;
    mode: 'create' | 'edit';
}

export interface CategoriaSelectProps {
    modelValue?: number | null;
    placeholder?: string;
    disabled?: boolean;
    excludeId?: number;
    showOnlyParents?: boolean;
    clearable?: boolean;
}

// Estados del store
export interface CategoriaState {
    categorias: Categoria[];
    categoria: Categoria | null;
    parentOptions: CategoriaSelectOption[];
    loading: boolean;
    filters: CategoriaFilters;
    pagination: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    } | null;
}

// Acciones del store
export interface CategoriaActions {
    fetchCategorias: (filters?: CategoriaFilters) => Promise<void>;
    fetchCategoria: (id: number) => Promise<void>;
    createCategoria: (data: CreateCategoriaRequest) => Promise<Categoria>;
    updateCategoria: (id: number, data: UpdateCategoriaRequest) => Promise<Categoria>;
    deleteCategoria: (id: number) => Promise<void>;
    toggleActivo: (id: number) => Promise<Categoria>;
    fetchParentOptions: (excludeId?: number) => Promise<void>;
    reorderCategorias: (categories: ReorderCategoryItem[]) => Promise<void>;
    setFilters: (filters: Partial<CategoriaFilters>) => void;
    clearCategoria: () => void;
}
