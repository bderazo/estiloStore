import { defineStore } from 'pinia';
import api from '../services/api';

interface User {
    id: number;
    numero_documento: string;
    nombres: string;
    apellidos: string;
    email: string;
    nombre_completo: string;
    rol: string[];
    permissions: Permission[];
}

interface Permission {
    slug: string;
    nombre: string;
    module: string;
}

interface LoginResponse {
    success: boolean;
    data?: any;
    errors?: Record<string, string[]>;
    message?: string;
}

interface AuthState {
    user: User | null;
    token: string | null;
    isAuthenticated: boolean;
    isLoading: boolean;
    isLoggingOut: boolean;
}

export const useAuthStore = defineStore('auth', {
    state: (): AuthState => ({
        user: null,
        token: localStorage.getItem('token'),
        isAuthenticated: false,
        isLoading: false,
        isLoggingOut: false,
    }),

    getters: {
        currentUser: (state): User | null => state.user,
        userPermissions: (state): string[] => {
            return state.user?.permissions?.map(p => p.slug) || [];
        },
        userRoles: (state): string[] => {
            return state.user?.rol || [];
        },
    },

    actions: {
        async login(credentials: { email: string; password: string }): Promise<LoginResponse> {
            this.isLoading = true;
            try {
                const response = await api.post('/auth/login', credentials);

                // Si llegamos aquí, la respuesta HTTP fue exitosa (200)
                if (response.data.success) {
                    const { token, user } = response.data.data;

                    this.token = token;
                    this.user = user;
                    this.isAuthenticated = true;

                    localStorage.setItem('token', token);
                    localStorage.setItem('user', JSON.stringify(user));

                    return { success: true, data: response.data };
                } else {
                    return {
                        success: false,
                        errors: response.data.errors,
                        message: response.data.message
                    };
                }
            } catch (error: any) {
                // Error HTTP (401, 422, 500, etc.) o error de red
                if (error.response?.data) {
                    // El servidor respondió con un error estructurado
                    return {
                        success: false,
                        errors: error.response.data.errors || { general: ['Error del servidor'] },
                        message: error.response.data.message || 'Error al iniciar sesión'
                    };
                } else {
                    // Error de red o conexión
                    return {
                        success: false,
                        errors: { general: ['Error de conexión. Verifique su conexión a internet.'] },
                        message: 'Error de conexión'
                    };
                }
            } finally {
                this.isLoading = false;
            }
        },

        async logout() {
            try {
                await api.post('/auth/logout');
            } catch (error) {
                console.error('Error al cerrar sesión:', error);
            } finally {
                this.clearAuth();
            }
        },

        async getUser() {
            if (!this.token) return false;

            try {
                const response = await api.get('/auth/me');
                console.log('User response:', response.data); // <-- Agrega esto
                console.log('User permissions:', response.data.data.user.permissions); // <-- Y esto

                if (response.data.success) {
                    this.user = response.data.data.user;
                    this.isAuthenticated = true;
                    localStorage.setItem('user', JSON.stringify(this.user));
                    return true;
                } else {
                    this.clearAuth();
                    return false;
                }
            } catch (error) {
                this.clearAuth();
                return false;
            }
        },  

        async refreshToken() {
            if (!this.token) return false;

            try {
                const response = await api.post('/auth/refresh');

                if (response.data.success) {
                    this.token = response.data.data.token;
                    if (this.token) {
                        localStorage.setItem('token', this.token);
                    }
                    return true;
                }
                return false;
            } catch (error) {
                this.clearAuth();
                return false;
            }
        },

        clearAuth() {
            this.user = null;
            this.token = null;
            this.isAuthenticated = false;
            this.isLoggingOut = false;
            localStorage.removeItem('token');
            localStorage.removeItem('user');
        },

        initAuth() {
            const token = localStorage.getItem('token');
            const user = localStorage.getItem('user');

            if (token && user) {
                this.token = token;
                this.user = JSON.parse(user);
                this.isAuthenticated = true;
                return true;
            }
            return false;
        },

        hasPermission(permission: string): boolean {
            // Para desarrollo, permitir todo
            if (import.meta.env.DEV) {
                console.log(`DEV MODE: Skipping permission check for "${permission}"`);
                return true;
            }
            
            return this.userPermissions.includes(permission);
        },

        hasRole(role: string): boolean {
            return this.userRoles.includes(role);
        },

        hasAnyPermission(permissions: string[]): boolean {
            return permissions.some(permission => this.hasPermission(permission));
        },

        hasAllPermissions(permissions: string[]): boolean {
            return permissions.every(permission => this.hasPermission(permission));
        },

        setLoggingOut(value: boolean): void {
            this.isLoggingOut = value;
        },
    },
});
