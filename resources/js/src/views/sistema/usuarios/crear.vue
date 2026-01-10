<template>
    <div>
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            <h2 class="text-xl">Crear Usuario</h2>
            <div class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto">
                <router-link to="/administrador/usuarios" class="btn btn-outline-danger gap-2">
                    ← Volver
                </router-link>
            </div>
        </div>

        <div class="panel">
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

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input
                             autocomplete="new-password"
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="form-input"
                                :class="{ 'border-red-500': errors.password }"
                                placeholder="Mínimo 8 caracteres"
                                minlength="8"
                                required
                            />
                            <div v-if="errors.password" class="mt-1 text-sm text-red-600">
                                {{ errors.password[0] }}
                            </div>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input
                             autocomplete="new-password"
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="form-input"
                                :class="{ 'border-red-500': errors.password_confirmation }"
                                placeholder="Confirma la contraseña"
                                minlength="8"
                                required
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
                            :disabled="loading || !selectedRoleId"
                            class="btn btn-primary gap-2"
                        >
                            💾 {{ loading ? 'Guardando...' : 'Crear Usuario' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { usePermissions } from '../../../composables/usePermissions';
import api from '../../../services/api';
import Swal from 'sweetalert2';
import type { Role, UserFormData, ApiResponse } from '../../../types/index';

// Usando emojis en lugar de componentes de iconos

const router = useRouter();
const { can } = usePermissions();

// Verificar permisos
if (!can('usuarios.create')) {
    router.push('/usuarios');
}

// Estado
const loading = ref(false);
const roles = ref<Role[]>([]);
const errors = ref<Record<string, string[]>>({});

// Estado del rol seleccionado
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

    loading.value = true;
    errors.value = {};

    try {
        const response = await api.post('/users', form.value) as { data: ApiResponse };

        if (response.data.success) {
            await Swal.fire({
                icon: 'success',
                title: 'Usuario creado',
                text: response.data.message,
                timer: 1500,
                showConfirmButton: false
            });

            router.push('/usuarios');
        } else {
            throw new Error(response.data.message || 'Error al crear el usuario');
        }
    } catch (error: any) {
        console.error('Error creando usuario:', error);

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
                text: error.response?.data?.message || 'Error al crear el usuario'
            });
        }
    } finally {
        loading.value = false;
    }
};

// Inicializar
onMounted(() => {
    loadRoles();
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
