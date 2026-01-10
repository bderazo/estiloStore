<template>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <router-link to="/" class="text-primary hover:underline">Inicio</router-link>
            </li>
            <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                <span>Nosotros</span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Sección Nosotros</h5>
                    <span class="text-sm text-gray-500">Gestiona el contenido público de la página de Nosotros.</span>
                </div>

                <div v-if="nosotrosStore.loading" class="py-16 text-center">
                    <div class="inline-block h-10 w-10 animate-spin rounded-full border-2 border-current border-t-transparent"></div>
                    <p class="mt-3 text-gray-600 dark:text-gray-400">Cargando información...</p>
                </div>

                <div v-else>
                    <NosotrosForm
                        :initial-data="nosotrosStore.record"
                        :saving="nosotrosStore.saving"
                        :disabled="isReadonly"
                        @submit="handleSubmit"
                    />
                    <p v-if="isReadonly" class="mt-4 text-sm text-amber-600 dark:text-amber-400">
                        Solo tienes permisos de lectura. Contacta a un administrador para gestionar esta sección.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue';
import Swal from 'sweetalert2';
import NosotrosForm from './Form.vue';
import { useNosotrosStore } from '../../stores/nosotrosStore';
import { useAuthStore } from '../../stores/auth';

const nosotrosStore = useNosotrosStore();
const authStore = useAuthStore();

const canManage = computed(() => authStore.hasPermission('manage nosotros'));
const isReadonly = computed(() => !canManage.value);

const fetchData = async () => {
    try {
        await nosotrosStore.fetchNosotros();
    } catch (error) {
        Swal.fire({
            title: 'Error',
            text: 'No se pudo cargar la información de la sección Nosotros.',
            icon: 'error',
            confirmButtonText: 'Cerrar',
        });
    }
};

const handleSubmit = async (formData: FormData) => {
    if (isReadonly.value) {
        return;
    }

    try {
        await nosotrosStore.saveNosotros(formData);
        Swal.fire({
            title: 'Actualizado',
            text: 'La información se guardó correctamente.',
            icon: 'success',
            confirmButtonText: 'Aceptar',
        });
    } catch (error: any) {
        const message = error?.response?.data?.message || 'Ocurrió un error al guardar la información.';
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonText: 'Cerrar',
        });
    }
};

onMounted(fetchData);
</script>
