<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        Nuevo Folleto
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Crea un nuevo catálogo en PDF para que los usuarios puedan descargarlo
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

        <!-- Formulario -->
        <div class="max-w-4xl mx-auto">
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
                                placeholder="Ej: Catálogo General 2024"
                            >
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Nombre descriptivo que identificará este folleto.
                            </p>
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
                                placeholder="Breve descripción del contenido del folleto..."
                            ></textarea>
                            <div class="mt-1 flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <p>Esta descripción será visible para los usuarios.</p>
                                <span>{{ form.descripcion.length }}/500</span>
                            </div>
                        </div>

                        <!-- Subida de PDF -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Archivo PDF <span class="text-red-500">*</span>
                            </label>
                            
                            <!-- Drag & Drop Area -->
                            <div
                                @dragover="handleDragOver"
                                @dragleave="handleDragLeave"
                                @drop="handleDrop"
                                :class="[
                                    'border-2 border-dashed rounded-lg p-8 text-center transition-all duration-200',
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
                                
                                <div v-if="!form.archivo">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium text-blue-600 dark:text-blue-400 cursor-pointer" @click="openFilePicker">
                                            Haz clic para subir
                                        </span>
                                        o arrastra y suelta tu archivo
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Solo archivos PDF (máximo 10MB)
                                    </p>
                                </div>
                                
                                <div v-else>
                                    <div class="flex items-center justify-center">
                                        <svg class="h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ form.archivo.name }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ formatFileSize(form.archivo.size) }}
                                        </p>
                                        <div class="mt-4 flex items-center justify-center space-x-3">
                                            <button
                                                type="button"
                                                @click="openFilePicker"
                                                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                            >
                                                Cambiar archivo
                                            </button>
                                            <button
                                                type="button"
                                                @click="removeFile"
                                                class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                            >
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                    Guardando...
                                </span>
                                <span v-else>
                                    Guardar Folleto
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useFolletoStore } from '../../stores/folletoStore';
import type { FolletoFormData } from '../../types/folleto';

const router = useRouter();
const store = useFolletoStore();
const fileInput = ref<HTMLInputElement | null>(null);

// Estado del formulario
const form = ref<FolletoFormData>({
    nombre: '',
    descripcion: '',
    archivo: null,
    estado: true
});

const isDragging = ref(false);
const fileError = ref('');

// Validaciones
const isFormValid = computed(() => {
    return form.value.nombre.trim() !== '' && 
           form.value.archivo !== null &&
           !fileError.value;
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
    // Limpiar error anterior
    fileError.value = '';
    
    // Validar tipo de archivo
    if (file.type !== 'application/pdf') {
        fileError.value = 'El archivo debe ser un PDF válido.';
        return;
    }
    
    // Validar tamaño (10MB)
    const maxSize = 10 * 1024 * 1024; // 10MB en bytes
    if (file.size > maxSize) {
        fileError.value = `El archivo es demasiado grande. Tamaño máximo: 10MB (actual: ${formatFileSize(file.size)})`;
        return;
    }
    
    // Archivo válido
    form.value.archivo = file;
};

const removeFile = () => {
    form.value.archivo = null;
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

// Envío del formulario
const submitForm = async () => {
    if (!isFormValid.value) return;
    
    try {
        await store.createFolleto(form.value);
        
        // Redirigir al listado
        router.push('/administrador/folletos');
    } catch (error) {
        console.error('Error al crear folleto:', error);
    }
};

// Limpiar estado al desmontar
onUnmounted(() => {
    store.clearState();
});
</script>