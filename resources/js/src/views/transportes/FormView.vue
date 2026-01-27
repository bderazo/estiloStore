<!-- src/views/transportes/FormView.vue -->

<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        <i class="fas fa-bus mr-2"></i>{{ isEditing ? 'Editar Transporte' : 'Nuevo Transporte' }}
                    </h1>
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 text-sm text-gray-600 dark:text-gray-400">
                            <li class="inline-flex items-center">
                                <router-link 
                                    to="/administrador/transportes"
                                    class="inline-flex items-center hover:text-gray-900 dark:hover:text-white transition"
                                >
                                    <i class="fas fa-bus-alt mr-2"></i>
                                    Transportes
                                </router-link>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right mx-2"></i>
                                    <span>{{ isEditing ? 'Editar' : 'Crear' }} Transporte</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <router-link
                    to="/administrador/transportes"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </router-link>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Formulario -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="p-6">
                            <!-- Mensajes de Error -->
                            <div v-if="Object.keys(errors).length > 0" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-red-700 dark:text-red-400">
                                            Por favor corrige los siguientes errores:
                                        </p>
                                        <ul class="mt-2 text-sm text-red-600 dark:text-red-300 list-disc list-inside">
                                            <li v-for="(errorList, field) in errors" :key="field">
                                                {{ errorList[0] }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <form @submit.prevent="submitForm">
                                <div class="space-y-6">
                                    <!-- Ruta -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Ruta <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                                </svg>
                                            </div>
                                            <input 
                                                type="text" 
                                                v-model="formData.ruta"
                                                :class="[
                                                    'w-full pl-10 pr-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                                    errors.ruta ? 'border-red-300 dark:border-red-700' : ''
                                                ]"
                                                placeholder="Ej: GUAYAQUIL - QUITO"
                                                required
                                                list="rutas-sugeridas"
                                            >
                                            <datalist id="rutas-sugeridas">
                                                <option v-for="ruta in rutasUnicas" :value="ruta" :key="ruta" />
                                            </datalist>
                                        </div>
                                        <div v-if="errors.ruta" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ errors.ruta[0] }}
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Ingrese el nombre de la ruta
                                        </p>
                                    </div>

                                    <!-- Cooperativa -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Cooperativa <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                                </svg>
                                            </div>
                                            <input 
                                                type="text" 
                                                v-model="formData.cooperativa"
                                                :class="[
                                                    'w-full pl-10 pr-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                                    errors.cooperativa ? 'border-red-300 dark:border-red-700' : ''
                                                ]"
                                                placeholder="Ej: ESPEJO"
                                                required
                                                list="cooperativas-sugeridas"
                                            >
                                            <datalist id="cooperativas-sugeridas">
                                                <option v-for="coop in cooperativas" :value="coop" :key="coop" />
                                            </datalist>
                                        </div>
                                        <div v-if="errors.cooperativa" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ errors.cooperativa[0] }}
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Nombre de la cooperativa de transporte
                                        </p>
                                    </div>

                                    <!-- Precio y Tiempo Estimado -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Precio -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Precio (USD) <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500">$</span>
                                                </div>
                                                <input 
                                                    type="number" 
                                                    v-model="formData.precio"
                                                    :class="[
                                                        'w-full pl-10 pr-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                                        errors.precio ? 'border-red-300 dark:border-red-700' : ''
                                                    ]"
                                                    step="0.01"
                                                    min="0"
                                                    max="1000"
                                                    placeholder="0.00"
                                                    required
                                                >
                                            </div>
                                            <div v-if="errors.precio" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                                {{ errors.precio[0] }}
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Precio por pasaje (0 - 1000 USD)
                                            </p>
                                        </div>

                                        <!-- Tiempo Estimado -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Tiempo Estimado (horas)
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <input 
                                                    type="number" 
                                                    v-model="formData.tiempo_estimado"
                                                    :class="[
                                                        'w-full pl-10 pr-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                                        errors.tiempo_estimado ? 'border-red-300 dark:border-red-700' : ''
                                                    ]"
                                                    step="0.5"
                                                    min="0"
                                                    max="72"
                                                    placeholder="Ej: 3.5"
                                                >
                                            </div>
                                            <div v-if="errors.tiempo_estimado" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                                {{ errors.tiempo_estimado[0] }}
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Tiempo aproximado del viaje (0 - 72 horas)
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Estado -->
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    Estado del Transporte
                                                </label>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Las rutas inactivas no estarán disponibles para los clientes.
                                                </p>
                                            </div>
                                            <button
                                                type="button"
                                                @click="formData.estado = !formData.estado"
                                                :class="[
                                                    'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                                                    formData.estado ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'
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
                                                {{ formData.estado ? 'Esta ruta estará disponible para los clientes.' : 'Esta ruta estará oculta para los clientes.' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Botones -->
                                    <div class="flex items-center justify-end space-x-4 pt-6 border-t dark:border-gray-700">
                                        <router-link
                                            to="/administrador/transportes"
                                            class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition"
                                        >
                                            Cancelar
                                        </router-link>
                                        <button
                                            type="submit"
                                            :disabled="loading || !isFormValid"
                                            :class="[
                                                'px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition',
                                                (loading || !isFormValid) 
                                                    ? 'opacity-50 cursor-not-allowed' 
                                                    : ''
                                            ]"
                                        >
                                            <span v-if="loading" class="flex items-center">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                {{ isEditing ? 'Actualizando...' : 'Creando...' }}
                                            </span>
                                            <span v-else class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ isEditing ? 'Actualizar Transporte' : 'Crear Transporte' }}
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel Derecho -->
                <div class="space-y-6">
                    <!-- Vista Previa -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                Vista Previa
                            </h3>
                            
                            <div class="transporte-preview">
                                <div v-if="formData.ruta || formData.cooperativa || formData.precio" class="space-y-4">
                                    <!-- Ruta -->
                                    <div class="p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border-l-4 border-blue-500">
                                        <div class="flex items-center">
                                            <svg class="h-8 w-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ruta</p>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ formData.ruta || 'No especificado' }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cooperativa -->
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="h-8 w-8 text-gray-500 dark:text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Cooperativa</p>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ formData.cooperativa || 'No especificado' }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Precio y Tiempo -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Precio -->
                                        <div class="p-4 bg-green-50 dark:bg-green-900/10 rounded-lg">
                                            <div class="text-center">
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Precio</p>
                                                <h4 class="text-xl font-bold text-green-600 dark:text-green-400">
                                                    {{ formatPrice(formData.precio) }}
                                                </h4>
                                            </div>
                                        </div>

                                        <!-- Tiempo Estimado -->
                                        <div v-if="formData.tiempo_estimado" class="p-4 bg-yellow-50 dark:bg-yellow-900/10 rounded-lg">
                                            <div class="text-center">
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tiempo</p>
                                                <h4 class="text-xl font-bold text-yellow-600 dark:text-yellow-400">
                                                    {{ formData.tiempo_estimado }}h
                                                </h4>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Estado -->
                                    <div class="pt-4 border-t dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Estado:</span>
                                            <span 
                                                class="px-2 py-1 text-xs font-medium rounded-full"
                                                :class="formData.estado 
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'"
                                            >
                                                {{ formData.estado ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Complete el formulario para ver la vista previa
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recomendaciones -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-lightbulb mr-2"></i>Recomendaciones
                            </h3>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Use el formato: CIUDAD ORIGEN - CIUDAD DESTINO
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Mantenga consistencia en los nombres de las cooperativas
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Incluya el tiempo estimado para mejor experiencia del usuario
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Revise que el precio sea competitivo y acorde al mercado
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTransporteStore } from '../../stores/transporteStore';
import type { TransporteFormData } from '../../types/transporte';

const route = useRoute();
const router = useRouter();
const transporteStore = useTransporteStore();

const isEditing = computed(() => route.name === 'transportes.editar');
const transporteId = computed(() => parseInt(route.params.id as string));

const loading = ref(false);
const errors = ref<Record<string, string[]>>({});

const formData = ref<TransporteFormData>({
    ruta: '',
    precio: '',
    cooperativa: '',
    estado: true,
    tiempo_estimado: null
});

const cooperativas = computed(() => transporteStore.cooperativas);
const rutasUnicas = computed(() => transporteStore.rutasUnicas);

// Computed para validación similar al formulario de métodos de pago
const isFormValid = computed(() => {
    return formData.value.ruta.trim() !== '' && 
           formData.value.precio !== '' && 
           parseFloat(formData.value.precio.toString()) > 0 &&
           formData.value.cooperativa.trim() !== '';
});

const formatPrice = (price: number | string) => {
    if (!price) return '$0.00';
    const num = typeof price === 'string' ? parseFloat(price) : price;
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    }).format(num);
};

onMounted(async () => {
    await transporteStore.fetchCooperativas();
    
    if (isEditing.value) {
        await loadTransporte();
    }
});

const loadTransporte = async () => {
    try {
        loading.value = true;
        const transporte = await transporteStore.fetchTransporte(transporteId.value);
        
        if (transporte) {
            formData.value = {
                ruta: transporte.ruta,
                precio: transporte.precio,
                cooperativa: transporte.cooperativa,
                estado: transporte.estado,
                tiempo_estimado: transporte.tiempo_estimado || null
            };
        }
    } catch (error) {
        router.push('/administrador/transportes');
    } finally {
        loading.value = false;
    }
};

const submitForm = async () => {
    loading.value = true;
    errors.value = {};
    
    try {
        if (isEditing.value) {
            await transporteStore.updateTransporte(transporteId.value, formData.value);
        } else {
            await transporteStore.createTransporte(formData.value);
        }
        
        router.push('/administrador/transportes');
    } catch (error: any) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        }
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
.transporte-preview {
    min-height: 300px;
}

/* Animaciones y transiciones suaves */
input, select, textarea {
    transition: all 0.2s ease-in-out;
}

/* Mejora de contraste en modo oscuro */
@media (prefers-color-scheme: dark) {
    input:focus, select:focus, textarea:focus {
        background-color: #374151;
    }
}
</style>