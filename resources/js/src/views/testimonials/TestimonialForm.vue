<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold">
                        {{ testimonial ? 'Editar' : 'Nuevo' }} Testimonio
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
                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.nombre"
                            type="text"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Ana López"
                        >
                    </div>

                    <!-- Cargo -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Cargo (opcional)</label>
                        <input
                            v-model="form.cargo"
                            type="text"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Cliente verificada, Emprendedora, etc."
                        >
                    </div>

                    <!-- Testimonio -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Testimonio <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="form.testimonio"
                            required
                            rows="4"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Escribe el testimonio..."
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ form.testimonio.length }}/500 caracteres
                        </p>
                    </div>

                    <!-- Rating y Orden en grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Calificación <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.rating"
                                required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                                <option v-for="r in RATINGS" :key="r.value" :value="r.value">
                                    {{ r.label }}
                                </option>
                            </select>
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
                    </div>

                    <!-- Imagen -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Imagen del cliente</label>
                        
                        <!-- Vista previa -->
                        <div v-if="imagePreview || testimonial?.imagen_url" class="mb-4">
                            <div class="relative w-32 h-32 rounded-lg overflow-hidden border">
                                <img
                                    :src="imagePreview || testimonial?.imagen_url"
                                    alt="Preview"
                                    class="w-full h-full object-cover"
                                    @error="handleImageError"
                                >
                                <button
                                    type="button"
                                    @click="removeImage"
                                    class="absolute top-1 right-1 p-1 bg-red-600 text-white rounded-full hover:bg-red-700"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Upload -->
                        <div
                            @click="triggerFileInput"
                            @dragover.prevent="handleDragOver"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop"
                            :class="[
                                'border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition',
                                isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'
                            ]"
                        >
                            <input
                                ref="fileInput"
                                type="file"
                                accept="image/*"
                                @change="handleFileChange"
                                class="hidden"
                            >
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">
                                Haz clic o arrastra una imagen aquí
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                PNG, JPG, GIF hasta 2MB
                            </p>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium">Estado del testimonio</p>
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
                            <span v-else>
                                {{ testimonial ? 'Actualizar' : 'Guardar' }}
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
import { useTestimonialStore } from '../../../src/stores/testimonialStore';
import { RATINGS } from '../../../src/types/testimonial';
import type { Testimonial } from '../../../src/types/testimonial';
import Swal from 'sweetalert2';

const props = defineProps<{
    testimonial?: Testimonial | null;
}>();

const emit = defineEmits(['close', 'saved']);

const testimonialStore = useTestimonialStore();
const fileInput = ref<HTMLInputElement | null>(null);
const isDragging = ref(false);
const loading = ref(false);
const error = ref<string | null>(null);
const imagePreview = ref<string | null>(null);

const form = ref({
    nombre: '',
    cargo: '',
    testimonio: '',
    rating: 5,
    orden: 0,
    activo: true,
    imagen: null as File | null
});

onMounted(() => {
    if (props.testimonial) {
        form.value = {
            nombre: props.testimonial.nombre,
            cargo: props.testimonial.cargo || '',
            testimonio: props.testimonial.testimonio,
            rating: props.testimonial.rating,
            orden: props.testimonial.orden,
            activo: props.testimonial.activo,
            imagen: null
        };
    }
});

// 👇 NUEVO: Método para cerrar modal
const closeModal = () => {
    emit('close');
};

// 👇 NUEVO: Manejar error de imagen
const handleImageError = (e: Event) => {
    const img = e.target as HTMLImageElement;
    img.src = '/assets/images/placeholder.png';
};

const triggerFileInput = () => {
    fileInput.value?.click();
};

const handleFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
        validateAndSetImage(input.files[0]);
    }
};

const handleDragOver = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = true;
};

const handleDrop = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = false;
    
    if (event.dataTransfer?.files && event.dataTransfer.files[0]) {
        validateAndSetImage(event.dataTransfer.files[0]);
    }
};

const validateAndSetImage = (file: File) => {
    // Validar tipo
    if (!file.type.startsWith('image/')) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El archivo debe ser una imagen'
        });
        return;
    }
    
    // Validar tamaño (2MB)
    if (file.size > 2 * 1024 * 1024) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La imagen no debe pesar más de 2MB'
        });
        return;
    }
    
    form.value.imagen = file;
    
    // Crear preview
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
};

const removeImage = () => {
    form.value.imagen = null;
    imagePreview.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const submitForm = async () => {
    loading.value = true;
    error.value = null;
    
    try {
        const formData = new FormData();
        formData.append('nombre', form.value.nombre);
        if (form.value.cargo) formData.append('cargo', form.value.cargo);
        formData.append('testimonio', form.value.testimonio);
        formData.append('rating', form.value.rating.toString());
        formData.append('orden', form.value.orden.toString());
        formData.append('activo', form.value.activo ? '1' : '0');
        
        if (form.value.imagen) {
            formData.append('imagen', form.value.imagen);
        }
        
        if (props.testimonial) {
            await testimonialStore.updateTestimonial(props.testimonial.id, formData);
        } else {
            await testimonialStore.createTestimonial(formData);
        }
        
        // Mostrar mensaje de éxito
        await Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: `Testimonio ${props.testimonial ? 'actualizado' : 'creado'} exitosamente`,
            timer: 1500,
            showConfirmButton: false
        });
        
        // 👇 IMPORTANTE: Emitir saved Y cerrar modal
        emit('saved');
        closeModal(); // 👈 Cerrar el modal después de guardar
        
    } catch (err: any) {
        error.value = err.response?.data?.message || 'Error al guardar testimonio';
        console.error('Error:', err);
    } finally {
        loading.value = false;
    }
};
</script>