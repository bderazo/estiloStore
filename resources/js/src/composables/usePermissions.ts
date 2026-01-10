import { useAuthStore } from '../stores/auth';

export function usePermissions() {
    const authStore = useAuthStore();

    const can = (permission: string): boolean => {
        return authStore.hasPermission(permission);
    };

    const canAny = (permissions: string[]): boolean => {
        return authStore.hasAnyPermission(permissions);
    };

    const canAll = (permissions: string[]): boolean => {
        return authStore.hasAllPermissions(permissions);
    };

    const hasRole = (role: string): boolean => {
        return authStore.hasRole(role);
    };

    return {
        can,
        canAny,
        canAll,
        hasRole,
    };
}