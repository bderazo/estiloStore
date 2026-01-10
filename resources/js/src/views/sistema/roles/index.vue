<template>
    <div>
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            <h2 class="text-xl">Gestión de Roles</h2>
            <div class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto">
                <router-link
                    v-if="can('roles.create')"
                    to="/administrador/roles/crear"
                    class="btn btn-primary gap-2"
                >
                    ➕ Crear Rol
                </router-link>
            </div>
        </div>

        <div class="panel">
            <div class="flex md:items-center justify-between md:flex-row flex-col mb-4.5 gap-5">
                <div class="flex items-center flex-wrap">
                    <div class="ltr:mr-2 rtl:ml-2">
                        <input
                            v-model="searchTerm"
                            type="text"
                            class="form-input w-auto"
                            placeholder="Buscar roles..."
                            @input="debouncedSearch"
                        />
                    </div>
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

            <div class="datatables">
                <div v-if="loading" class="min-h-[200px] flex items-center justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                </div>

                <table v-else class="table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Permisos</th>
                            <th>Usuarios</th>
                            <th class="!text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="roles.length === 0">
                            <td colspan="5" class="!text-center">
                                <div class="flex justify-center items-center min-h-[60px]">
                                    <div class="text-center">
                                        <div class="text-gray-500 mb-2">📋</div>
                                        <p class="text-gray-600">
                                            {{ searchTerm ? 'No se encontraron roles con ese criterio' : 'No hay roles registrados' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-else v-for="role in roles" :key="role.id">
                            <td>
                                <div class="font-semibold">{{ role.nombre }}</div>
                            </td>
                            <td>
                                <div class="text-gray-600">{{ role.descripcion || 'Sin descripción' }}</div>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <span
                                        v-for="permission in role.permissions.slice(0, 3)"
                                        :key="permission.id"
                                        class="badge bg-primary/10 text-primary text-xs"
                                    >
                                        {{ permission.nombre }}
                                    </span>
                                    <span
                                        v-if="role.permissions.length > 3"
                                        class="badge bg-gray-100 text-gray-600 text-xs"
                                    >
                                        +{{ role.permissions.length - 3 }} más
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success/10 text-success">
                                    {{ role.users?.length || 0 }} usuarios
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <router-link
                                        v-if="can('roles.edit')"
                                        :to="`/administrador/roles/${role.id}/editar`"
                                        class="btn btn-outline-primary btn-sm"
                                        title="Editar rol"
                                    >
                                        ✏️ Editar
                                    </router-link>

                                    <button
                                        v-if="can('roles.delete')"
                                        @click="confirmDelete(role)"
                                        class="btn btn-outline-danger btn-sm"
                                        title="Eliminar rol"
                                        :disabled="(role.users?.length || 0) > 0"
                                    >
                                        🗑️ Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Paginación -->
                <div v-if="pagination && pagination.last_page > 1" class="mt-5 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Mostrando {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }}
                        a {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}
                        de {{ pagination.total }} registros
                    </div>

                    <nav class="flex items-center gap-1">
                        <button
                            @click="changePage(pagination.current_page - 1)"
                            :disabled="pagination.current_page <= 1"
                            class="btn btn-outline-primary btn-sm"
                        >
                            Anterior
                        </button>

                        <button
                            v-for="page in getVisiblePages()"
                            :key="page"
                            @click="changePage(page)"
                            :class="[
                                'btn btn-sm',
                                page === pagination.current_page
                                    ? 'btn-primary'
                                    : 'btn-outline-primary'
                            ]"
                        >
                            {{ page }}
                        </button>

                        <button
                            @click="changePage(pagination.current_page + 1)"
                            :disabled="pagination.current_page >= pagination.last_page"
                            class="btn btn-outline-primary btn-sm"
                        >
                            Siguiente
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { usePermissions } from '../../../composables/usePermissions';
import api from '../../../services/api';
import Swal from 'sweetalert2';
import type { Role, PaginationData, PaginatedResponse } from '../../../types/index';

const { can } = usePermissions();

// Estado
const roles = ref<Role[]>([]);
const loading = ref(false);
const searchTerm = ref('');
const perPage = ref(10);
const pagination = ref<PaginationData | null>(null);

// Cargar roles
const loadRoles = async (page = 1) => {
    loading.value = true;
    try {
        const params = new URLSearchParams({
            page: page.toString(),
            per_page: perPage.value.toString(),
            ...(searchTerm.value && { search: searchTerm.value })
        });

        const response = await api.get(`/roles?${params}`) as { data: PaginatedResponse<Role> };

        if (response.data.success) {
            roles.value = response.data?.data?.data;
            pagination.value = response.data.data.pagination;
        }
    } catch (error) {
        console.error('Error cargando roles:', error);
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al cargar los roles'
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
        loadRoles(1);
    }, 300);
};

// Cambiar página
const changePage = (page: number) => {
    if (page >= 1 && pagination.value && page <= pagination.value.last_page) {
        loadRoles(page);
    }
};

// Manejar cambio en elementos por página
const handlePerPageChange = () => {
    loadRoles(1);
};

// Páginas visibles en la paginación
const getVisiblePages = () => {
    if (!pagination.value) return [];

    const current = pagination.value.current_page;
    const last = pagination.value.last_page;
    const pages = [];

    let start = Math.max(1, current - 2);
    let end = Math.min(last, current + 2);

    if (end - start < 4) {
        if (start === 1) {
            end = Math.min(last, start + 4);
        } else if (end === last) {
            start = Math.max(1, end - 4);
        }
    }

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
};

// Confirmar eliminación
const confirmDelete = async (role: Role) => {
    if (role.users && role.users.length > 0) {
        await Swal.fire({
            icon: 'warning',
            title: 'No se puede eliminar',
            text: `Este rol tiene ${role.users.length} usuarios asignados. Debes reasignar o eliminar esos usuarios primero.`
        });
        return;
    }

    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: `Se eliminará el rol "${role.nombre}". Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    });

    if (result.isConfirmed) {
        await deleteRole(role.id);
    }
};

// Eliminar rol
const deleteRole = async (roleId: number) => {
    try {
        const response = await api.delete(`/roles/${roleId}`);

        if (response.data.success) {
            await Swal.fire({
                icon: 'success',
                title: 'Rol eliminado',
                text: response.data.message,
                timer: 1500,
                showConfirmButton: false
            });

            // Recargar la lista actual o ir a la página anterior si esta queda vacía
            const currentPage = pagination.value?.current_page || 1;
            if (roles.value.length === 1 && currentPage > 1) {
                loadRoles(currentPage - 1);
            } else {
                loadRoles(pagination.value?.current_page || 1);
            }
        }
    } catch (error: any) {
        console.error('Error eliminando rol:', error);
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response?.data?.message || 'Error al eliminar el rol'
        });
    }
};

// Inicializar
onMounted(() => {
    loadRoles();
});
</script>

<style scoped>
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}


</style>
