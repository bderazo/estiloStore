<!-- src/views/banners/index.vue -->
<template>
    <div>
        <!-- Header con título y botón -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h2 class="text-xl font-bold">Gestión de Banners</h2>
            <div class="flex w-full flex-col gap-4 sm:w-auto sm:flex-row sm:items-center sm:gap-3">
                <div class="flex gap-3">
                    <router-link to="/administrador/banners/crear" class="btn btn-primary">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" stroke-width="1.5" fill="none" stroke="currentColor">
                            <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Nuevo Banner
                    </router-link>
                </div>
            </div>
        </div>

        <!-- Panel principal -->
        <div class="panel">
            <!-- Barra de búsqueda y filtros -->
            <div class="mb-5">
                <div class="flex flex-col gap-5 md:flex-row md:items-center">
                    <div class="ltr:ml-auto rtl:mr-auto">
                        <div class="flex flex-wrap gap-4">
                            <div class="relative">
                                <input 
                                    v-model="search" 
                                    type="text" 
                                    class="form-input w-64" 
                                    placeholder="Buscar por título, subtítulo o sección..."
                                />
                                <div class="absolute right-2 top-1/2 -translate-y-1/2">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle cx="11" cy="11" r="8" stroke-width="1.5"/>
                                        <path d="M21 21L16.65 16.65" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </div>
                            </div>
                            <select v-model="filterEstado" class="form-select w-32">
                                <option value="">Todos</option>
                                <option value="1">Activos</option>
                                <option value="0">Inactivos</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de banners -->
            <div class="table-responsive">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th class="w-12">ID</th>
                            <th class="w-20">Imagen</th>
                            <th>Sección</th>
                            <th>Título</th>
                            <th>Subtítulo</th>
                            <th class="w-24">Estado</th>
                            <th class="w-32">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="bannerStore.loading">
                            <tr>
                                <td colspan="7" class="text-center py-8">
                                    <div class="flex justify-center">
                                        <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-l-transparent"></div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template v-else-if="filteredBanners.length === 0">
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    No se encontraron banners
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr v-for="banner in filteredBanners" :key="banner.id">
                                <td>{{ banner.id }}</td>
                                <td>
                                    <div class="h-16 w-24 overflow-hidden rounded border">
                                        <img 
                                            :src="getImageUrl(banner.imagen_ruta)" 
                                            :alt="banner.titulo || 'Banner'"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary/10 text-primary border-primary/20">
                                        {{ getSeccionLabel(banner.seccion) }}
                                    </span>
                                </td>
                                <td class="max-w-xs truncate" :title="banner.titulo || 'Sin título'">
                                    {{ banner.titulo || 'Sin título' }}
                                </td>
                                <td class="max-w-xs truncate" :title="banner.subtitulo || 'Sin subtítulo'">
                                    {{ banner.subtitulo || 'Sin subtítulo' }}
                                </td>
                                <td>
                                    <button
                                        @click="toggleBannerStatus(banner.id)"
                                        :class="[
                                            'badge cursor-pointer',
                                            banner.estado 
                                                ? 'bg-success/10 text-success border-success/20' 
                                                : 'bg-danger/10 text-danger border-danger/20'
                                        ]"
                                    >
                                        {{ banner.estado ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <router-link 
                                            :to="`/administrador/banners/${banner.id}/editar`" 
                                            class="btn btn-sm btn-outline-primary"
                                            title="Editar"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke-width="1.5"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-width="1.5"/>
                                            </svg>
                                        </router-link>
                                        <button 
                                            @click="confirmDelete(banner)"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Eliminar"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke-width="1.5"/>
                                                <path d="M10 11v6M14 11v6" stroke-width="1.5"/>
                                            </svg>
                                        </button>
                                        <a 
                                            v-if="banner.url_destino" 
                                            :href="banner.url_destino" 
                                            target="_blank"
                                            class="btn btn-sm btn-outline-info"
                                            title="Ver URL"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke-width="1.5"/>
                                                <path d="M15 3h6v6M10 14L21 3" stroke-width="1.5"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Paginación (si es necesaria) -->
            <div v-if="filteredBanners.length > 0" class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Mostrando {{ filteredBanners.length }} de {{ bannerStore.banners.length }} banners
                </div>
                <!-- Aquí podrías agregar paginación real si tu API la soporta -->
            </div>
        </div>

        <!-- Modal de confirmación para eliminar -->
        <div v-if="showDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-[#000]/50 p-4">
            <div class="relative w-full max-w-md">
                <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">
                    <div class="p-6">
                        <div class="mb-4 flex items-center">
                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-danger/10">
                                <svg class="h-6 w-6 text-danger" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Confirmar Eliminación</h3>
                                <p class="text-gray-500">¿Estás seguro de eliminar este banner?</p>
                            </div>
                        </div>
                        
                        <div v-if="bannerToDelete" class="mb-6 rounded border p-4">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-24 overflow-hidden rounded border">
                                    <img 
                                        :src="getImageUrl(bannerToDelete.imagen_ruta)" 
                                        alt="Banner"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div>
                                    <p class="font-medium">{{ bannerToDelete.titulo || 'Sin título' }}</p>
                                    <p class="text-sm text-gray-500">{{ getSeccionLabel(bannerToDelete.seccion) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button 
                                @click="showDeleteModal = false"
                                type="button" 
                                class="btn btn-outline-secondary"
                            >
                                Cancelar
                            </button>
                            <button 
                                @click="deleteBanner"
                                type="button" 
                                class="btn btn-danger"
                                :disabled="bannerStore.loading"
                            >
                                <span v-if="bannerStore.loading" class="animate-spin border-2 border-white border-l-transparent rounded-full h-4 w-4 inline-block"></span>
                                <span v-else>Eliminar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
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