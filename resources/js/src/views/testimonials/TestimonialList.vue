<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    <svg class="w-6 h-6 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Testimonios
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Administra los testimonios de tus clientas
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
                    Nuevo Testimonio
                </button>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-semibold">{{ testimonialStore.testimonials.length }}</p>
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
                        <p class="text-2xl font-semibold">{{ testimoniosActivos.length }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Promedio</p>
                        <p class="text-2xl font-semibold">{{ promedioRating.toFixed(1) }} ⭐</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Último mes</p>
                        <p class="text-2xl font-semibold">{{ testimoniosUltimoMes }}</p>
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
                        placeholder="Buscar por nombre o testimonio..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <select
                    v-model="filters.rating"
                    class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Todos los ratings</option>
                    <option v-for="r in 5" :key="r" :value="r">{{ r }} Estrellas</option>
                </select>
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

        <!-- Testimonials Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orden</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Imagen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Testimonio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="testimonialStore.loading">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                                <p class="mt-2 text-gray-500">Cargando testimonios...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredTestimonials.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay testimonios</h3>
                                <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo testimonio.</p>
                            </td>
                        </tr>
                        <tr
                            v-for="testimonial in filteredTestimonials"
                            :key="testimonial.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <button
                                        @click="moveUp(testimonial)"
                                        :disabled="testimonial.orden === 0"
                                        class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-30"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                    <span class="mx-2">{{ testimonial.orden }}</span>
                                    <button
                                        @click="moveDown(testimonial)"
                                        :disabled="testimonial.orden === maxOrden"
                                        class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-30"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-12 w-12 rounded-lg overflow-hidden border">
                                    <img
                                        :src="getImageUrl(testimonial.imagen)"
                                        :alt="testimonial.nombre"
                                        class="h-full w-full object-cover"
                                    >
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ testimonial.nombre }}</div>
                                <div class="text-sm text-gray-500">{{ testimonial.cargo || 'Cliente' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700 line-clamp-2 max-w-xs">
                                    {{ testimonial.testimonio }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium mr-2">{{ testimonial.rating }}</span>
                                    <div class="flex text-yellow-400">
                                        <svg v-for="i in 5" :key="i" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path v-if="i <= testimonial.rating" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            <path v-else d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" fill="currentColor" fill-opacity="0.3" />
                                        </svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <button
                                    @click="toggleStatus(testimonial.id)"
                                    :class="[
                                        'px-2 py-1 text-xs font-medium rounded-full',
                                        testimonial.activo
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800'
                                    ]"
                                >
                                    {{ testimonial.activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button
                                        @click="editTestimonial(testimonial)"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="confirmDelete(testimonial)"
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
        <TestimonialForm
            v-if="showFormModal"
            :testimonial="selectedTestimonial"
            @close="closeFormModal"
            @saved="handleSaved"
        />

        <TestimonialConfig
            v-if="showConfigModal"
            :config="testimonialStore.config"
            @close="showConfigModal = false"
            @saved="handleConfigSaved"
        />

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium mb-4">Confirmar eliminación</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    ¿Estás seguro que deseas eliminar el testimonio de "{{ testimonialToDelete?.nombre }}"? Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-end space-x-3">
                    <button
                        @click="showDeleteModal = false"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteTestimonial"
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
import { useTestimonialStore } from '../../../src/stores/testimonialStore';
import TestimonialForm from './TestimonialForm.vue';
import TestimonialConfig from './TestimonialConfig.vue';
import type { Testimonial } from '../../../src/types/testimonial';
import Swal from 'sweetalert2';

const testimonialStore = useTestimonialStore();
const showFormModal = ref(false);
const showConfigModal = ref(false);
const showDeleteModal = ref(false);
const selectedTestimonial = ref<Testimonial | null>(null);
const testimonialToDelete = ref<Testimonial | null>(null);

const filters = ref({
    search: '',
    rating: '',
    estado: ''
});

const getImageUrl = (path: string) => {
    if (!path) return '/assets/images/placeholder.png';
    if (path.startsWith('http')) return path;
    return `/storage/${path}`;
};

// Computed
const testimoniosActivos = computed(() => 
    testimonialStore.testimonials.filter(t => t.activo)
);

const promedioRating = computed(() => {
    const total = testimonialStore.testimonials.reduce((sum, t) => sum + t.rating, 0);
    return testimonialStore.testimonials.length ? total / testimonialStore.testimonials.length : 0;
});

const testimoniosUltimoMes = computed(() => {
    const haceUnMes = new Date();
    haceUnMes.setMonth(haceUnMes.getMonth() - 1);
    
    return testimonialStore.testimonials.filter(t => 
        new Date(t.created_at) > haceUnMes
    ).length;
});

const filteredTestimonials = computed(() => {
    return testimonialStore.testimonials.filter(t => {
        const matchesSearch = filters.value.search === '' || 
            t.nombre.toLowerCase().includes(filters.value.search.toLowerCase()) ||
            t.testimonio.toLowerCase().includes(filters.value.search.toLowerCase());
        
        const matchesRating = filters.value.rating === '' || 
            t.rating === Number(filters.value.rating);
        
        const matchesEstado = filters.value.estado === '' || 
            t.activo === (filters.value.estado === '1');
        
        return matchesSearch && matchesRating && matchesEstado;
    });
});

const maxOrden = computed(() => 
    Math.max(...testimonialStore.testimonials.map(t => t.orden), 0)
);

// Methods
onMounted(async () => {
    await testimonialStore.fetchTestimonials();
});

const openCreateModal = () => {
    selectedTestimonial.value = null;
    showFormModal.value = true;
};

const openConfig = () => {
    showConfigModal.value = true;
};

const editTestimonial = (testimonial: Testimonial) => {
    selectedTestimonial.value = testimonial;
    showFormModal.value = true;
};

const closeFormModal = () => {
    showFormModal.value = false;
    selectedTestimonial.value = null;
};

const handleSaved = () => {
    closeFormModal();
};

const handleConfigSaved = () => {
    showConfigModal.value = false;
};

const toggleStatus = async (id: number) => {
    try {
        await testimonialStore.toggleStatus(id);
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

const confirmDelete = (testimonial: Testimonial) => {
    testimonialToDelete.value = testimonial;
    showDeleteModal.value = true;
};

const deleteTestimonial = async () => {
    if (!testimonialToDelete.value) return;
    
    try {
        await testimonialStore.deleteTestimonial(testimonialToDelete.value.id);
        showDeleteModal.value = false;
        testimonialToDelete.value = null;
        
        Swal.fire({
            icon: 'success',
            title: 'Eliminado',
            text: 'Testimonio eliminado exitosamente',
            timer: 2000
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo eliminar el testimonio'
        });
    }
};

const moveUp = (testimonial: Testimonial) => {
    if (testimonial.orden === 0) return;
    // Implementar lógica de reordenamiento
};

const moveDown = (testimonial: Testimonial) => {
    if (testimonial.orden === maxOrden.value) return;
    // Implementar lógica de reordenamiento
};

const resetFilters = () => {
    filters.value = {
        search: '',
        rating: '',
        estado: ''
    };
};
</script>