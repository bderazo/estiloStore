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
                <span>Crear</span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Crear Nueva Marca</h5>
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
                        />
                        <div v-if="errors.nombre" class="text-danger text-sm mt-1">
                            {{ errors.nombre[0] }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            El slug se generará automáticamente basado en el nombre
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

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <router-link
                            to="/administrador/marcas"
                            class="btn btn-outline-secondary"
                        >
                            Cancelar
                        </router-link>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="btn btn-primary"
                            :class="{ 'opacity-50 cursor-not-allowed': loading }"
                        >
                            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ loading ? 'Creando...' : 'Crear Marca' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useMarcaStore } from '../../stores/marcaStore';
import { CreateMarcaRequest } from '../../types/marca';
import Swal from 'sweetalert2';

// Composables
const router = useRouter();
const marcaStore = useMarcaStore();

// Refs reactivos
const loading = ref(false);
const errors = ref<Record<string, string[]>>({});

// Formulario
const form = reactive<CreateMarcaRequest>({
    nombre: '',
    descripcion: '',
    activo: true
});

// Métodos
const clearErrors = () => {
    errors.value = {};
};

const validateForm = () => {
    clearErrors();
    let isValid = true;

    if (!form.nombre.trim()) {
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

const submitForm = async () => {
    if (!validateForm()) {
        return;
    }

    loading.value = true;
    clearErrors();

    try {
        const nuevaMarca = await marcaStore.createMarca(form);

        await Swal.fire({
            title: '¡Éxito!',
            text: `La marca "${nuevaMarca.nombre}" ha sido creada correctamente.`,
            icon: 'success',
            confirmButtonText: 'Aceptar',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });

        // Redirigir a la lista de marcas
        router.push('/administrador/marcas');
    } catch (error: any) {
        console.error('Error al crear marca:', error);

        if (error.response?.status === 422) {
            // Errores de validación del servidor
            errors.value = error.response.data.errors || {};
        } else {
            // Otros errores
            await Swal.fire({
                title: 'Error',
                text: error.response?.data?.message || 'Error al crear la marca. Por favor, inténtalo de nuevo.',
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

// Limpiar errores cuando el usuario empiece a escribir
const handleInput = (field: string) => {
    if (errors.value[field]) {
        delete errors.value[field];
    }
};
</script>
