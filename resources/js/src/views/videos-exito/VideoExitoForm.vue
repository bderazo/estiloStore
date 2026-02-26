<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold">
                        {{ video ? 'Editar' : 'Nuevo' }} Video de Caso de Éxito
                    </h2>
                    <button
                        @click="closeModal"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Error Message -->
                <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-600">{{ error }}</p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- URL de YouTube -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            URL de YouTube <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.url_youtube"
                            type="url"
                            required
                            @input="extractYoutubeId"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="https://www.youtube.com/watch?v=..."
                        >
                        <div v-if="youtubeId" class="mt-2 p-2 bg-gray-50 rounded-lg">
                            <p class="text-sm">
                                <span class="font-medium">YouTube ID:</span> 
                                <code class="bg-gray-200 p-1 rounded">{{ youtubeId }}</code>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Preview: https://img.youtube.com/vi/{{ youtubeId }}/maxresdefault.jpg
                            </p>
                        </div>
                    </div>

                    <!-- Título -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Título del Video <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.titulo"
                            type="text"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Cómo pasé de cliente a distribuidora"
                        >
                    </div>

                    <!-- Subtítulo -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Subtítulo (opcional)</label>
                        <input
                            v-model="form.subtitulo"
                            type="text"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Mi historia de éxito"
                        >
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Descripción (opcional)</label>
                        <textarea
                            v-model="form.descripcion"
                            rows="3"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Breve descripción del video..."
                        ></textarea>
                    </div>

                    <!-- Nombre de la persona -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Nombre de la persona <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.nombre_persona"
                            type="text"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: María González"
                        >
                    </div>

                    <!-- Cargo de la persona -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Cargo (opcional)</label>
                        <input
                            v-model="form.cargo_persona"
                            type="text"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Distribuidora Diamante"
                        >
                    </div>

                    <!-- Orden -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Orden de aparición <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model.number="form.orden"
                            type="number"
                            min="0"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <!-- Estado -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium">Estado del video</p>
                            <p class="text-sm text-gray-500">
                                {{ form.activo ? 'Visible en el sitio web' : 'Oculto en el sitio web' }}
                            </p>
                        </div>
                        <button
                            type="button"
                            @click="form.activo = !form.activo"
                            :class="[
                                'relative inline-flex h-6 w-11 items-center rounded-full transition',
                                form.activo ? 'bg-blue-600' : 'bg-gray-300'
                            ]"
                        >
                            <span
                                :class="[
                                    'inline-block h-4 w-4 transform rounded-full bg-white transition',
                                    form.activo ? 'translate-x-6' : 'translate-x-1'
                                ]"
                            />
                        </button>
                    </div>

                    <!-- Preview del Thumbnail -->
                    <div v-if="youtubeId" class="border rounded-lg p-4">
                        <p class="font-medium mb-2">Preview del thumbnail:</p>
                        <img 
                            :src="`https://img.youtube.com/vi/${youtubeId}/maxresdefault.jpg`"
                            alt="Thumbnail preview"
                            class="w-full rounded-lg border"
                            @error="handleImageError"
                        >
                        <p class="text-xs text-gray-500 mt-2">
                            * YouTube genera automáticamente el thumbnail. Si no ves la imagen, puede que el video no tenga maxresdefault.
                        </p>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-50"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="loading || !youtubeId"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                        >
                            <span v-if="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Guardando...
                            </span>
                            <span v-else>
                                {{ video ? 'Actualizar' : 'Guardar' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useVideoExitoStore } from '../../../src/stores/videoExitoStore';
import type { VideoExito } from '../../../src/types/videoExito';
import Swal from 'sweetalert2';

const props = defineProps<{
    video?: VideoExito | null;
}>();

const emit = defineEmits(['close', 'saved']);

const videoStore = useVideoExitoStore();
const loading = ref(false);
const error = ref<string | null>(null);
const youtubeId = ref('');

const form = ref({
    titulo: '',
    subtitulo: '',
    descripcion: '',
    url_youtube: '',
    nombre_persona: '',
    cargo_persona: '',
    orden: 0,
    activo: true
});

onMounted(() => {
    if (props.video) {
        form.value = {
            titulo: props.video.titulo,
            subtitulo: props.video.subtitulo || '',
            descripcion: props.video.descripcion || '',
            url_youtube: props.video.url_youtube,
            nombre_persona: props.video.nombre_persona,
            cargo_persona: props.video.cargo_persona || '',
            orden: props.video.orden,
            activo: props.video.activo
        };
        youtubeId.value = props.video.youtube_id;
    }
});

const closeModal = () => {
    emit('close');
};

const extractYoutubeId = () => {
    const url = form.value.url_youtube;
    const match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
    youtubeId.value = match ? match[1] : '';
};

const handleImageError = (e: Event) => {
    const img = e.target as HTMLImageElement;
    img.src = `https://img.youtube.com/vi/${youtubeId.value}/hqdefault.jpg`;
};

const submitForm = async () => {
    if (!youtubeId.value) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La URL de YouTube no es válida'
        });
        return;
    }
    
    loading.value = true;
    error.value = null;
    
    try {
        const formData = new FormData();
        formData.append('titulo', form.value.titulo);
        if (form.value.subtitulo) formData.append('subtitulo', form.value.subtitulo);
        if (form.value.descripcion) formData.append('descripcion', form.value.descripcion);
        formData.append('url_youtube', form.value.url_youtube);
        formData.append('youtube_id', youtubeId.value);
        formData.append('nombre_persona', form.value.nombre_persona);
        if (form.value.cargo_persona) formData.append('cargo_persona', form.value.cargo_persona);
        formData.append('orden', form.value.orden.toString());
        formData.append('activo', form.value.activo ? '1' : '0');
        
        if (props.video) {
            await videoStore.updateVideo(props.video.id, formData);
        } else {
            await videoStore.createVideo(formData);
        }
        
        // Mostrar mensaje de éxito
        await Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: `Video ${props.video ? 'actualizado' : 'creado'} exitosamente`,
            timer: 1500,
            showConfirmButton: false
        });
        
        // 👇 IMPORTANTE: Emitir saved Y cerrar modal
        emit('saved');
        closeModal(); // 👈 Cerrar el modal después de guardar
        
    } catch (err: any) {
        error.value = err.response?.data?.message || 'Error al guardar video';
        console.error('Error:', err);
    } finally {
        loading.value = false;
    }
};
</script>