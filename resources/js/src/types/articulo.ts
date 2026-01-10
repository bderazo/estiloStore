export interface Articulo {
    id: number;
    nombre: string;
    slug: string;
    descripcion: string;
    especificaciones: string;
    precio: number;
    sku: string;
    categoria_id: number;
    marca_id: number;
    activo: boolean;
    destacado: boolean;
    created_at: string;
    updated_at: string;
    categoria?: { id: number; nombre: string };
    marca?: { id: number; nombre: string };
    imagenes?: ArticuloImagen[];
    variantes?: ArticuloVariante[];
    stock_disponible?: number;
}

export interface ArticuloImagen {
    id: number;
    articulo_id: number;
    url: string;
    alt_text?: string;
    orden: number;
    created_at: string;
    updated_at: string;
}

export interface ArticuloVariante {
    id: number;
    articulo_id: number;
    atributos: Record<string, any>;
    stock: number;
    activo: boolean;
    created_at: string;
    updated_at: string;
}

export interface ArticuloForm {
    nombre: string;
    slug: string;
    descripcion: string;
    especificaciones: string;
    precio: number;
    sku: string;
    categoria_id: number;
    marca_id: number;
    activo: boolean;
    destacado: boolean;
    imagenes: File[];
    imagenes_eliminar: number[];
    imagenes_orden: number[];
    variantes: ArticuloVarianteForm[];
}

export interface ArticuloVarianteForm {
    id?: number;
    atributos: Record<string, any>;
    stock: number;
    activo?: boolean;
}

export interface ArticuloFilters {
    search: string;
    categoria_id: string;
    marca_id: string;
    activo: string;
    current_page: number;
    per_page: number;
}

export interface ArticuloPagination {
    current_page: number;
    total: number;
    per_page: number;
    last_page: number;
    from: number;
    to: number;
}

export interface ArticuloSelectItem {
    id: number;
    nombre: string;
    codigo: string;
}

export interface ArticuloResponse {
    data: Articulo;
    success?: boolean;
    message?: string;
}

export interface ArticuloListResponse {
    data: Articulo[];
    pagination: ArticuloPagination;
}

export interface StockMovement {
    articulo_variante_id: number;
    tipo: 'reserva' | 'liberacion' | 'decremento';
    cantidad: number;
}
