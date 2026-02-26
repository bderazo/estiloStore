<template>
    <div class="p-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Configuración de Testimonios
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Personaliza cómo se muestran los testimonios en el sitio
            </p>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        <!-- Formulario -->
        <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow max-w-2xl">
            <form @submit.prevent="saveConfig" class="p-6 space-y-6">
                <!-- Título -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Título de la sección
                    </label>
                    <input
                        v-model="form.titulo"
                        type="text"
                        required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                        placeholder="Ej: Testimonios de Clientes"
                    >
                </div>

                <!-- Subtítulo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Subtítulo (opcional)
                    </label>
                    <input
                        v-model="form.subtitulo"
                        type="text"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                        placeholder="Ej: Lo que dicen nuestras clientas"
                    >
                </div>

                <!-- Configuraciones del carrusel -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Configuración del Carrusel</h3>
                    
                    <!-- Autoplay -->
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Reproducción automática
                        </label>
                        <button
                            type="button"
                            @click="form.autoplay = !form.autoplay"
                            :class="[
                                'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none',
                                form.autoplay ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'
                            ]"
                        >
                            <span
                                :class="[
                                    'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                    form.autoplay ? 'translate-x-5' : 'translate-x-0'
                                ]"
                            />
                        </button>
                    </div>

                    <!-- Velocidad del autoplay -->
                    <div v-if="form.autoplay">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Velocidad de autoplay (ms)
                        </label>
                        <input
                            v-model.number="form.autoplay_speed"
                            type="number"
                            min="1000"
                            max="10000"
                            step="1000"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                        >
                        <p class="mt-1 text-sm text-gray-500">
                            Tiempo entre cada transición (1000ms = 1 segundo)
                        </p>
                    </div>

                    <!-- Mostrar controles -->
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Mostrar botones de navegación
                        </label>
                        <button
                            type="button"
                            @click="form.mostrar_controles = !form.mostrar_controles"
                            :class="[
                                'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none',
                                form.mostrar_controles ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'
                            ]"
                        >
                            <span
                                :class="[
                                    'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                    form.mostrar_controles ? 'translate-x-5' : 'translate-x-0'
                                ]"
                            />
                        </button>
                    </div>

                    <!-- Mostrar indicadores -->
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Mostrar indicadores (puntos)
                        </label>
                        <button
                            type="button"
                            @click="form.mostrar_indicadores = !form.mostrar_indicadores"
                            :class="[
                                'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none',
                                form.mostrar_indicadores ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'
                            ]"
                        >
                            <span
                                :class="[
                                    'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                    form.mostrar_indicadores ? 'translate-x-5' : 'translate-x-0'
                                ]"
                            />
                        </button>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 pt-6 border-t dark:border-gray-700">
                    <button
                        type="button"
                        @click="goBack"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        :disabled="saving"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    >
                        <span v-if="saving" class="flex items-center">
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
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useTestimonialStore } from '../../stores/testimonialStore';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';
import type { TestimonialConfig } from '../../types/testimonial';

const testimonialStore = useTestimonialStore();
const toast = useToast();
const router = useRouter();

const loading = ref<boolean>(false);
const saving = ref<boolean>(false);

// Estado local del formulario con tipo definido
const form = ref<TestimonialConfig>({
    titulo: '',
    subtitulo: '',
    autoplay: true,
    autoplay_speed: 5000,
    mostrar_controles: true,
    mostrar_indicadores: true
});

// Método para navegar hacia atrás
const goBack = () => {
    router.push('/administrador/testimonios');
};

// Cargar configuración
const loadConfig = async (): Promise<void> => {
    loading.value = true;
    try {
        // Si el store ya tiene config, usarla
        if (testimonialStore.config) {
            form.value = {
                titulo: testimonialStore.config.titulo || 'Testimonios de Clientes',
                subtitulo: testimonialStore.config.subtitulo || '',
                autoplay: testimonialStore.config.autoplay ?? true,
                autoplay_speed: testimonialStore.config.autoplay_speed || 5000,
                mostrar_controles: testimonialStore.config.mostrar_controles ?? true,
                mostrar_indicadores: testimonialStore.config.mostrar_indicadores ?? true
            };
        } else {
            // Si no, cargar del backend
            await testimonialStore.fetchConfig();
            if (testimonialStore.config) {
                form.value = {
                    titulo: testimonialStore.config.titulo || 'Testimonios de Clientes',
                    subtitulo: testimonialStore.config.subtitulo || '',
                    autoplay: testimonialStore.config.autoplay ?? true,
                    autoplay_speed: testimonialStore.config.autoplay_speed || 5000,
                    mostrar_controles: testimonialStore.config.mostrar_controles ?? true,
                    mostrar_indicadores: testimonialStore.config.mostrar_indicadores ?? true
                };
            }
        }
    } catch (error: any) {
        console.error('Error cargando configuración:', error);
        toast.error(error?.message || 'Error al cargar la configuración');
    } finally {
        loading.value = false;
    }
};

// Guardar configuración
const saveConfig = async (): Promise<void> => {
    saving.value = true;
    try {
        await testimonialStore.saveConfig(form.value);
        toast.success('Configuración guardada exitosamente');
        router.push('/administrador/testimonios');
    } catch (error: any) {
        console.error('Error guardando configuración:', error);
        toast.error(error?.message || 'Error al guardar la configuración');
    } finally {
        saving.value = false;
    }
};

onMounted(() => {
    loadConfig();
});
</script>