<template>
    <div>
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <button class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                    <IconHome class="w-4 h-4" />
                </button>
            </li>
            <li class="before:content-['/'] before:px-1.5">
                <span class="text-black dark:text-white-light">Administración</span>
            </li>
            <li class="before:content-['/'] before:px-1.5">
                <span>Colores</span>
            </li>
        </ol>

        <div class="panel mt-6">
            <div class="flex items-center justify-between mb-6">
                <h5 class="font-semibold text-lg dark:text-white-light">
                    Gestión de Colores
                </h5>
                <button @click="openCreateModal" class="btn btn-primary gap-2" type="button">
                    <IconPlus class="w-4 h-4" />
                    Nuevo Color
                </button>
            </div>

            <!-- Filtros -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Búsqueda -->
                <div class="relative">
                    <input v-model="filters.search" @input="debounceSearch" type="text"
                        placeholder="Buscar por nombre o código..." class="form-input pl-10" />
                    <IconSearch class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                </div>

                <!-- Estado -->
                <select v-model="filters.activo" @change="applyFilters" class="form-select">
                    <option value="">Todos los estados</option>
                    <option :value="true">Activos</option>
                    <option :value="false">Inactivos</option>
                </select>

                <!-- Ordenar por -->
                <select v-model="filters.sort_by" @change="applyFilters" class="form-select">
                    <option value="nombre">Ordenar por Nombre</option>
                    <option value="codigo_hex">Ordenar por Código</option>
                    <option value="created_at">Ordenar por Fecha</option>
                </select>

                <!-- Orden -->
                <select v-model="filters.sort_order" @change="applyFilters" class="form-select">
                    <option value="asc">Ascendente</option>
                    <option value="desc">Descendente</option>
                </select>
            </div>

            <!-- Tabla de colores -->
            <div class="datatables" v-if="!loading">
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>Color</th>
                                <th>Nombre</th>
                                <th>Código Hex</th>
                                <th>Estado</th>
                                <th>Fecha Creación</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="color in colores" :key="color.id">
                                <!-- Preview del color -->
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div :style="{ backgroundColor: color.codigo_hex }"
                                            class="w-8 h-8 rounded border border-gray-300 shadow-sm"
                                            :title="color.codigo_hex"></div>
                                        <div :style="{
                                            backgroundColor: color.codigo_hex,
                                            color: color.color_preview?.contrast || '#000'
                                        }" class="px-2 py-1 text-xs rounded font-medium">
                                            {{ color.codigo_hex }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Nombre -->
                                <td>
                                    <div class="font-semibold">{{ color.nombre }}</div>
                                </td>

                                <!-- Código hex -->
                                <td>
                                    <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-sm">
                                        {{ color.codigo_hex }}
                                    </code>
                                </td>

                                <!-- Estado -->
                                <td>
                                    <span :class="[
                                        'badge',
                                        color.activo ? 'badge-outline-success' : 'badge-outline-danger'
                                    ]">
                                        {{ color.activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>

                                <!-- Fecha -->
                                <td>
                                    <div class="text-sm">
                                        {{ formatDate(color.created_at) }}
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Toggle estado -->
                                        <button @click="toggleColorStatus(color.id)" :class="[
                                            'btn btn-sm',
                                            color.activo ? 'btn-warning' : 'btn-success'
                                        ]" :title="color.activo ? 'Desactivar' : 'Activar'">
                                            <IconPower class="w-4 h-4" />
                                        </button>

                                        <!-- Editar -->
                                        <button @click="openEditModal(color)" class="btn btn-sm btn-primary"
                                            title="Editar">
                                            <IconEdit class="w-4 h-4" />
                                        </button>

                                        <!-- Eliminar -->
                                        <button @click="deleteColor(color.id)" class="btn btn-sm btn-danger"
                                            title="Eliminar">
                                            <IconTrash class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div v-if="pagination.total > 0" class="flex items-center justify-between mt-4">
                    <div class="text-sm text-gray-500">
                        Mostrando {{ pagination.from }} a {{ pagination.to }} de {{ pagination.total }} registros
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Registros por página -->
                        <select v-model="filters.per_page" @change="applyFilters" class="form-select text-sm w-20">
                            <option :value="10">10</option>
                            <option :value="15">15</option>
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                        </select>

                        <!-- Navegación -->
                        <div class="flex">
                            <button @click="changePage(pagination.current_page - 1)"
                                :disabled="pagination.current_page <= 1"
                                class="btn btn-sm btn-outline-primary disabled:opacity-50">
                                Anterior
                            </button>
                            <button @click="changePage(pagination.current_page + 1)"
                                :disabled="pagination.current_page >= pagination.last_page"
                                class="btn btn-sm btn-outline-primary disabled:opacity-50 ml-1">
                                Siguiente
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Estado vacío -->
                <div v-if="colores.length === 0" class="text-center py-8">
                    <div class="text-gray-500 mb-4">
                        <IconPalette class="w-16 h-16 mx-auto mb-2 opacity-30" />
                        <p>No se encontraron colores</p>
                    </div>
                    <button @click="openCreateModal" class="btn btn-primary">
                        Crear primer color
                    </button>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            </div>
        </div>

        <!-- Modal de formulario -->
        <ColorFormModal v-if="showModal" :visible="showModal" :color="selectedColor" @close="closeModal"
            @saved="handleColorSaved" />
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useColorStore } from '../../stores/colorStore';
import type { Color, ColorFilters } from '../../types/color';
import ColorFormModal from './ColorFormModal.vue';

// Icons
import {
    IconHome,
    IconPlus,
    IconSearch,
    IconEdit,
    IconTrash,
    IconPower,
    IconPalette,
} from '@tabler/icons-vue';

// Store
const colorStore = useColorStore();
const { colores, loading, error, pagination } = storeToRefs(colorStore);

// Estados locales
const showModal = ref(false);
const selectedColor = ref<Color | null>(null);
const searchTimeout = ref<number | null>(null);

// Filtros reactivos
const filters = ref<ColorFilters>({
    search: '',
    activo: '',
    sort_by: 'nombre',
    sort_order: 'asc',
    per_page: 15,
});

// Métodos
const loadColores = async () => {
    await colorStore.fetchColores(filters.value);
};

const applyFilters = () => {
    loadColores();
};

const debounceSearch = () => {
    if (searchTimeout.value) {
        clearTimeout(searchTimeout.value);
    }

    searchTimeout.value = setTimeout(() => {
        applyFilters();
    }, 500);
};

const changePage = (page: number) => {
    if (page >= 1 && page <= pagination.value.last_page) {
        filters.value = { ...filters.value, current_page: page };
        loadColores();
    }
};

const openCreateModal = () => {
    selectedColor.value = null;
    showModal.value = true;
};

const openEditModal = (color: Color) => {
    selectedColor.value = color;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedColor.value = null;
};

const handleColorSaved = () => {
    closeModal();
    loadColores();
};

const toggleColorStatus = async (id: number) => {
    await colorStore.toggleActivo(id);
};

const deleteColor = async (id: number) => {
    await colorStore.deleteColor(id);
};

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

// Lifecycle
onMounted(() => {
    loadColores();
});
</script>

<style scoped>
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.dark .table-hover tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

code {
    font-family: 'Courier New', Courier, monospace;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.125rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-outline-success {
    color: rgb(34 197 94);
    background-color: rgb(240 253 244);
    border: 1px solid rgb(187 247 208);
}

.badge-outline-danger {
    color: rgb(239 68 68);
    background-color: rgb(254 242 242);
    border: 1px solid rgb(252 165 165);
}

.dark .badge-outline-success {
    color: rgb(187 247 208);
    background-color: rgb(20 83 45);
    border-color: rgb(34 197 94);
}

.dark .badge-outline-danger {
    color: rgb(252 165 165);
    background-color: rgb(127 29 29);
    border-color: rgb(239 68 68);
}
</style>
