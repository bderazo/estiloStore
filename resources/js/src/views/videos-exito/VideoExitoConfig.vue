<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold">Configuración de Videos</h2>
                    <button
                        @click="$emit('close')"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitConfig" class="space-y-6">
                    <!-- Título -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Título de la sección</label>
                        <input
                            v-model="form.titulo"
                            type="text"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Casos de Éxito"
                        >
                    </div>

                    <!-- Subtítulo -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Subtítulo</label>
                        <input
                            v-model="form.subtitulo"
                            type="text"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Historias inspiradoras de nuestras clientas"
                        >
                    </div>

                    <!-- Videos por página -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Videos por página (carga inicial)
                        </label>
                        <input
                            v-model.number="form.videos_por_pagina"
                            type="number"
                            min="1"
                            max="50"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            Cantidad de videos a mostrar inicialmente
                        </p>
                    </div>

                    <!-- Mostrar botón "Cargar más" -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium">Mostrar botón "Cargar más"</p>
                            <p class="text-sm text-gray-500">Permite a los usuarios cargar más videos</p>
                        </div>
                        <button
                            type="button"
                            @click="form.mostrar_cargar_mas = !form.mostrar_cargar_mas"
                            :class="[
                                'relative inline-flex h-6 w-11 items-center rounded-full transition',
                                form.mostrar_cargar_mas ? 'bg-blue-600' : 'bg-gray-300'
                            ]"
                        >
                            <span
                                :class="[
                                    'inline-block h-4 w-4 transform rounded-full bg-white transition',
                                    form.mostrar_cargar_mas ? 'translate-x-6' : 'translate-x-1'
                                ]"
                            />
                        </button>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-50"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                        >
                            <span v-if="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Guardando...
                            </span>
                            <span v-else>Guardar configuración</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useVideoExitoStore } from '../../../src/stores/videoExitoStore';
import type { VideoExitoConfig } from '../../../src/types/videoExito';
import Swal from 'sweetalert2';

const props = defineProps<{
    config: VideoExitoConfig;
}>();

const emit = defineEmits(['close', 'saved']);

const videoStore = useVideoExitoStore();
const loading = ref(false);

const form = ref({
    titulo: props.config.titulo,
    subtitulo: props.config.subtitulo,
    videos_por_pagina: props.config.videos_por_pagina,
    mostrar_cargar_mas: props.config.mostrar_cargar_mas
});

const submitConfig = async () => {
    loading.value = true;
    
    try {
        await videoStore.saveConfig(form.value);
        
        Swal.fire({
            icon: 'success',
            title: 'Configuración guardada',
            timer: 2000,
            showConfirmButton: false
        });
        
        emit('saved');
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo guardar la configuración'
        });
    } finally {
        loading.value = false;
    }
};
</script>