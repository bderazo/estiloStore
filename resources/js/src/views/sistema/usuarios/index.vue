<template>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <router-link to="/" class="text-primary hover:underline">
                    Inicio
                </router-link>
            </li>
            <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                <span>Usuarios</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h5 class="text-lg font-semibold dark:text-white-light">Usuarios del Sistema</h5>
                    <router-link
                        v-if="can('usuarios.create')"
                        to="/administrador/usuarios/crear"
                        class="btn btn-primary"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/>
                            <path d="m12 8 0 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="m8 12 8 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        Nuevo Usuario
                    </router-link>
                </div>

                <!-- Filtros -->
                <div class="mb-5">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input
                                type="text"
                                v-model="searchTerm"
                                placeholder="Buscar por documento, nombre, apellido o email..."
                                class="form-input"
                                @input="debouncedSearch"
                            />
                        </div>
                        <div class="flex gap-2">
                            <select v-model="perPage" @change="handlePerPageChange" class="form-select w-auto">
                                <option value="5">5 por página</option>
                                <option value="10">10 por página</option>
                                <option value="25">25 por página</option>
                                <option value="50">50 por página</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="loading">
                                <td colspan="5" class="text-center py-8">
                                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                                    <div class="mt-2">Cargando usuarios...</div>
                                </td>
                            </tr>
                            <tr v-else-if="users.length === 0">
                                <td colspan="5" class="text-center py-8 text-gray-500">
                                    No se encontraron usuarios
                                </td>
                            </tr>
                            <tr v-else v-for="user in users" :key="user.id">
                                <td>{{ user.numero_documento }}</td>
                                <td>
                                    <div class="font-semibold">{{ user.nombres }} {{ user.apellidos }}</div>
                                </td>
                                <td>{{ user.email || 'Sin email' }}</td>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        <span class="badge bg-primary/10 text-primary">
                                            {{ user?.role?.nombre }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="flex gap-2 justify-center">

                                        <router-link
                                            v-if="can('usuarios.edit')"
                                            :to="`/administrador/usuarios/${user.id}/editar`"
                                            class="btn btn-outline-warning btn-sm"
                                            title="Editar"
                                        >
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                <path d="M11 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M16.04 3.02001L8.16 10.9C7.86 11.2 7.56 11.79 7.5 12.22L7.07 15.23C6.91 16.32 7.68 17.09 8.77 16.93L11.78 16.5C12.2 16.44 12.79 16.14 13.1 15.84L20.98 7.96001C22.34 6.60001 22.98 5.02001 20.98 3.02001C18.98 1.02001 17.4 1.66001 16.04 3.02001Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M14.91 4.1501C15.58 6.5401 17.45 8.4101 19.85 9.0901" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </router-link>
                                        <button
                                            v-if="can('usuarios.delete')"
                                            @click="confirmDelete(user)"
                                            class="btn btn-outline-danger btn-sm"
                                            title="Eliminar"
                                        >
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                <path d="M21 5.98001C17.67 5.65001 14.32 5.48001 10.98 5.48001C9 5.48001 7.02 5.58001 5.04 5.78001L3 5.98001" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M18.85 9.14001L18.2 19.21C18.09 20.78 18 22 15.21 22H8.79002C6.00002 22 5.91002 20.78 5.80002 19.21L5.15002 9.14001" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div v-if="pagination && pagination.last_page > 1" class="mt-5 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Mostrando {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }}
                        a {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}
                        de {{ pagination.total }} resultados
                    </div>
                    <div class="flex gap-1">
                        <button
                            @click="changePage(pagination.current_page - 1)"
                            :disabled="pagination.current_page === 1"
                            class="btn btn-outline-primary btn-sm"
                            :class="{ 'opacity-50 cursor-not-allowed': pagination.current_page === 1 }"
                        >
                            Anterior
                        </button>
                        <template v-for="page in getVisiblePages()" :key="page">
                            <button
                                @click="changePage(page)"
                                class="btn btn-sm"
                                :class="page === pagination.current_page ? 'btn-primary' : 'btn-outline-primary'"
                            >
                                {{ page }}
                            </button>
                        </template>
                        <button
                            @click="changePage(pagination.current_page + 1)"
                            :disabled="pagination.current_page === pagination.last_page"
                            class="btn btn-outline-primary btn-sm"
                            :class="{ 'opacity-50 cursor-not-allowed': pagination.current_page === pagination.last_page }"
                        >
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useAuthStore } from '../../../stores/auth';
import { usePermissions } from '../../../composables/usePermissions';
import api from '../../../services/api';
import Swal from 'sweetalert2';
import type { User, PaginationData, PaginatedResponse } from '../../../types/index';

const { can } = usePermissions();
const authStore = useAuthStore();

// Estado
const users = ref<User[]>([]);
const loading = ref(false);
const searchTerm = ref('');
const perPage = ref(10);
const pagination = ref<PaginationData | null>(null);

// Cargar usuarios
const loadUsers = async (page = 1) => {
    loading.value = true;
    try {
        const params = new URLSearchParams({
            page: page.toString(),
            per_page: perPage.value.toString(),
        });

        if (searchTerm.value.trim()) {
            params.append('search', searchTerm.value.trim());
        }

        const response = await api.get(`/users?${params}`);

        if (response.data.success) {
            users.value = response.data.data.users;
            pagination.value = response.data.data.pagination;
        }
    } catch (error) {
        console.error('Error cargando usuarios:', error);
        Swal.fire({
            title: 'Error',
            text: 'No se pudieron cargar los usuarios',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    } finally {
        loading.value = false;
    }
};

// Búsqueda con debounce
let searchTimeout: NodeJS.Timeout;
const debouncedSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadUsers(1);
    }, 300);
};

// Cambiar página
const changePage = (page: number) => {
    if (page >= 1 && pagination.value && page <= pagination.value.last_page) {
        loadUsers(page);
    }
};

// Manejar cambio en elementos por página
const handlePerPageChange = () => {
    loadUsers(1);
};

// Páginas visibles en la paginación
const getVisiblePages = () => {
    if (!pagination.value) return [];

    const current = pagination.value.current_page;
    const last = pagination.value.last_page;
    const pages = [];

    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
};

// Confirmar eliminación
const confirmDelete = (user: any) => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `Se eliminará el usuario "${user.nombres} ${user.apellidos}". Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteUser(user.id);
        }
    });
};

// Eliminar usuario
const deleteUser = async (userId: number) => {
    try {
        const response = await api.delete(`/users/${userId}`);

        if (response.data.success) {
            Swal.fire({
                title: '¡Eliminado!',
                text: response.data.message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });

            // Recargar la lista
            loadUsers(pagination.value?.current_page || 1);
        }
    } catch (error: any) {
        console.error('Error eliminando usuario:', error);
        const message = error.response?.data?.message || 'Error al eliminar el usuario';

        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    }
};

// Lifecycle
onMounted(() => {
    loadUsers();
});
</script>
