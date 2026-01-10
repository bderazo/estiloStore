<template>
    <div>
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            <h2 class="text-xl">Editar Usuario</h2>
            <div class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto">
                <router-link to="/administrador/usuarios" class="btn btn-outline-danger gap-2">
                    ← Volver
                </router-link>
            </div>
        </div>

        <div v-if="loading" class="panel">
            <div class="min-h-[200px] flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            </div>
        </div>

        <div v-else-if="user" class="panel">
            <div class="mb-5">
                <form @submit.prevent="submitForm" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Número de Documento -->
                        <div>
                            <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-1">
                                Número de Documento <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="numero_documento"
                                v-model="form.numero_documento"
                                type="text"
                                class="form-input"
                                :class="{ 'border-red-500': errors.numero_documento }"
                                placeholder="Ingresa el número de documento"
                                maxlength="20"
                                required
                            />
                            <div v-if="errors.numero_documento" class="mt-1 text-sm text-red-600">
                                {{ errors.numero_documento[0] }}
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Correo Electrónico
                            </label>
                            <input
                                autocomplete="off"
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="form-input"
                                :class="{ 'border-red-500': errors.email }"
                                placeholder="usuario@ejemplo.com"
                            />
                            <div v-if="errors.email" class="mt-1 text-sm text-red-600">
                                {{ errors.email[0] }}
                            </div>
                        </div>

                        <!-- Nombres -->
                        <div>
                            <label for="nombres" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombres <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="nombres"
                                v-model="form.nombres"
                                type="text"
                                class="form-input"
                                :class="{ 'border-red-500': errors.nombres }"
                                placeholder="Nombres del usuario"
                                maxlength="100"
                                required
                            />
                            <div v-if="errors.nombres" class="mt-1 text-sm text-red-600">
                                {{ errors.nombres[0] }}
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">
                                Apellidos <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="apellidos"
                                v-model="form.apellidos"
                                type="text"
                                class="form-input"
                                :class="{ 'border-red-500': errors.apellidos }"
                                placeholder="Apellidos del usuario"
                                maxlength="100"
                                required
                            />
                            <div v-if="errors.apellidos" class="mt-1 text-sm text-red-600">
                                {{ errors.apellidos[0] }}
                            </div>
                        </div>

                        <!-- Cambiar contraseña (opcional) -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Nueva Contraseña <span class="text-gray-500">(opcional)</span>
                            </label>
                            <input
                                id="password"
                                autocomplete="new-password"
                                v-model="form.password"
                                type="password"
                                class="form-input"
                                :class="{ 'border-red-500': errors.password }"
                                placeholder="Dejar vacío para mantener la actual"
                                minlength="8"
                            />
                            <div v-if="errors.password" class="mt-1 text-sm text-red-600">
                                {{ errors.password[0] }}
                            </div>
                        </div>

                        <!-- Confirmar nueva contraseña -->
                        <div v-if="form.password">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirmar Nueva Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                 autocomplete="new-password"
                                class="form-input"
                                :class="{ 'border-red-500': errors.password_confirmation }"
                                placeholder="Confirma la nueva contraseña"
                                minlength="8"
                            />
                            <div v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">
                                {{ errors.password_confirmation[0] }}
                            </div>
                        </div>
                    </div>

                    <!-- Roles -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Roles del Usuario <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="selectedRoleId"
                            class="form-select"
                            :class="{ 'border-red-500': errors.roles }"
                            required
                        >
                            <option value="">Selecciona un rol</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">
                                {{ role.nombre }} - {{ role.descripcion }}
                            </option>
                        </select>
                        <div v-if="errors.roles" class="mt-1 text-sm text-red-600">
                            {{ errors.roles[0] }}
                        </div>
                        <div v-if="!selectedRoleId" class="mt-1 text-sm text-amber-600">
                            Debes seleccionar un rol para el usuario
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4 pt-5 border-t">
                        <router-link to="/usuarios" class="btn btn-outline-danger">
                            Cancelar
                        </router-link>
                        <button
                            type="submit"
                            :disabled="submitting || !selectedRoleId"
                            class="btn btn-primary gap-2"
                        >
                            💾 {{ submitting ? 'Guardando...' : 'Actualizar Usuario' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div v-else class="panel">
            <div class="min-h-[200px] flex items-center justify-center">
                <div class="text-center">
                    <div class="text-gray-500 mb-2">❌</div>
                    <p class="text-gray-600">Usuario no encontrado</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { usePermissions } from '../../../composables/usePermissions';
import api from '../../../services/api';
import Swal from 'sweetalert2';
import type { User, Role, UserFormData, ApiResponse } from '../../../types/index';

const router = useRouter();
const route = useRoute();
const { can } = usePermissions();

// Verificar permisos
if (!can('usuarios.edit')) {
    router.push('/usuarios');
}

// Estado
const loading = ref(true);
const submitting = ref(false);
const user = ref<User | null>(null);
const roles = ref<Role[]>([]);
const errors = ref<Record<string, string[]>>({});
const selectedRoleId = ref<string>('');

// Formulario
const form = ref<UserFormData>({
    numero_documento: '',
    nombres: '',
    apellidos: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: []
});

// ID del usuario desde la ruta
const userId = computed(() => route.params.id as string);

// Cargar datos del usuario
const loadUser = async () => {
    try {
        const response = await api.get(`/users/${userId.value}`);
        if (response.data.success) {
            user.value = response.data.data.user;

            // Llenar el formulario con los datos del usuario
            if (user.value) {
                form.value = {
                    numero_documento: user.value.numero_documento,
                    nombres: user.value.nombres,
                    apellidos: user.value.apellidos,
                    email: user.value.email || '',
                    password: '',
                    password_confirmation: '',
                    roles: user.value.role ? [user.value.role.id] : []
                };

                // Asignar el rol seleccionado
                selectedRoleId.value = user.value.role ? user.value.role.id.toString() : '';
            }
        } else {
            throw new Error('Usuario no encontrado');
        }
    } catch (error) {
        console.error('Error cargando usuario:', error);
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Usuario no encontrado'
        });
        router.push('/usuarios');
    }
};

// Cargar roles disponibles
const loadRoles = async () => {
    try {
        const response = await api.get('/roles/all/list');
        if (response.data.success) {
            roles.value = response.data.data;
        }
    } catch (error) {
        console.error('Error cargando roles:', error);
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al cargar los roles disponibles'
        });
    }
};

// Enviar formulario
const submitForm = async () => {
    if (!selectedRoleId.value) {
        await Swal.fire({
            icon: 'warning',
            title: 'Rol requerido',
            text: 'Debes seleccionar un rol para el usuario'
        });
        return;
    }

    // Asignar el rol seleccionado al formulario
    form.value.roles = [parseInt(selectedRoleId.value)];

    submitting.value = true;
    errors.value = {};

    try {
        // Si no hay contraseña, eliminar los campos de contraseña del form
        const formData = { ...form.value };
        if (!formData.password) {
            delete formData.password;
            delete formData.password_confirmation;
        }

        const response = await api.put(`/users/${userId.value}`, formData) as { data: ApiResponse };

        if (response.data.success) {
            await Swal.fire({
                icon: 'success',
                title: 'Usuario actualizado',
                text: response.data.message,
                timer: 1500,
                showConfirmButton: false
            });

            router.push('/usuarios');
        } else {
            throw new Error(response.data.message || 'Error al actualizar el usuario');
        }
    } catch (error: any) {
        console.error('Error actualizando usuario:', error);

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
                text: error.response?.data?.message || 'Error al actualizar el usuario'
            });
        }
    } finally {
        submitting.value = false;
    }
};

// Inicializar
onMounted(async () => {
    loading.value = true;
    await Promise.all([loadUser(), loadRoles()]);
    loading.value = false;
});
</script>

<style scoped>
.form-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-checkbox:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}
</style>
