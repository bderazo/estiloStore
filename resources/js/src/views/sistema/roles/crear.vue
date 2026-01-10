<template>
    <div>
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            <h2 class="text-xl">Crear Rol</h2>
            <div class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto">
                <router-link to="/administrador/roles" class="btn btn-outline-danger gap-2">
                    ← Volver
                </router-link>
            </div>
        </div>

        <div class="panel">
            <div class="mb-5">
                <form @submit.prevent="submitForm" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nombre del Rol -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre del Rol <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="nombre"
                                v-model="form.nombre"
                                type="text"
                                class="form-input"
                                :class="{ 'border-red-500': errors.nombre }"
                                placeholder="Ej: Administrador, Supervisor, Usuario"
                                maxlength="100"
                                required
                            />
                            <div v-if="errors.nombre" class="mt-1 text-sm text-red-600">
                                {{ errors.nombre[0] }}
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                                Descripción
                            </label>
                            <textarea
                                id="descripcion"
                                v-model="form.descripcion"
                                class="form-textarea"
                                :class="{ 'border-red-500': errors.descripcion }"
                                placeholder="Describe las responsabilidades de este rol"
                                rows="3"
                                maxlength="255"
                            ></textarea>
                            <div v-if="errors.descripcion" class="mt-1 text-sm text-red-600">
                                {{ errors.descripcion[0] }}
                            </div>
                        </div>
                    </div>

                    <!-- Permisos por Módulo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Permisos del Rol <span class="text-red-500">*</span>
                        </label>

                        <div v-if="loadingModules" class="min-h-[100px] flex items-center justify-center">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary"></div>
                        </div>

                        <div v-else class="space-y-4">
                            <div v-for="module in modules" :key="module.id" class="border rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-medium text-gray-900">📁 {{ module.nombre }}</h3>
                                    <button
                                        type="button"
                                        @click="toggleAllModulePermissions(module.id)"
                                        class="text-sm text-primary hover:underline"
                                    >
                                        {{ isModuleFullySelected(module.id) ? 'Deseleccionar todos' : 'Seleccionar todos' }}
                                    </button>
                                </div>

                                <div v-if="module.descripcion" class="text-sm text-gray-600 mb-3">
                                    {{ module.descripcion }}
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                    <div
                                        v-for="permission in getPermissionsByModule(module.id)"
                                        :key="permission.id"
                                        class="flex items-center"
                                    >
                                        <input
                                            :id="`permission_${permission.id}`"
                                            v-model="form.permissions"
                                            :value="permission.id"
                                            type="checkbox"
                                            class="form-checkbox h-4 w-4 text-primary"
                                        />
                                        <label :for="`permission_${permission.id}`" class="ml-2 text-sm text-gray-700">
                                            {{ permission.nombre }}
                                            <div v-if="permission.descripcion" class="text-xs text-gray-500">
                                                {{ permission.descripcion }}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="errors.permissions" class="mt-1 text-sm text-red-600">
                            {{ errors.permissions[0] }}
                        </div>
                        <div v-if="form.permissions.length === 0" class="mt-1 text-sm text-amber-600">
                            Debes seleccionar al menos un permiso para el rol
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4 pt-5 border-t">
                        <router-link to="/roles" class="btn btn-outline-danger">
                            Cancelar
                        </router-link>
                        <button
                            type="submit"
                            :disabled="loading || form.permissions.length === 0"
                            class="btn btn-primary gap-2"
                        >
                            💾 {{ loading ? 'Guardando...' : 'Crear Rol' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { usePermissions } from '../../../composables/usePermissions';
import api from '../../../services/api';
import Swal from 'sweetalert2';
import type { Module, Permission, RoleFormData, ApiResponse } from '../../../types/index';

const router = useRouter();
const { can } = usePermissions();

// Verificar permisos
if (!can('roles.create')) {
    router.push('/roles');
}

// Estado
const loading = ref(false);
const loadingModules = ref(true);
const modules = ref<Module[]>([]);
const permissions = ref<Permission[]>([]);
const errors = ref<Record<string, string[]>>({});

// Formulario
const form = ref<RoleFormData>({
    nombre: '',
    descripcion: '',
    permissions: []
});

// Cargar módulos y permisos
const loadModulesAndPermissions = async () => {
    try {
        const response = await api.get('/modules/all');
        if (response.data.success) {
            modules.value = response.data.data.modules || [];
            permissions.value = response.data.data.permissions || [];

            if (modules.value.length === 0) {
                console.warn('No se encontraron módulos');
            }

            if (permissions.value.length === 0) {
                console.warn('No se encontraron permisos');
            }
        } else {
            throw new Error(response.data.message || 'Error en la respuesta del servidor');
        }
    } catch (error) {
        console.error('Error cargando módulos y permisos:', error);
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: (error as any)?.response?.data?.message || 'Error al cargar los módulos y permisos'
        });
    } finally {
        loadingModules.value = false;
    }
};

// Obtener permisos por módulo
const getPermissionsByModule = (moduleId: number) => {
    const modulePermissions = permissions.value.filter(permission => {
        if (!permission.module_id) {
            return false;
        }
        return permission.module_id === moduleId;
    });
    return modulePermissions;
};

// Verificar si todos los permisos de un módulo están seleccionados
const isModuleFullySelected = (moduleId: number) => {
    const modulePermissions = getPermissionsByModule(moduleId);
    return modulePermissions.every(permission =>
        form.value.permissions.includes(permission.id)
    );
};

// Alternar todos los permisos de un módulo
const toggleAllModulePermissions = (moduleId: number) => {
    const modulePermissions = getPermissionsByModule(moduleId);
    const modulePermissionIds = modulePermissions.map(p => p.id);

    if (isModuleFullySelected(moduleId)) {
        // Deseleccionar todos los permisos del módulo
        form.value.permissions = form.value.permissions.filter(
            permissionId => !modulePermissionIds.includes(permissionId)
        );
    } else {
        // Seleccionar todos los permisos del módulo
        const newPermissions = modulePermissionIds.filter(
            permissionId => !form.value.permissions.includes(permissionId)
        );
        form.value.permissions.push(...newPermissions);
    }
};

// Enviar formulario
const submitForm = async () => {
    if (form.value.permissions.length === 0) {
        await Swal.fire({
            icon: 'warning',
            title: 'Permisos requeridos',
            text: 'Debes seleccionar al menos un permiso para el rol'
        });
        return;
    }

    loading.value = true;
    errors.value = {};

    try {
        const response = await api.post('/roles', form.value) as { data: ApiResponse };

        if (response.data.success) {
            await Swal.fire({
                icon: 'success',
                title: 'Rol creado',
                text: response.data.message,
                timer: 1500,
                showConfirmButton: false
            });

            router.push('/roles');
        } else {
            throw new Error(response.data.message || 'Error al crear el rol');
        }
    } catch (error: any) {
        console.error('Error creando rol:', error);

        if (error.response?.status === 422 && error.response.data?.errors) {
            errors.value = error.response.data.errors;
            await Swal.fire({
                icon: 'error',
                title: 'Errores de validación',
                text: 'Por favor corrige los errores en el formulario'
            });
        } else {
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.response?.data?.message || 'Error al crear el rol'
            });
        }
    } finally {
        loading.value = false;
    }
};

// Inicializar
onMounted(() => {
    loadModulesAndPermissions();
});
</script>

<style scoped>
.form-input:focus, .form-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-checkbox:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.form-textarea {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
