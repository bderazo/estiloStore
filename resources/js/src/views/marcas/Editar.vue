<template>
    <div>
        <!-- Breadcrumb -->
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <router-link to="/" class="text-primary hover:underline">Inicio</router-link>
            </li>
            <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                <router-link to="/administrador/marcas" class="text-primary hover:underline">Marcas</router-link>
            </li>
            <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                <span>Editar</span>
            </li>
        </ul>

        <div class="pt-5">
            <!-- Loading state -->
            <div v-if="initialLoading" class="panel">
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Cargando marca...</p>
                </div>
            </div>

            <!-- Error state -->
            <div v-else-if="loadError" class="panel">
                <div class="text-center py-8">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 text-danger">
                        <path d="M12 9V14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M11.9998 21.0003C17.5227 21.0003 21.9998 16.5232 21.9998 11.0003C21.9998 5.47744 17.5227 1.00031 11.9998 1.00031C6.47693 1.00031 1.99979 5.47744 1.99979 11.0003C1.99979 16.5232 6.47693 21.0003 11.9998 21.0003Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M12 17H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ loadError }}</p>
                    <router-link to="/administrador/marcas" class="btn btn-primary">
                        Volver a Marcas
                    </router-link>
                </div>
            </div>

            <!-- Form -->
            <div v-else class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">
                        Editar Marca: {{ marcaOriginal?.nombre }}
                    </h5>
                    <router-link
                        to="/administrador/marcas"
                        class="btn btn-outline-danger"
                    >
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <path d="M20 12H4M4 12L10 6M4 12L10 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Volver
                    </router-link>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Información actual -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <h6 class="font-medium mb-2">Información Actual</h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Slug:</span>
                                <span class="ml-2 font-medium">{{ marcaOriginal?.slug }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Creado:</span>
                                <span class="ml-2 font-medium">{{ marcaOriginal?.formatted_created_at }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium mb-1">
                            Nombre de la Marca <span class="text-danger">*</span>
                        </label>
                        <input
                            id="nombre"
                            v-model="form.nombre"
                            type="text"
                            placeholder="Ingrese el nombre de la marca"
                            class="form-input"
                            :class="{ 'border-danger': errors.nombre }"
                            required
                            @input="handleInput('nombre')"
                        />
                        <div v-if="errors.nombre" class="text-danger text-sm mt-1">
                            {{ errors.nombre[0] }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            El slug se actualizará automáticamente si es necesario
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium mb-1">
                            Descripción
                        </label>
                        <textarea
                            id="descripcion"
                            v-model="form.descripcion"
                            rows="4"
                            placeholder="Descripción opcional de la marca"
                            class="form-textarea"
                            :class="{ 'border-danger': errors.descripcion }"
                            @input="handleInput('descripcion')"
                        ></textarea>
                        <div v-if="errors.descripcion" class="text-danger text-sm mt-1">
                            {{ errors.descripcion[0] }}
                        </div>
                    </div>

                    <!-- Estado Activo -->
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input
                                v-model="form.activo"
                                type="checkbox"
                                class="form-checkbox text-primary"
                            />
                            <span class="ml-2">Marca activa</span>
                        </label>
                        <div class="text-xs text-gray-500 mt-1">
                            Las marcas inactivas no aparecerán en los selectores del sistema
                        </div>
                    </div>

                    <!-- Indicador de cambios -->
                    <div v-if="hasChanges" class="bg-info/10 border border-info/20 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-info mr-2">
                                <path d="M12 9V14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M11.9998 21.0003C17.5227 21.0003 21.9998 16.5232 21.9998 11.0003C21.9998 5.47744 17.5227 1.00031 11.9998 1.00031C6.47693 1.00031 1.99979 5.47744 1.99979 11.0003C1.99979 16.5232 6.47693 21.0003 11.9998 21.0003Z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M12 17H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-info font-medium">Tienes cambios sin guardar</span>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <router-link
                            to="/administrador/marcas"
                            class="btn btn-outline-secondary"
                        >
                            Cancelar
                        </router-link>
                        <button
                            type="button"
                            @click="resetForm"
                            class="btn btn-outline-warning"
                            :disabled="!hasChanges"
                        >
                            Restablecer
                        </button>
                        <button
                            type="submit"
                            :disabled="loading || !hasChanges"
                            class="btn btn-primary"
                            :class="{ 'opacity-50 cursor-not-allowed': loading || !hasChanges }"
                        >
                            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ loading ? 'Guardando...' : 'Guardar Cambios' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useMarcaStore } from '../../stores/marcaStore';
import { UpdateMarcaRequest, Marca } from '../../types/marca';
import Swal from 'sweetalert2';

// Composables
const router = useRouter();
const route = useRoute();
const marcaStore = useMarcaStore();

// Refs reactivos
const initialLoading = ref(true);
const loading = ref(false);
const loadError = ref('');
const errors = ref<Record<string, string[]>>({});
const marcaOriginal = ref<Marca | null>(null);

// Formulario
const form = reactive<UpdateMarcaRequest>({
    nombre: '',
    descripcion: '',
    activo: true
});

// Computed
const hasChanges = computed(() => {
    if (!marcaOriginal.value) return false;

    return (
        form.nombre !== marcaOriginal.value.nombre ||
        form.descripcion !== (marcaOriginal.value.descripcion || '') ||
        form.activo !== marcaOriginal.value.activo
    );
});

// Métodos
const clearErrors = () => {
    errors.value = {};
};

const validateForm = () => {
    clearErrors();
    let isValid = true;

    if (!form.nombre?.trim()) {
        errors.value.nombre = ['El nombre es obligatorio'];
        isValid = false;
    } else if (form.nombre.trim().length < 2) {
        errors.value.nombre = ['El nombre debe tener al menos 2 caracteres'];
        isValid = false;
    } else if (form.nombre.trim().length > 255) {
        errors.value.nombre = ['El nombre no puede exceder 255 caracteres'];
        isValid = false;
    }

    if (form.descripcion && form.descripcion.length > 1000) {
        errors.value.descripcion = ['La descripción no puede exceder 1000 caracteres'];
        isValid = false;
    }

    return isValid;
};

const loadMarca = async () => {
    const marcaId = Number(route.params.id);

    if (!marcaId || isNaN(marcaId)) {
        loadError.value = 'ID de marca inválido';
        initialLoading.value = false;
        return;
    }

    try {
        const marca = await marcaStore.fetchMarca(marcaId);
        marcaOriginal.value = marca;

        // Llenar el formulario con los datos actuales
        form.nombre = marca.nombre;
        form.descripcion = marca.descripcion || '';
        form.activo = marca.activo;

    } catch (error: any) {
        console.error('Error al cargar marca:', error);
        loadError.value = error.response?.status === 404
            ? 'La marca solicitada no existe'
            : 'Error al cargar la marca';
    } finally {
        initialLoading.value = false;
    }
};

const resetForm = () => {
    if (marcaOriginal.value) {
        form.nombre = marcaOriginal.value.nombre;
        form.descripcion = marcaOriginal.value.descripcion || '';
        form.activo = marcaOriginal.value.activo;
        clearErrors();
    }
};

const submitForm = async () => {
    if (!validateForm() || !marcaOriginal.value) {
        return;
    }

    loading.value = true;
    clearErrors();

    try {
        const marcaActualizada = await marcaStore.updateMarca(marcaOriginal.value.id, form);

        await Swal.fire({
            title: '¡Éxito!',
            text: `La marca "${marcaActualizada.nombre}" ha sido actualizada correctamente.`,
            icon: 'success',
            confirmButtonText: 'Aceptar',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });

        // Actualizar los datos originales para el cálculo de cambios
        marcaOriginal.value = marcaActualizada;

        // Redirigir a la lista de marcas
        router.push('/administrador/marcas');
    } catch (error: any) {
        console.error('Error al actualizar marca:', error);

        if (error.response?.status === 422) {
            // Errores de validación del servidor
            errors.value = error.response.data.errors || {};
        } else if (error.response?.status === 404) {
            await Swal.fire({
                title: 'Error',
                text: 'La marca no existe o ha sido eliminada.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            router.push('/administrador/marcas');
        } else {
            // Otros errores
            await Swal.fire({
                title: 'Error',
                text: error.response?.data?.message || 'Error al actualizar la marca. Por favor, inténtalo de nuevo.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        }
    } finally {
        loading.value = false;
    }
};

const handleInput = (field: string) => {
    if (errors.value[field]) {
        delete errors.value[field];
    }
};

// Lifecycle
onMounted(() => {
    loadMarca();
});
</script>
