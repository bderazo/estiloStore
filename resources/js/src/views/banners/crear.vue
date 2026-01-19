<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <router-link
                        to="/administrador/banners"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 transition mr-4"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </router-link>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Crear Nuevo Banner
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Configura un nuevo banner para mostrar en el sitio web
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="p-6">
                    <!-- Mensajes de Error -->
                    <div v-if="bannerStore.error" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 dark:text-red-400">{{ bannerStore.error }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje de Éxito -->
                    <div v-if="successMessage" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 dark:text-green-400">{{ successMessage }}</p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submitForm" class="space-y-6">
                        <!-- Sección -->
                        <div>
                            <label for="seccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sección <span class="text-red-500">*</span>
                            </label>
                            <select 
                                v-model="formData.seccion" 
                                id="seccion" 
                                required
                                :disabled="bannerStore.loading"
                                :class="[
                                    'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                    bannerStore.loading ? 'opacity-50 cursor-not-allowed' : ''
                                ]"
                            >
                                <option value="">Seleccionar sección</option>
                                <option v-for="seccion in SECCIONES_BANNER" :key="seccion.value" :value="seccion.value">
                                    {{ seccion.label }}
                                </option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Selecciona dónde se mostrará este banner
                            </p>
                        </div>

                        <!-- Título y Subtítulo -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Título
                                </label>
                                <input 
                                    v-model="formData.titulo" 
                                    type="text" 
                                    id="titulo" 
                                    placeholder="Título del banner"
                                    :disabled="bannerStore.loading"
                                    :class="[
                                        'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                        bannerStore.loading ? 'opacity-50 cursor-not-allowed' : ''
                                    ]"
                                />
                            </div>
                            <div>
                                <label for="subtitulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Subtítulo
                                </label>
                                <input 
                                    v-model="formData.subtitulo" 
                                    type="text" 
                                    id="subtitulo" 
                                    placeholder="Subtítulo del banner"
                                    :disabled="bannerStore.loading"
                                    :class="[
                                        'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                        bannerStore.loading ? 'opacity-50 cursor-not-allowed' : ''
                                    ]"
                                />
                            </div>
                        </div>

                        <!-- URL Destino -->
                        <div>
                            <label for="url_destino" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                URL Destino
                            </label>
                            <input 
                                v-model="formData.url_destino" 
                                type="url" 
                                id="url_destino" 
                                placeholder="https://ejemplo.com"
                                :disabled="bannerStore.loading"
                                :class="[
                                    'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                    bannerStore.loading ? 'opacity-50 cursor-not-allowed' : ''
                                ]"
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                URL a la que redirigirá el banner (opcional)
                            </p>
                        </div>

                        <!-- Imagen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Imagen <span class="text-red-500">*</span>
                            </label>
                            
                            <!-- Vista previa -->
                            <div v-if="imagePreview" class="mb-6">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vista previa:</p>
                                <div class="relative w-full h-64 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600">
                                    <img 
                                        :src="imagePreview" 
                                        alt="Vista previa" 
                                        class="w-full h-full object-contain"
                                    />
                                    <button 
                                        type="button" 
                                        @click="removeImage"
                                        class="absolute top-3 right-3 p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500"
                                        :disabled="bannerStore.loading"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Dropzone -->
                            <div
                                v-if="!imagePreview"
                                @click="triggerFileInput"
                                @dragover.prevent="handleDragOver"
                                @dragleave.prevent="handleDragLeave"
                                @drop.prevent="handleDrop"
                                :class="[
                                    'border-2 border-dashed rounded-lg p-8 text-center transition-all duration-200 cursor-pointer',
                                    isDragging 
                                        ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' 
                                        : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500',
                                    bannerStore.loading ? 'opacity-50 cursor-not-allowed' : ''
                                ]"
                                :disabled="bannerStore.loading"
                            >
                                <input 
                                    type="file" 
                                    ref="fileInput"
                                    @change="handleImageChange"
                                    accept="image/*"
                                    class="hidden"
                                    :disabled="bannerStore.loading"
                                />
                                
                                <div class="space-y-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            <span 
                                                class="font-medium text-blue-600 dark:text-blue-400"
                                            >
                                                Haz clic para subir
                                            </span>
                                            o arrastra y suelta tu archivo
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            PNG, JPG, GIF hasta 5MB
                                        </p>
                                    </div>
                                    <button
                                        type="button"
                                        @click.stop="triggerFileInput"
                                        :disabled="bannerStore.loading"
                                        :class="[
                                            'inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500',
                                            bannerStore.loading ? 'opacity-50 cursor-not-allowed' : ''
                                        ]"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Seleccionar imagen
                                    </button>
                                </div>
                            </div>

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
                        <div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Estado del Banner
                                    </label>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        El banner se mostrará solo si está activo
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    @click="formData.estado = !formData.estado"
                                    :disabled="bannerStore.loading"
                                    :class="[
                                        'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                                        formData.estado ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700',
                                        bannerStore.loading ? 'opacity-50 cursor-not-allowed' : ''
                                    ]"
                                >
                                    <span
                                        :class="[
                                            'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                            formData.estado ? 'translate-x-5' : 'translate-x-0'
                                        ]"
                                    />
                                </button>
                            </div>
                            <div class="mt-2 flex items-center">
                                <span 
                                    :class="[
                                        'px-2 py-1 text-xs font-medium rounded-full',
                                        formData.estado 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                    ]"
                                >
                                    {{ formData.estado ? 'Activo' : 'Inactivo' }}
                                </span>
                                <p class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ formData.estado ? 'Los usuarios podrán ver este banner.' : 'Este banner estará oculto para los usuarios.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t dark:border-gray-700">
                            <router-link
                                to="/administrador/banners"
                                :class="[
                                    'px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition',
                                    bannerStore.loading ? 'opacity-50 cursor-not-allowed' : ''
                                ]"
                            >
                                Cancelar
                            </router-link>
                            <button
                                type="submit"
                                :disabled="bannerStore.loading || !isFormValid"
                                :class="[
                                    'px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition',
                                    (bannerStore.loading || !isFormValid) 
                                        ? 'opacity-50 cursor-not-allowed' 
                                        : ''
                                ]"
                            >
                                <span v-if="bannerStore.loading" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Guardando...
                                </span>
                                <span v-else class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Guardar Banner
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
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useBannerStore } from '../../stores/banner';
import { SECCIONES_BANNER } from '../../types/banner';
import type { BannerFormData } from '../../types/banner';

const router = useRouter();
const bannerStore = useBannerStore();
const fileInput = ref<HTMLInputElement | null>(null);

const formData = ref<BannerFormData>({
    seccion: '',
    titulo: '',
    subtitulo: '',
    imagen: null,
    url_destino: '',
    estado: true,
});

const imagePreview = ref<string | null>(null);
const successMessage = ref<string>('');
const isDragging = ref(false);
const fileError = ref<string>('');

// Validación del formulario
const isFormValid = computed(() => {
    return formData.value.seccion.trim() !== '' && 
           formData.value.imagen !== null &&
           !fileError.value;
});

// Métodos de archivo
const triggerFileInput = () => {
    if (bannerStore.loading) return;
    fileInput.value?.click();
};

const handleImageChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
        const file = input.files[0];
        validateImageFile(file);
    }
};

const handleDragOver = (event: DragEvent) => {
    if (bannerStore.loading) return;
    event.preventDefault();
    isDragging.value = true;
};

const handleDragLeave = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = false;
};

const handleDrop = (event: DragEvent) => {
    if (bannerStore.loading) return;
    event.preventDefault();
    isDragging.value = false;
    
    if (event.dataTransfer?.files && event.dataTransfer.files[0]) {
        const file = event.dataTransfer.files[0];
        validateImageFile(file);
    }
};

const validateImageFile = (file: File) => {
    // Limpiar error anterior
    fileError.value = '';
    
    // Validar tipo de archivo
    if (!file.type.startsWith('image/')) {
        fileError.value = 'El archivo debe ser una imagen válida.';
        return;
    }
    
    // Validar tamaño (5MB)
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        fileError.value = 'La imagen es demasiado grande. Tamaño máximo: 5MB';
        return;
    }
    
    // Archivo válido
    formData.value.imagen = file;
    
    // Crear vista previa
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
};

// Remover imagen
const removeImage = () => {
    if (bannerStore.loading) return;
    
    formData.value.imagen = null;
    imagePreview.value = null;
    fileError.value = '';
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

// Enviar formulario
const submitForm = async () => {
    if (!isFormValid.value) {
        if (!formData.value.seccion) {
            fileError.value = 'Por favor, selecciona una sección';
        } else if (!formData.value.imagen) {
            fileError.value = 'Por favor, selecciona una imagen';
        }
        return;
    }
    
    try {
        const formDataToSend = new FormData();
        formDataToSend.append('seccion', formData.value.seccion);
        if (formData.value.titulo) formDataToSend.append('titulo', formData.value.titulo);
        if (formData.value.subtitulo) formDataToSend.append('subtitulo', formData.value.subtitulo);
        if (formData.value.imagen) formDataToSend.append('imagen', formData.value.imagen);
        if (formData.value.url_destino) formDataToSend.append('url_destino', formData.value.url_destino);
        formDataToSend.append('estado', formData.value.estado ? '1' : '0');
        
        await bannerStore.createBanner(formDataToSend);
        
        successMessage.value = '¡Banner creado exitosamente!';
        
        // Redirigir después de 2 segundos
        setTimeout(() => {
            router.push('/administrador/banners');
        }, 2000);
    } catch (error) {
        console.error('Error al crear banner:', error);
    }
};
</script>