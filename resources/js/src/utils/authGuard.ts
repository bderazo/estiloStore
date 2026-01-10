import { useAuthStore } from '../stores/auth';

/**
 * Verifica si es seguro realizar llamadas a la API
 * @returns true si es seguro hacer la llamada, false si debe ser bloqueada
 */
export function isApiCallSafe(): boolean {
    const authStore = useAuthStore();

    // Bloquear si está en proceso de logout o no está autenticado
    if (authStore.isLoggingOut || !authStore.isAuthenticated) {
        return false;
    }

    return true;
}

/**
 * Wrapper para funciones que hacen llamadas a la API
 * Solo ejecuta la función si es seguro hacer la llamada
 */
export async function safeApiCall<T>(apiFunction: () => Promise<T>): Promise<T | undefined> {
    if (!isApiCallSafe()) {
        console.log('API call blocked: user is logging out or not authenticated');
        return undefined;
    }

    try {
        return await apiFunction();
    } catch (error) {
        // Si el error es de autenticación, podría ser que el token expiró
        if (isAuthError(error)) {
            const authStore = useAuthStore();
            authStore.clearAuth();
        }
        throw error;
    }
}

/**
 * Verifica si un error es relacionado con autenticación
 */
function isAuthError(error: any): boolean {
    return error?.response?.status === 401 ||
           error?.response?.status === 403 ||
           error?.code === 'UNAUTHORIZED';
}
