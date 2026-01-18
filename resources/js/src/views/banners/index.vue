<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                <svg class="w-6 h-6 inline-block mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Gestión de Banners
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Administra los banners de tu sitio web
            </p>
        </div>

        <!-- Dashboard de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Banners -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Banners</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ bannerStore.banners.length }}</p>
                    </div>
                </div>
            </div>

            <!-- Activos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Activos</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ bannerStore.banners.filter(b => b.estado).length }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Inactivos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Inactivos</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ bannerStore.banners.filter(b => !b.estado).length }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Por Sección (ejemplo: Home) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">En Home</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ bannerStore.banners.filter(b => b.seccion === 'home').length }}
                        </p>
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
                        to="/administrador/banners/crear"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nuevo Banner
                    </router-link>

                    <!-- Filtros -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Búsqueda -->
                        <div class="relative">
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Buscar por título, subtítulo o sección..."
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
                            v-model="filterEstado"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tabla de Banners -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Imagen
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Sección
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Título
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Subtítulo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Loading State -->
                        <tr v-if="bannerStore.loading">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">Cargando banners...</p>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-else-if="filteredBanners.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay banners</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Comienza creando un nuevo banner.
                                </p>
                                <div class="mt-6">
                                    <router-link
                                        to="/administrador/banners/crear"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nuevo Banner
                                    </router-link>
                                </div>
                            </td>
                        </tr>

                        <!-- Banners -->
                        <tr 
                            v-for="banner in filteredBanners" 
                            :key="banner.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex-shrink-0 h-20 w-32 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                                    <img 
                                        :src="getImageUrl(banner.imagen_url)" 
                                        :alt="banner.titulo || 'Banner'"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        getSeccionBadgeClass(banner.seccion)
                                    ]"
                                >
                                    {{ getSeccionLabel(banner.seccion) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-xs">
                                    {{ banner.titulo || 'Sin título' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    ID: {{ banner.id }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">
                                    {{ banner.subtitulo || 'Sin subtítulo' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button
                                    @click="toggleBannerStatus(banner.id)"
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all',
                                        banner.estado
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800'
                                    ]"
                                >
                                    <span class="w-2 h-2 rounded-full mr-1" :class="banner.estado ? 'bg-green-500' : 'bg-red-500'"></span>
                                    {{ banner.estado ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Ver URL -->
                                    <a 
                                        v-if="banner.url_destino" 
                                        :href="banner.url_destino" 
                                        target="_blank"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        title="Ver URL destino"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>

                                    <!-- Editar -->
                                    <router-link
                                        :to="`/administrador/banners/${banner.id}/editar`"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </router-link>

                                    <!-- Cambiar Estado -->
                                    <button
                                        @click="toggleBannerStatus(banner.id)"
                                        :class="[
                                            'hover:text-yellow-900 dark:hover:text-yellow-300',
                                            banner.estado 
                                                ? 'text-yellow-600 dark:text-yellow-400' 
                                                : 'text-gray-600 dark:text-gray-400'
                                        ]"
                                        :title="banner.estado ? 'Desactivar' : 'Activar'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path v-if="banner.estado" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>

                                    <!-- Eliminar -->
                                    <button
                                        @click="confirmDelete(banner)"
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

            <!-- Mostrando resultados (sin paginación) -->
            <div v-if="filteredBanners.length > 0" class="px-6 py-4 border-t dark:border-gray-700">
                <div class="text-sm text-gray-700 dark:text-gray-400">
                    Mostrando 
                    <span class="font-medium">{{ filteredBanners.length }}</span>
                    de 
                    <span class="font-medium">{{ bannerStore.banners.length }}</span>
                    banners
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
                    ¿Eliminar banner?
                </h3>
                <div class="mt-2 px-7 py-3">
                    <div class="flex items-center gap-4 mb-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <div class="h-16 w-24 overflow-hidden rounded border border-gray-200 dark:border-gray-600">
                            <img 
                                v-if="bannerToDelete"
                                :src="getImageUrl(bannerToDelete.imagen_url)" 
                                alt="Banner"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="text-left">
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ bannerToDelete?.titulo || 'Sin título' }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ bannerToDelete ? getSeccionLabel(bannerToDelete.seccion) : '' }}
                            </p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ¿Estás seguro de que deseas eliminar este banner? 
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="items-center px-4 py-3 mt-4">
                    <button
                        @click="showDeleteModal = false"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteBanner"
                        :disabled="bannerStore.loading"
                        :class="[
                            'px-4 py-2 text-white text-base font-medium rounded-md w-24',
                            bannerStore.loading 
                                ? 'bg-red-400 cursor-not-allowed' 
                                : 'bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300'
                        ]"
                    >
                        <span v-if="bannerStore.loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Eliminando...
                        </span>
                        <span v-else>Eliminar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { useBannerStore } from '../../stores/banner';
import { SECCIONES_BANNER } from '../../types/banner';

const bannerStore = useBannerStore();
const search = ref('');
const filterEstado = ref('');
const showDeleteModal = ref(false);
const bannerToDelete = ref<any>(null);

// Cargar banners al montar
onMounted(async () => {
    await bannerStore.fetchBanners();
});

// Obtener URL completa de la imagen
const getImageUrl = (path: string) => {
    if (!path) return '/assets/images/placeholder.png';
    if (path.startsWith('http')) return path;
    return `/storage/${path}`;
};

// Obtener etiqueta de la sección
const getSeccionLabel = (seccion: string) => {
    const found = SECCIONES_BANNER.find(s => s.value === seccion);
    return found ? found.label : seccion;
};

// Obtener clase CSS para el badge de sección
const getSeccionBadgeClass = (seccion: string) => {
    const classes: Record<string, string> = {
        'home': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'about': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'services': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'contact': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'promo': 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200'
    };
    
    // Si la sección no está mapeada, usar una clase por defecto
    for (const key in classes) {
        if (seccion.toLowerCase().includes(key)) {
            return classes[key];
        }
    }
    
    return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};

// Filtrar banners
const filteredBanners = computed(() => {
    let filtered = bannerStore.banners;

    // Filtrar por búsqueda
    if (search.value) {
        const term = search.value.toLowerCase();
        filtered = filtered.filter(banner =>
            (banner.titulo?.toLowerCase().includes(term) || false) ||
            (banner.subtitulo?.toLowerCase().includes(term) || false) ||
            (banner.seccion?.toLowerCase().includes(term) || false)
        );
    }

    // Filtrar por estado
    if (filterEstado.value !== '') {
        const estado = filterEstado.value === '1';
        filtered = filtered.filter(banner => banner.estado === estado);
    }

    return filtered;
});

// Watch para filtrar cuando cambian los valores
watch([search, filterEstado], () => {
    // La propiedad computed se actualiza automáticamente
});

// Confirmar eliminación
const confirmDelete = (banner: any) => {
    bannerToDelete.value = banner;
    showDeleteModal.value = true;
};

// Eliminar banner
const deleteBanner = async () => {
    if (!bannerToDelete.value) return;
    
    try {
        await bannerStore.deleteBanner(bannerToDelete.value.id);
        showDeleteModal.value = false;
        bannerToDelete.value = null;
    } catch (error) {
        console.error('Error al eliminar banner:', error);
    }
};

// Cambiar estado del banner
const toggleBannerStatus = async (id: number) => {
    try {
        await bannerStore.toggleStatus(id);
    } catch (error) {
        console.error('Error al cambiar estado:', error);
    }
};
</script>