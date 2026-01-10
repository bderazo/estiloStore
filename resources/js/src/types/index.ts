// Tipos para el sistema de usuarios

export interface User {
    id: number;
    numero_documento: string;
    nombres: string;
    apellidos: string;
    email?: string;
    nombre_completo: string;
    role?: Role; // Cambio: Un usuario tiene un rol
    role_id?: number; // Agregar role_id
    created_at: string;
    updated_at: string;
}

// Exportar tipos de carrusel
export * from './carrusel'

export interface Role {
    id: number;
    nombre: string;
    descripcion?: string;
    permissions: Permission[];
    users?: User[];
    created_at: string;
    updated_at: string;
}

export interface Permission {
    id: number;
    slug: string;
    nombre: string;
    descripcion?: string;
    module_id: number;
    created_at: string;
    updated_at: string;
}

export interface Module {
    id: number;
    nombre: string;
    descripcion?: string;
    created_at: string;
    updated_at: string;
}

export interface PaginationData {
    current_page: number;
    per_page: number;
    last_page: number;
    total: number;
    from: number;
    to: number;
}

export interface ApiResponse<T = any> {
    success: boolean;
    message: string;
    data: T;
    errors?: Record<string, string[]>;
}

export interface PaginatedResponse<T> extends ApiResponse {
    data: {
        data: T[];
        pagination: PaginationData;
    };
}

export interface AuthUser {
    id: number;
    numero_documento: string;
    nombres: string;
    apellidos: string;
    email?: string;
    nombre_completo: string;
    roles: string[];
    permissions: {
        slug: string;
        nombre: string;
        module: string;
    }[];
}

export interface LoginCredentials {
    numero_documento: string;
    password: string;
}

export interface AuthResponse extends ApiResponse {
    data: {
        token: string;
        token_type: string;
        expires_in: number;
        user: AuthUser;
    };
}

// Tipos para formularios
export interface UserFormData {
    numero_documento: string;
    nombres: string;
    apellidos: string;
    email?: string;
    password?: string;
    password_confirmation?: string;
    roles: number[];
}

export interface RoleFormData {
    nombre: string;
    descripcion?: string;
    permissions: number[];
}
