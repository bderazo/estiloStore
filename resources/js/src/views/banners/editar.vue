<!-- src/views/banners/editar.vue -->
<template>
    <div>
        <!-- Header con navegación -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <router-link to="/administrador/banners" class="btn btn-outline-primary btn-sm">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M19 12H5M5 12l7-7M5 12l7 7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Volver
                </router-link>
                <h2 class="text-xl font-bold">Editar Banner</h2>
            </div>
        </div>

        <!-- Formulario -->
        <div class="panel">
            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Mensajes de error/success -->
                <div v-if="bannerStore.error" class="rounded border border-danger/20 bg-danger/5 p-4 text-danger">
                    {{ bannerStore.error }}
                </div>

                <div v-if="successMessage" class="rounded border border-success/20 bg-success/5 p-4 text-success">
                    {{ successMessage }}
                </div>

                <!-- Cargando -->
                <div v-if="bannerStore.loading && !bannerStore.banner" class="text-center py-8">
                    <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-l-transparent mx-auto"></div>
                    <p class="mt-2 text-gray-500">Cargando banner...</p>
                </div>

                <template v-else-if="bannerStore.banner">
                    <!-- Sección -->
                    <div>
                        <label for="seccion" class="mb-1 block font-semibold">Sección <span class="text-danger">*</span></label>
                        <select 
                            v-model="formData.seccion" 
                            id="seccion" 
                            class="form-select"
                            required
                            :disabled="bannerStore.loading"
                        >
                            <option value="">Seleccionar sección</option>
                            <option v-for="seccion in SECCIONES_BANNER" :key="seccion.value" :value="seccion.value">
                                {{ seccion.label }}
                            </option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Selecciona dónde se mostrará este banner</p>
                    </div>

                    <!-- Título y Subtítulo -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="titulo" class="mb-1 block font-semibold">Título</label>
                            <input 
                                v-model="formData.titulo" 
                                type="text" 
                                id="titulo" 
                                class="form-input"
                                placeholder="Título del banner"
                                :disabled="bannerStore.loading"
                            />
                        </div>
                        <div>
                            <label for="subtitulo" class="mb-1 block font-semibold">Subtítulo</label>
                            <input 
                                v-model="formData.subtitulo" 
                                type="text" 
                                id="subtitulo" 
                                class="form-input"
                                placeholder="Subtítulo del banner"
                                :disabled="bannerStore.loading"
                            />
                        </div>
                    </div>

                    <!-- URL Destino -->
                    <div>
                        <label for="url_destino" class="mb-1 block font-semibold">URL Destino</label>
                        <input 
                            v-model="formData.url_destino" 
                            type="url" 
                            id="url_destino" 
                            class="form-input"
                            placeholder="https://ejemplo.com"
                            :disabled="bannerStore.loading"
                        />
                        <p class="mt-1 text-xs text-gray-500">URL a la que redirigirá el banner (opcional)</p>
                    </div>

                    <!-- Imagen -->
                    <div>
                        <label class="mb-1 block font-semibold">Imagen</label>
                        <div class="flex flex-col gap-4">
                            <!-- Imagen actual -->
                            <div v-if="bannerStore.banner.imagen_ruta" class="w-full max-w-md">
                                <p class="mb-2 text-sm font-medium">Imagen actual:</p>
                                <div class="relative h-48 w-full overflow-hidden rounded-lg border-2 border-gray-200">
                                    <img 
                                        :src="getImageUrl(bannerStore.banner.imagen_ruta)" 
                                        alt="Imagen actual" 
                                        class="h-full w-full object-contain"
                                    />
                                    <div class="absolute bottom-2 right-2 rounded bg-black/50 px-2 py-1 text-xs text-white">
                                        Actual
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Para cambiar la imagen, selecciona una nueva abajo</p>
                            </div>

                            <!-- Vista previa de nueva imagen -->
                            <div v-if="imagePreview" class="w-full max-w-md">
                                <p class="mb-2 text-sm font-medium">Nueva imagen (vista previa):</p>
                                <div class="relative h-48 w-full overflow-hidden rounded-lg border-2 border-dashed border-primary">
                                    <img 
                                        :src="imagePreview" 
                                        alt="Vista previa" 
                                        class="h-full w-full object-contain"
                                    />
                                    <button 
                                        type="button" 
                                        @click="removeImage"
                                        class="absolute right-2 top-2 rounded-full bg-danger p-1 text-white"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Selector de archivo -->
                            <div>
                                <div class="relative">
                                    <input 
                                        type="file" 
                                        ref="fileInput"
                                        @change="handleImageChange"
                                        accept="image/*"
                                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                        :disabled="bannerStore.loading"
                                    />
                                    <div class="flex cursor-pointer items-center justify-center rounded-lg border-2 border-dashed border-gray-300 p-6 hover:border-primary">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke-width="1.5"/>
                                                <polyline points="17 8 12 3 7 8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <line x1="12" y1="3" x2="12" y2="15" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                            <p class="mt-2 text-sm font-medium">Haz clic para cambiar la imagen</p>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 5MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="mb-1 block font-semibold">Estado</label>
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input 
                                    v-model="formData.estado" 
                                    type="checkbox" 
                                    class="sr-only" 
                                    :disabled="bannerStore.loading"
                                />
                                <div :class="[
                                    'block h-6 w-12 rounded-full',
                                    formData.estado ? 'bg-primary' : 'bg-gray-300'
                                ]"></div>
                                <div :class="[
                                    'dot absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-transform',
                                    formData.estado ? 'translate-x-6' : ''
                                ]"></div>
                            </div>
                            <div class="ml-3">
                                <span :class="formData.estado ? 'text-primary font-medium' : 'text-gray-500'">
                                    {{ formData.estado ? 'Activo' : 'Inactivo' }}
                                </span>
                                <p class="text-xs text-gray-500">El banner se mostrará solo si está activo</p>
                            </div>
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 border-t pt-6">
                        <router-link to="/administrador/banners" class="btn btn-outline-secondary" :disabled="bannerStore.loading">
                            Cancelar
                        </router-link>
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                            :disabled="bannerStore.loading"
                        >
                            <span v-if="bannerStore.loading" class="animate-spin border-2 border-white border-l-transparent rounded-full h-4 w-4 inline-block mr-2"></span>
                            <span>Actualizar Banner</span>
                        </button>
                    </div>
                </template>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useBannerStore } from '../../stores/banner';
import { SECCIONES_BANNER } from '../../types/banner';
import type { BannerFormData } from '../../types/banner';

const route = useRoute();
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

// Obtener ID del banner de la ruta
const bannerId = parseInt(route.params.id as string);

// Cargar banner al montar
onMounted(async () => {
    try {
        await bannerStore.fetchBanner(bannerId);
        
        // Inicializar formData con los datos del banner
        if (bannerStore.banner) {
            formData.value = {
                seccion: bannerStore.banner.seccion,
                titulo: bannerStore.banner.titulo || '',
                subtitulo: bannerStore.banner.subtitulo || '',
                imagen: null,
                url_destino: bannerStore.banner.url_destino || '',
                estado: bannerStore.banner.estado,
            };
        }
    } catch (error) {
        console.error('Error al cargar banner:', error);
        router.push('/administrador/banners');
    }
});

// Obtener URL completa de la imagen
const getImageUrl = (path: string) => {
    if (!path) return '/assets/images/placeholder.png';
    if (path.startsWith('http')) return path;
    return `/storage/${path}`;
};

// Manejar cambio de imagen
const handleImageChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validar tamaño (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('La imagen no debe superar los 5MB');
            return;
        }
        
        // Validar tipo
        if (!file.type.startsWith('image/')) {
            alert('Por favor, selecciona una imagen válida');
            return;
        }
        
        formData.value.imagen = file;
        
        // Crear vista previa
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

// Remover imagen
const removeImage = () => {
    formData.value.imagen = null;
    imagePreview.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

// Enviar formulario
const submitForm = async () => {
    if (!formData.value.seccion) {
        alert('Por favor, selecciona una sección');
        return;
    }
    
    try {
        const formDataToSend = new FormData();
        formDataToSend.append('seccion', formData.value.seccion);
        formDataToSend.append('titulo', formData.value.titulo || '');
        formDataToSend.append('subtitulo', formData.value.subtitulo || '');
        if (formData.value.imagen) {
            formDataToSend.append('imagen', formData.value.imagen);
        }
        formDataToSend.append('url_destino', formData.value.url_destino || '');
        formDataToSend.append('estado', formData.value.estado ? '1' : '0');
        
        await bannerStore.updateBanner(bannerId, formDataToSend);
        
        successMessage.value = '¡Banner actualizado exitosamente!';
        
        // Redirigir después de 2 segundos
        setTimeout(() => {
            router.push('/administrador/banners');
        }, 2000);
    } catch (error) {
        console.error('Error al actualizar banner:', error);
    }
};
</script>