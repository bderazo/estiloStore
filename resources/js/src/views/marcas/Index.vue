<template>
    <div>
        <!-- Breadcrumb -->
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <router-link to="/" class="text-primary hover:underline">Inicio</router-link>
            </li>
            <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                <span>Marcas</span>
            </li>
        </ul>

        <div class="pt-5">
            <!-- Header con botón crear -->
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Gestión de Marcas</h5>
                    <router-link
                        to="/administrador/marcas/crear"
                        class="btn btn-primary"
                    >
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Nueva Marca
                    </router-link>
                </div>

                <!-- Filtros -->
                <div class="mb-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Buscar -->
                    <div>
                        <input
                            v-model="searchTerm"
                            type="text"
                            placeholder="Buscar marcas..."
                            class="form-input"
                            @input="handleSearch"
                        />
                    </div>

                    <!-- Filtro por estado -->
                    <div>
                        <select
                            v-model="statusFilter"
                            class="form-select"
                            @change="handleStatusFilter"
                        >
                            <option value="">Todos los estados</option>
                            <option value="true">Activo</option>
                            <option value="false">Inactivo</option>
                        </select>
                    </div>

                    <!-- Elementos por página -->
                    <div>
                        <select
                            v-model="perPage"
                            class="form-select"
                            @change="handlePerPageChange"
                        >
                            <option value="10">10 por página</option>
                            <option value="15">15 por página</option>
                            <option value="25">25 por página</option>
                            <option value="50">50 por página</option>
                        </select>
                    </div>
                </div>


                <!-- Tabla -->
                <div class="datatables">
                    <div v-if="marcaStore.loading" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Cargando marcas...</p>
                    </div>

                    <div v-else-if="marcaStore.marcas.length === 0" class="text-center py-8">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 text-gray-400">
                            <path d="M7 8.5L9.94202 10.2394C11.6572 11.2535 12.3428 11.2535 14.058 10.2394L17 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2.01577 13.4756C2.08114 16.5412 2.11383 18.0739 3.24496 19.2094C4.37608 20.3448 5.95033 20.3843 9.09883 20.4634C11.0393 20.5122 12.9607 20.5122 14.9012 20.4634C18.0497 20.3843 19.6239 20.3448 20.7551 19.2094C21.8862 18.0739 21.9189 16.5412 21.9842 13.4756C22.0053 12.4899 22.0053 11.5101 21.9842 10.5244C21.9189 7.45886 21.8862 5.92609 20.7551 4.79066C19.6239 3.65523 18.0497 3.61568 14.9012 3.53657C12.9607 3.48781 11.0393 3.48781 9.09882 3.53656C5.95033 3.61566 4.37608 3.65521 3.24496 4.79065C2.11383 5.92608 2.08114 7.45885 2.01577 10.5244C1.99474 11.5101 1.99474 12.4899 2.01577 13.4756Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400">No hay marcas registradas</p>
                        <router-link
                            to="/administrador/marcas/crear"
                            class="btn btn-primary mt-4"
                        >
                            Crear primera marca
                        </router-link>
                    </div>

                    <div v-else class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th @click="handleSort('nombre')" class="cursor-pointer">
                                        <div class="flex items-center">
                                            Nombre
                                            <svg v-if="sortField === 'nombre'"
                                                 :class="{'rotate-180': sortOrder === 'desc'}"
                                                 width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ml-1">
                                                <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </th>
                                    <th>Slug</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Estado</th>
                                    <th @click="handleSort('created_at')" class="cursor-pointer">
                                        <div class="flex items-center">
                                            Fecha Creación
                                            <svg v-if="sortField === 'created_at'"
                                                 :class="{'rotate-180': sortOrder === 'desc'}"
                                                 width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ml-1">
                                                <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="marca in marcaStore.marcas" :key="marca.id">
                                    <td>{{ marca.id }}</td>
                                    <td class="font-semibold">{{ marca.nombre }}</td>
                                    <td class="text-gray-600 dark:text-gray-400">{{ marca.slug }}</td>
                                    <td>
                                        <div class="max-w-xs truncate" :title="marca.descripcion || undefined">
                                            {{ marca.descripcion || 'Sin descripción' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            :class="{
                                                'badge-outline-success': marca.activo,
                                                'badge-outline-danger': !marca.activo
                                            }"
                                            class="badge"
                                        >
                                            {{ marca.activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="text-gray-600 dark:text-gray-400">
                                        {{ marca.formatted_created_at }}
                                    </td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Ver -->
                                            <button
                                                @click="viewMarca(marca)"
                                                type="button"
                                                class="btn btn-sm btn-outline-info"
                                                v-tippy:view
                                            >
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M15.0007 12C15.0007 13.6569 13.6576 15 12.0007 15C10.3439 15 9.00073 13.6569 9.00073 12C9.00073 10.3431 10.3439 9 12.0007 9C13.6576 9 15.0007 10.3431 15.0007 12Z" stroke="currentColor" stroke-width="1.5"/>
                                                    <path d="M12.0012 21C7.52166 21 3.73157 17.8761 2.45898 13.1835C2.15171 12.0611 2.15171 11.9389 2.45898 10.8165C3.73157 6.12394 7.52166 3 12.0012 3C16.4807 3 20.2708 6.12394 21.5434 10.8165C21.8507 11.9389 21.8507 12.0611 21.5434 13.1835C20.2708 17.8761 16.4807 21 12.0012 21Z" stroke="currentColor" stroke-width="1.5"/>
                                                </svg>
                                            </button>

                                            <!-- Editar -->
                                            <router-link
                                                :to="`/administrador/marcas/${marca.id}/editar`"
                                                class="btn btn-sm btn-outline-primary"
                                                v-tippy:edit
                                            >
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M15.2869 3.15178C14.3263 2.19115 12.7975 2.19115 11.8369 3.15178L4.83691 10.1518C4.37059 10.6181 4.11402 11.2357 4.11402 11.8758V16.3615C4.11402 17.2956 4.87285 18.0545 5.80691 18.0545H10.2926C10.9327 18.0545 11.5503 17.7979 12.0166 17.3316L19.0166 10.3316C19.9772 9.37097 19.9772 7.84216 19.0166 6.88153L15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5"/>
                                                    <path d="M11.2435 4.65139L17.5174 10.9253" stroke="currentColor" stroke-width="1.5"/>
                                                </svg>
                                            </router-link>

                                            <!-- Toggle Estado -->
                                            <button
                                                @click="toggleStatus(marca)"
                                                type="button"
                                                :class="{
                                                    'btn-outline-success': !marca.activo,
                                                    'btn-outline-danger': marca.activo
                                                }"
                                                class="btn btn-sm"
                                                :data-tippy-content="marca.activo ? 'Desactivar' : 'Activar'"
                                                v-tippy
                                            >
                                                <svg v-if="marca.activo" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.64645 5.64645C5.84171 5.45118 6.15829 5.45118 6.35355 5.64645L18.3536 17.6464C18.5488 17.8417 18.5488 18.1583 18.3536 18.3536C18.1583 18.5488 17.8417 18.5488 17.6464 18.3536L5.64645 6.35355C5.45118 6.15829 5.45118 5.84171 5.64645 5.64645Z" fill="currentColor"/>
                                                    <path d="M18.3536 5.64645C18.5488 5.84171 18.5488 6.15829 18.3536 6.35355L6.35355 18.3536C6.15829 18.5488 5.84171 18.5488 5.64645 18.3536C5.45118 18.1583 5.45118 17.8417 5.64645 17.6464L17.6464 5.64645C17.8417 5.45118 18.1583 5.45118 18.3536 5.64645Z" fill="currentColor"/>
                                                </svg>
                                                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="1.5"/>
                                                </svg>
                                            </button>

                                            <!-- Eliminar -->
                                            <button
                                                @click="deleteMarca(marca)"
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                v-tippy:delete
                                            >
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div v-if="marcaStore.pagination && marcaStore.pagination.total > marcaStore.pagination.per_page" class="mt-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Mostrando {{ marcaStore.pagination.from }} - {{ marcaStore.pagination.to }}
                                de {{ marcaStore.pagination.total }} marcas
                            </div>

                            <div class="flex items-center gap-2">
                                <!-- Botón anterior -->
                                <button
                                    @click="changePage(marcaStore.pagination.current_page - 1)"
                                    :disabled="marcaStore.pagination.current_page === 1"
                                    class="btn btn-sm btn-outline-primary"
                                    :class="{ 'opacity-50 cursor-not-allowed': marcaStore.pagination.current_page === 1 }"
                                >
                                    Anterior
                                </button>

                                <!-- Números de página -->
                                <template v-for="page in getVisiblePages()" :key="page">
                                    <button
                                        v-if="page !== '...'"
                                        @click="changePage(typeof page === 'number' ? page : parseInt(page))"
                                        :class="{
                                            'btn-primary': page === marcaStore.pagination.current_page,
                                            'btn-outline-primary': page !== marcaStore.pagination.current_page
                                        }"
                                        class="btn btn-sm"
                                    >
                                        {{ page }}
                                    </button>
                                    <span v-else class="px-2">...</span>
                                </template>

                                <!-- Botón siguiente -->
                                <button
                                    @click="changePage(marcaStore.pagination.current_page + 1)"
                                    :disabled="marcaStore.pagination.current_page === marcaStore.pagination.last_page"
                                    class="btn btn-sm btn-outline-primary"
                                    :class="{ 'opacity-50 cursor-not-allowed': marcaStore.pagination.current_page === marcaStore.pagination.last_page }"
                                >
                                    Siguiente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tooltips -->
        <tippy target="view">Ver detalles</tippy>
        <tippy target="edit">Editar marca</tippy>
        <tippy target="delete">Eliminar marca</tippy>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useMarcaStore } from '../../stores/marcaStore';
import { Marca } from '../../types/marca';
import Swal from 'sweetalert2';

// Store
const marcaStore = useMarcaStore();

// Refs reactivos
const searchTerm = ref('');
const statusFilter = ref('');
const perPage = ref(15);
const sortField = ref('nombre');
const sortOrder = ref<'asc' | 'desc'>('asc');

// Debounce timer para búsqueda
let searchTimer: ReturnType<typeof setTimeout>;

// Métodos
const handleSearch = () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        marcaStore.searchMarcas(searchTerm.value);
    }, 500);
};

const handleStatusFilter = () => {
    const activo = statusFilter.value === '' ? null : statusFilter.value === 'true';
    marcaStore.filterByActivo(activo);
};

const handlePerPageChange = () => {
    marcaStore.changePerPage(Number(perPage.value));
};

const handleSort = (field: string) => {
    if (sortField.value === field) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortOrder.value = 'asc';
    }
    marcaStore.changeSorting(field, sortOrder.value);
};

const changePage = (page: number) => {
    marcaStore.changePage(page);
};

const getVisiblePages = () => {
    const pagination = marcaStore.pagination;
    if (!pagination) return [];

    const current = pagination.current_page;
    const last = pagination.last_page;
    const pages = [];

    if (last <= 7) {
        for (let i = 1; i <= last; i++) {
            pages.push(i);
        }
    } else {
        if (current <= 4) {
            for (let i = 1; i <= 5; i++) {
                pages.push(i);
            }
            pages.push('...');
            pages.push(last);
        } else if (current >= last - 3) {
            pages.push(1);
            pages.push('...');
            for (let i = last - 4; i <= last; i++) {
                pages.push(i);
            }
        } else {
            pages.push(1);
            pages.push('...');
            for (let i = current - 1; i <= current + 1; i++) {
                pages.push(i);
            }
            pages.push('...');
            pages.push(last);
        }
    }

    return pages;
};

const viewMarca = (marca: Marca) => {
    Swal.fire({
        title: marca.nombre,
        html: `
            <div class="text-left">
                <p><strong>Slug:</strong> ${marca.slug}</p>
                <p><strong>Descripción:</strong> ${marca.descripcion || 'Sin descripción'}</p>
                <p><strong>Estado:</strong> ${marca.activo ? 'Activo' : 'Inactivo'}</p>
                <p><strong>Fecha creación:</strong> ${marca.formatted_created_at}</p>
            </div>
        `,
        confirmButtonText: 'Cerrar',
        customClass: {
            confirmButton: 'btn btn-primary'
        }
    });
};

const toggleStatus = async (marca: Marca) => {
    const action = marca.activo ? 'desactivar' : 'activar';

    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas ${action} la marca "${marca.nombre}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `Sí, ${action}`,
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger'
        }
    });

    if (result.isConfirmed) {
        try {
            await marcaStore.toggleActivo(marca.id);

            Swal.fire({
                title: '¡Éxito!',
                text: `La marca ha sido ${marca.activo ? 'desactivada' : 'activada'} correctamente.`,
                icon: 'success',
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        } catch (error: any) {
            Swal.fire({
                title: 'Error',
                text: error.response?.data?.message || `Error al ${action} la marca`,
                icon: 'error',
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        }
    }
};

const deleteMarca = async (marca: Marca) => {
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas eliminar la marca "${marca.nombre}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-outline-secondary'
        }
    });

    if (result.isConfirmed) {
        try {
            await marcaStore.deleteMarca(marca.id);

            Swal.fire({
                title: '¡Eliminada!',
                text: 'La marca ha sido eliminada correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        } catch (error: any) {
            Swal.fire({
                title: 'Error',
                text: error.response?.data?.message || 'Error al eliminar la marca',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        }
    }
};

// Lifecycle
onMounted(async () => {
    await marcaStore.fetchMarcas();
});
</script>
