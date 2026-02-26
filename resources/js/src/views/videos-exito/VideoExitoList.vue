<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    <svg class="w-6 h-6 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Videos de Casos de Éxito
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Administra los videos de casos de éxito de tus clientas
                </p>
            </div>
            <div class="flex gap-3">
                <button
                    @click="openConfig"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Configuración
                </button>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Video
                </button>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Videos</p>
                        <p class="text-2xl font-semibold">{{ videoStore.videos.length }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Activos</p>
                        <p class="text-2xl font-semibold">{{ videosActivos.length }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Visualizaciones</p>
                        <p class="text-2xl font-semibold">{{ totalViews }}K</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Último mes</p>
                        <p class="text-2xl font-semibold">{{ videosUltimoMes }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1">
                    <input
                        v-model="filters.search"
                        type="text"
                        placeholder="Buscar por título, persona o descripción..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <select
                    v-model="filters.estado"
                    class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Todos los estados</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
                <button
                    @click="resetFilters"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-50"
                >
                    Limpiar filtros
                </button>
            </div>
        </div>

        <!-- Videos Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orden</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thumbnail</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persona</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">YouTube ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="videoStore.loading">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                                <p class="mt-2 text-gray-500">Cargando videos...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredVideos.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay videos</h3>
                                <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo video.</p>
                            </td>
                        </tr>
                        <tr
                            v-for="video in filteredVideos"
                            :key="video.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <button
                                        @click="moveUp(video)"
                                        :disabled="video.orden === 0"
                                        class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-30"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                    <span class="mx-2">{{ video.orden }}</span>
                                    <button
                                        @click="moveDown(video)"
                                        :disabled="video.orden === maxOrden"
                                        class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-30"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-16 w-24 rounded-lg overflow-hidden border">
                                    <img
                                        :src="video.thumbnail!"
                                        :alt="video.titulo"
                                        class="h-full w-full object-cover"
                                    >
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ video.titulo }}</div>
                                <div class="text-sm text-gray-500">{{ video.subtitulo || '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ video.nombre_persona }}</div>
                                <div class="text-sm text-gray-500">{{ video.cargo_persona || '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs bg-gray-100 p-1 rounded">{{ video.youtube_id }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <button
                                    @click="toggleStatus(video.id)"
                                    :class="[
                                        'px-2 py-1 text-xs font-medium rounded-full',
                                        video.activo
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800'
                                    ]"
                                >
                                    {{ video.activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a
                                        :href="`https://www.youtube.com/watch?v=${video.youtube_id}`"
                                        target="_blank"
                                        class="text-gray-600 hover:text-gray-900"
                                        title="Ver en YouTube"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <button
                                        @click="editVideo(video)"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="confirmDelete(video)"
                                        class="text-red-600 hover:text-red-900"
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
        </div>

        <!-- Modals -->
        <VideoExitoForm
            v-if="showFormModal"
            :video="selectedVideo"
            @close="closeFormModal"
            @saved="handleSaved"
        />

        <VideoExitoConfig
            v-if="showConfigModal"
            :config="videoStore.config"
            @close="showConfigModal = false"
            @saved="handleConfigSaved"
        />

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium mb-4">Confirmar eliminación</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    ¿Estás seguro que deseas eliminar el video "{{ videoToDelete?.titulo }}"? Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-end space-x-3">
                    <button
                        @click="showDeleteModal = false"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteVideo"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useVideoExitoStore } from '../../../src/stores/videoExitoStore';
import VideoExitoForm from './VideoExitoForm.vue';
import VideoExitoConfig from './VideoExitoConfig.vue';
import type { VideoExito } from '../../../src/types/videoExito';
import Swal from 'sweetalert2';

const videoStore = useVideoExitoStore();
const showFormModal = ref(false);
const showConfigModal = ref(false);
const showDeleteModal = ref(false);
const selectedVideo = ref<VideoExito | null>(null);
const videoToDelete = ref<VideoExito | null>(null);

const filters = ref({
    search: '',
    estado: ''
});

// Computed
const videosActivos = computed(() => 
    videoStore.videos.filter(v => v.activo)
);

const totalViews = computed(() => {
    // Esto es solo un placeholder, puedes implementar lógica real si tienes estadísticas
    return videoStore.videos.length * 4;
});

const videosUltimoMes = computed(() => {
    const haceUnMes = new Date();
    haceUnMes.setMonth(haceUnMes.getMonth() - 1);
    
    return videoStore.videos.filter(v => 
        new Date(v.created_at) > haceUnMes
    ).length;
});

const filteredVideos = computed(() => {
    return videoStore.videos.filter(v => {
        const matchesSearch = filters.value.search === '' || 
            v.titulo.toLowerCase().includes(filters.value.search.toLowerCase()) ||
            v.nombre_persona.toLowerCase().includes(filters.value.search.toLowerCase()) ||
            (v.descripcion && v.descripcion.toLowerCase().includes(filters.value.search.toLowerCase()));
        
        const matchesEstado = filters.value.estado === '' || 
            v.activo === (filters.value.estado === '1');
        
        return matchesSearch && matchesEstado;
    });
});

const maxOrden = computed(() => 
    Math.max(...videoStore.videos.map(v => v.orden), 0)
);

// Methods
onMounted(async () => {
    await videoStore.fetchVideos();
    await videoStore.fetchConfig();
});

const openCreateModal = () => {
    selectedVideo.value = null;
    showFormModal.value = true;
};

const openConfig = () => {
    showConfigModal.value = true;
};

const editVideo = (video: VideoExito) => {
    selectedVideo.value = video;
    showFormModal.value = true;
};

const closeFormModal = () => {
    showFormModal.value = false;
    selectedVideo.value = null;
};

const handleSaved = () => {
    closeFormModal();
};

const handleConfigSaved = () => {
    showConfigModal.value = false;
};

const toggleStatus = async (id: number) => {
    try {
        await videoStore.toggleStatus(id);
        Swal.fire({
            icon: 'success',
            title: 'Estado actualizado',
            timer: 1500,
            showConfirmButton: false
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo cambiar el estado'
        });
    }
};

const confirmDelete = (video: VideoExito) => {
    videoToDelete.value = video;
    showDeleteModal.value = true;
};

const deleteVideo = async () => {
    if (!videoToDelete.value) return;
    
    try {
        await videoStore.deleteVideo(videoToDelete.value.id);
        showDeleteModal.value = false;
        videoToDelete.value = null;
        
        Swal.fire({
            icon: 'success',
            title: 'Eliminado',
            text: 'Video eliminado exitosamente',
            timer: 2000
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo eliminar el video'
        });
    }
};

const moveUp = (video: VideoExito) => {
    if (video.orden === 0) return;
    // Implementar lógica de reordenamiento
};

const moveDown = (video: VideoExito) => {
    if (video.orden === maxOrden.value) return;
    // Implementar lógica de reordenamiento
};

const resetFilters = () => {
    filters.value = {
        search: '',
        estado: ''
    };
};
</script>