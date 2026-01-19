<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        Editar Folleto
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Modifica la información y el archivo de este catálogo
                    </p>
                </div>
                <router-link
                    to="/administrador/folletos"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </router-link>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="store.loading && !store.folleto" class="flex justify-center items-center h-64">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Cargando folleto...</p>
            </div>
        </div>

        <!-- Formulario -->
        <div v-else-if="store.folleto" class="max-w-4xl mx-auto">
            <!-- Información Actual -->
            <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-300">
                            Estás editando: <span class="font-bold">{{ store.folleto.nombre }}</span>
                        </p>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-blue-600 dark:text-blue-400">Descargas actuales:</span>
                                <span class="ml-2 font-bold">{{ store.folleto.descargas }}</span>
                            </div>
                            <div>
                                <span class="text-blue-600 dark:text-blue-400">Tamaño archivo:</span>
                                <span class="ml-2 font-bold">{{ store.folleto.tamano_archivo }}</span>
                            </div>
                            <div>
                                <span class="text-blue-600 dark:text-blue-400">Creado el:</span>
                                <span class="ml-2 font-bold">{{ formatFecha(store.folleto.created_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="p-6">
                    <!-- Mensajes de Error -->
                    <div v-if="store.error" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 dark:text-red-400">{{ store.error }}</p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submitForm">
                        <!-- Nombre -->
                        <div class="mb-6">
                            <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nombre del Folleto <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nombre"
                                type="text"
                                id="nombre"
                                required
                                class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition"
                            >
                        </div>

                        <!-- Descripción -->
                        <div class="mb-6">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descripción
                            </label>
                            <textarea
                                v-model="form.descripcion"
                                id="descripcion"
                                rows="3"
                                class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition resize-none"
                            ></textarea>
                            <div class="mt-1 flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <p>Esta descripción será visible para los usuarios.</p>
                                <span>{{ (form.descripcion || '').length }}/500</span>
                            </div>
                        </div>

                        <!-- Gestión de Archivo -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Archivo PDF
                            </label>
                            
                            <!-- Archivo Actual -->
                            <div v-if="!form.archivo" class="mb-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                Archivo actual
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ store.folleto.tamano_archivo }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <a
                                            :href="store.folleto.archivo_url"
                                            target="_blank"
                                            class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                        >
                                            Ver
                                        </a>
                                        <button
                                            type="button"
                                            @click="form.reset_descargas = true"
                                            :class="[
                                                'text-sm',
                                                form.reset_descargas 
                                                    ? 'text-orange-600 dark:text-orange-400 font-bold' 
                                                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300'
                                            ]"
                                            title="Resetear contador de descargas al cambiar archivo"
                                        >
                                            Resetear descargas
                                        </button>
                                        <button
                                            type="button"
                                            @click="showChangeFile = true"
                                            class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                        >
                                            Cambiar archivo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nuevo Archivo Seleccionado -->
                            <div v-else class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ form.archivo.name }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ formatFileSize(form.archivo.size) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button
                                            type="button"
                                            @click="removeFile"
                                            class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                        >
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                                <!-- Opción para resetear descargas -->
                                <div v-if="showChangeFile" class="mt-3">
                                    <label class="flex items-center">
                                        <input
                                            v-model="form.reset_descargas"
                                            type="checkbox"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700"
                                        >
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Resetear contador de descargas a 0
                                        </span>
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        El contador actual ({{ store.folleto.descargas }} descargas) se reiniciará.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Subida de Nuevo Archivo -->
                            <div v-if="showChangeFile" class="mt-4">
                                <div
                                    @dragover="handleDragOver"
                                    @dragleave="handleDragLeave"
                                    @drop="handleDrop"
                                    :class="[
                                        'border-2 border-dashed rounded-lg p-6 text-center transition-all duration-200',
                                        isDragging 
                                            ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' 
                                            : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500'
                                    ]"
                                >
                                    <input
                                        ref="fileInput"
                                        type="file"
                                        accept=".pdf"
                                        @change="handleFileSelect"
                                        class="hidden"
                                    >
                                    
                                    <div>
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium text-blue-600 dark:text-blue-400 cursor-pointer" @click="openFilePicker">
                                                Selecciona un nuevo archivo
                                            </span>
                                            o arrástralo aquí
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Solo archivos PDF (máximo 10MB)
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botón para cambiar archivo -->
                            <button
                                v-if="!showChangeFile && !form.archivo"
                                type="button"
                                @click="showChangeFile = true"
                                class="mt-3 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                            >
                                + Cambiar archivo PDF
                            </button>
                            
                            <!-- Botón para cancelar cambio -->
                            <button
                                v-if="showChangeFile && !form.archivo"
                                type="button"
                                @click="cancelFileChange"
                                class="mt-3 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300"
                            >
                                Cancelar cambio de archivo
                            </button>

                            <!-- Validación de Archivo -->
                            <div v-if="fileError" class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="ml-2 text-sm text-red-700 dark:text-red-400">{{ fileError }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Estado del Folleto
                                    </label>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Los folletos inactivos no estarán disponibles para descarga.
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    @click="form.estado = !form.estado"
                                    :class="[
                                        'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                                        form.estado ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'
                                    ]"
                                >
                                    <span
                                        :class="[
                                            'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                            form.estado ? 'translate-x-5' : 'translate-x-0'
                                        ]"
                                    />
                                </button>
                            </div>
                            <div class="mt-2 flex items-center">
                                <span 
                                    :class="[
                                        'px-2 py-1 text-xs font-medium rounded-full',
                                        form.estado 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                    ]"
                                >
                                    {{ form.estado ? 'Activo' : 'Inactivo' }}
                                </span>
                                <p class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ form.estado ? 'Los usuarios podrán ver y descargar este folleto.' : 'Este folleto estará oculto para los usuarios.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t dark:border-gray-700">
                            <router-link
                                to="/administrador/folletos"
                                class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition"
                            >
                                Cancelar
                            </router-link>
                            <button
                                type="submit"
                                :disabled="store.loading || !isFormValid"
                                :class="[
                                    'px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition',
                                    (store.loading || !isFormValid) 
                                        ? 'opacity-50 cursor-not-allowed' 
                                        : ''
                                ]"
                            >
                                <span v-if="store.loading" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Actualizando...
                                </span>
                                <span v-else>
                                    Actualizar Folleto
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div v-else-if="!store.loading && !store.folleto" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Folleto no encontrado</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                El folleto que buscas no existe o ha sido eliminado.
            </p>
            <div class="mt-6">
                <router-link
                    to="/administrador/folletos"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Volver a la lista
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useFolletoStore } from '../../stores/folletoStore';
import type { FolletoFormData } from '../../types/folleto';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';

const route = useRoute();
const router = useRouter();
const store = useFolletoStore();
const fileInput = ref<HTMLInputElement | null>(null);

// Estado del formulario
const form = ref<Partial<FolletoFormData>>({
    nombre: '',
    descripcion: '',
    archivo: null,
    estado: true,
    reset_descargas: false
});

const isDragging = ref(false);
const showChangeFile = ref(false);
const fileError = ref('');

// Inicializar con datos del folleto
onMounted(async () => {
    const id = parseInt(route.params.id as string);
    if (id) {
        await store.fetchFolleto(id);
        if (store.folleto) {
            form.value = {
                nombre: store.folleto.nombre,
                descripcion: store.folleto.descripcion || '',
                archivo: null,
                estado: store.folleto.estado,
                reset_descargas: false
            };
        }
    }
});

// Validaciones
const isFormValid = computed(() => {
    return form.value.nombre?.trim() !== '' && !fileError.value;
});

// Métodos de archivo
const openFilePicker = () => {
    fileInput.value?.click();
};

const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        validateFile(target.files[0]);
    }
};

const handleDragOver = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = true;
};

const handleDragLeave = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = false;
};

const handleDrop = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = false;
    
    if (event.dataTransfer?.files && event.dataTransfer.files[0]) {
        validateFile(event.dataTransfer.files[0]);
    }
};

const validateFile = (file: File) => {
    fileError.value = '';
    
    if (file.type !== 'application/pdf') {
        fileError.value = 'El archivo debe ser un PDF válido.';
        return;
    }
    
    const maxSize = 10 * 1024 * 1024;
    if (file.size > maxSize) {
        fileError.value = `El archivo es demasiado grande. Tamaño máximo: 10MB (actual: ${formatFileSize(file.size)})`;
        return;
    }
    
    form.value.archivo = file;
    showChangeFile.value = true;
};

const removeFile = () => {
    form.value.archivo = null;
    showChangeFile.value = true;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const cancelFileChange = () => {
    form.value.archivo = null;
    showChangeFile.value = false;
    fileError.value = '';
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const formatFileSize = (bytes: number): string => {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const formatFecha = (fecha: string) => {
    return format(new Date(fecha), 'dd/MM/yyyy HH:mm', { locale: es });
};

// Envío del formulario
const submitForm = async () => {
    if (!store.folleto || !isFormValid.value) return;
    
    try {
        await store.updateFolleto(store.folleto.id, form.value as FolletoFormData);
        
        // Redirigir al listado
        router.push('/administrador/folletos');
    } catch (error) {
        console.error('Error al actualizar folleto:', error);
    }
};

// Limpiar estado al desmontar
onUnmounted(() => {
    store.clearState();
});
</script>