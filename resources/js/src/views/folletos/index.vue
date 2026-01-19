<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Folletos y Catálogos
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Gestiona todos tus catálogos en PDF disponibles para descarga
            </p>
        </div>

        <!-- Dashboard de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Folletos</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ totalFolletos }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Activos</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ folletosActivos.length }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Descargas</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ totalDescargas }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Folleto más Popular</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                            {{ folletoMasPopular?.descargas || 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-500">{{ folletoMasPopular?.descargas || 0 }} descargas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de Acciones y Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-4 border-b dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Botón Nuevo -->
                    <router-link 
                        to="/administrador/folletos/crear"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nuevo Folleto
                    </router-link>

                    <!-- Filtros -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Búsqueda -->
                        <div class="relative">
                            <input
                                v-model="filters.search"
                                @input="onFilterChange"
                                type="text"
                                placeholder="Buscar folletos..."
                                class="pl-10 pr-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white w-full"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Filtro por Estado -->
                        <select
                            v-model="filters.estado"
                            @change="onFilterChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option :value="null">Todos los estados</option>
                            <option value="true">Activos</option>
                            <option value="false">Inactivos</option>
                        </select>

                        <!-- Ordenamiento -->
                        <select
                            v-model="filters.order_by"
                            @change="onFilterChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="created_at">Más recientes</option>
                            <option value="descargas">Más descargados</option>
                            <option value="nombre">Nombre A-Z</option>
                        </select>

                        <!-- Items por página -->
                        <select
                            v-model="filters.per_page"
                            @change="onFilterChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="10">10 por página</option>
                            <option value="15">15 por página</option>
                            <option value="25">25 por página</option>
                            <option value="50">50 por página</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tabla de Folletos -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Archivo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Descargas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Fecha Creación
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Loading State -->
                        <tr v-if="store.loading">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">Cargando folletos...</p>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-else-if="store.folletos.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay folletos</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Comienza creando un nuevo folleto.
                                </p>
                                <div class="mt-6">
                                    <router-link
                                        to="/administrador/folletos/crear"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nuevo Folleto
                                    </router-link>
                                </div>
                            </td>
                        </tr>

                        <!-- Folletos -->
                        <tr 
                            v-for="folleto in store.folletos" 
                            :key="folleto.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ folleto.nombre }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                            {{ folleto.descripcion || 'Sin descripción' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            PDF
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ folleto.tamano_archivo || 'Tamaño no disponible' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-32 mr-4">
                                        <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div 
                                                :style="{ width: getPorcentajeDescargas(folleto) + '%' }"
                                                class="h-full bg-gradient-to-r from-blue-500 to-purple-600"
                                            ></div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ folleto.descargas }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        folleto.estado 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ]"
                                >
                                    <span class="w-2 h-2 rounded-full mr-1" :class="folleto.estado ? 'bg-green-500' : 'bg-red-500'"></span>
                                    {{ folleto.estado_label || (folleto.estado ? 'Activo' : 'Inactivo') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ formatFecha(folleto.created_at) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Descargar -->
                                    <button
                                        @click="descargarFolleto(folleto)"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        title="Descargar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </button>

                                    <!-- Editar -->
                                    <router-link
                                        :to="`/administrador/folletos/${folleto.id}/editar`"
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </router-link>

                                    <!-- Cambiar Estado -->
                                    <button
                                        @click="toggleEstado(folleto.id)"
                                        :class="[
                                            'hover:text-yellow-900 dark:hover:text-yellow-300',
                                            folleto.estado 
                                                ? 'text-yellow-600 dark:text-yellow-400' 
                                                : 'text-gray-600 dark:text-gray-400'
                                        ]"
                                        :title="folleto.estado ? 'Desactivar' : 'Activar'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>

                                    <!-- Eliminar -->
                                    <button
                                        @click="confirmarEliminar(folleto)"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        title="Eliminar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div v-if="store.pagination.last_page > 1" class="px-6 py-4 border-t dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-400">
                        Mostrando 
                        <span class="font-medium">{{ ((store.pagination.current_page - 1) * store.pagination.per_page) + 1 }}</span>
                        a 
                        <span class="font-medium">{{ Math.min(store.pagination.current_page * store.pagination.per_page, store.pagination.total) }}</span>
                        de 
                        <span class="font-medium">{{ store.pagination.total }}</span>
                        resultados
                    </div>
                    <div class="flex space-x-2">
                        <button
                            @click="cambiarPagina(store.pagination.current_page - 1)"
                            :disabled="store.pagination.current_page === 1"
                            :class="[
                                'px-3 py-1 rounded-md text-sm font-medium',
                                store.pagination.current_page === 1
                                    ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                            ]"
                        >
                            Anterior
                        </button>
                        
                        <div class="flex space-x-1">
                            <button
                                v-for="page in paginasDisponibles"
                                :key="page"
                                @click="cambiarPagina(page)"
                                :class="[
                                    'px-3 py-1 rounded-md text-sm font-medium',
                                    page === store.pagination.current_page
                                        ? 'bg-blue-600 text-white'
                                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                                ]"
                            >
                                {{ page }}
                            </button>
                        </div>
                        
                        <button
                            @click="cambiarPagina(store.pagination.current_page + 1)"
                            :disabled="store.pagination.current_page === store.pagination.last_page"
                            :class="[
                                'px-3 py-1 rounded-md text-sm font-medium',
                                store.pagination.current_page === store.pagination.last_page
                                    ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                            ]"
                        >
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">
                        ¿Eliminar folleto?
                    </h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            ¿Estás seguro de que deseas eliminar "{{ folletoAEliminar?.nombre }}"? 
                            Esta acción no se puede deshacer y el archivo PDF también será eliminado.
                        </p>
                    </div>
                    <div class="items-center px-4 py-3 mt-4">
                        <button
                            @click="cancelarEliminar"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="eliminarFolleto"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300"
                        >
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useFolletoStore } from '../../stores/folletoStore';
import type { Folleto } from '../../types/folleto';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';

const store = useFolletoStore();
const showDeleteModal = ref(false);
const folletoAEliminar = ref<Folleto | null>(null);

// Filtros locales
const filters = ref({
    search: '',
    estado: undefined as string | boolean | undefined,
    order_by: 'created_at',
    order_dir: 'desc' as 'desc' | 'asc',
    per_page: 15
});

// Computed properties
const totalFolletos = computed(() => store.folletos.length);
const folletosActivos = computed(() => store.folletos.filter((f: { estado: any; }) => f.estado));
const totalDescargas = computed(() => 
    store.folletos.reduce((sum: any, f: { descargas: any; }) => sum + f.descargas, 0)
);
const folletoMasPopular = computed(() => 
    store.folletos.reduce((prev: { descargas: number; }, current: { descargas: number; }) => 
        (prev.descargas > current.descargas) ? prev : current, 
        { descargas: 0 } as Folleto
    )
);
const paginasDisponibles = computed(() => {
    const pages = [];
    const totalPages = store.pagination.last_page;
    const currentPage = store.pagination.current_page;
    
    let start = Math.max(1, currentPage - 2);
    let end = Math.min(totalPages, currentPage + 2);
    
    if (currentPage <= 3) {
        end = Math.min(5, totalPages);
    }
    if (currentPage >= totalPages - 2) {
        start = Math.max(1, totalPages - 4);
    }
    
    for (let i = start; i <= end; i++) {
        pages.push(i);
    }
    
    return pages;
});

// Métodos
const formatFecha = (fecha: string) => {
    return format(new Date(fecha), 'dd/MM/yyyy HH:mm', { locale: es });
};

const getPorcentajeDescargas = (folleto: Folleto) => {
    const maxDescargas = Math.max(...store.folletos.map(f => f.descargas));
    if (maxDescargas === 0) return 0;
    return (folleto.descargas / maxDescargas) * 100;
};

const onFilterChange = () => {
    store.setFilters(filters.value);
};

const cambiarPagina = (page: number) => {
    if (page >= 1 && page <= store.pagination.last_page) {
        store.fetchFolletos(page);
    }
};

const descargarFolleto = async (folleto: Folleto) => {
    try {
        await store.descargarArchivo(folleto.id, folleto.nombre);
    } catch (error) {
        console.error('Error al descargar:', error);
    }
};

const toggleEstado = async (id: number) => {
    try {
        await store.toggleEstado(id);
    } catch (error) {
        console.error('Error al cambiar estado:', error);
    }
};

const confirmarEliminar = (folleto: Folleto) => {
    folletoAEliminar.value = folleto;
    showDeleteModal.value = true;
};

const cancelarEliminar = () => {
    showDeleteModal.value = false;
    folletoAEliminar.value = null;
};

const eliminarFolleto = async () => {
    if (!folletoAEliminar.value) return;
    
    try {
        await store.deleteFolleto(folletoAEliminar.value.id);
        showDeleteModal.value = false;
        folletoAEliminar.value = null;
    } catch (error) {
        console.error('Error al eliminar:', error);
    }
};

// Lifecycle
onMounted(() => {
    store.fetchFolletos();
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>