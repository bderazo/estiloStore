<template>
  <Teleport to="body">
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="mx-auto w-full max-w-md rounded-lg bg-white shadow-lg dark:bg-gray-800">
        <!-- Encabezado -->
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            {{ medida ? 'Editar Medida' : 'Nueva Medida' }}
          </h2>
        </div>

        <!-- Contenido -->
        <form @submit.prevent="handleSubmit" class="space-y-4 p-6">
          <!-- Campo: Nombre -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
              Nombre
              <span class="text-red-500">*</span>
            </label>
            <input
              v-model="formData.nombre"
              type="text"
              placeholder="Ej: Centímetros, Metros, Pulgadas..."
              class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
              @blur="validateField('nombre')"
            />
            <p v-if="errors.nombre" class="mt-1 text-xs text-red-600 dark:text-red-400">
              {{ errors.nombre }}
            </p>
          </div>

          <!-- Campo: Estado -->
          <div>
            <label class="mb-2 flex items-center gap-2">
              <input
                v-model="formData.activo"
                type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
              />
              <span class="text-sm font-medium text-gray-900 dark:text-white">Activo</span>
            </label>
          </div>

          <!-- Botones de acción -->
          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="handleClose"
              class="flex-1 rounded-lg border border-gray-300 px-4 py-2 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="flex-1 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
            >
              {{ isSubmitting ? 'Guardando...' : medida ? 'Actualizar' : 'Crear' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import type { Medida, MedidaForm } from '@/types/medida';

interface Props {
  medida?: Medida | null;
}

interface Emits {
  (e: 'close'): void;
  (e: 'submit', data: MedidaForm): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const isSubmitting = ref(false);
const errors = reactive<Record<string, string>>({});

const formData = reactive<MedidaForm>({
  nombre: props.medida?.nombre || '',
  activo: props.medida?.activo ?? true,
});

const validateField = (field: string) => {
  delete errors[field];

  if (field === 'nombre') {
    if (!formData.nombre.trim()) {
      errors.nombre = 'El nombre es requerido';
    } else if (formData.nombre.length < 2) {
      errors.nombre = 'El nombre debe tener al menos 2 caracteres';
    } else if (formData.nombre.length > 100) {
      errors.nombre = 'El nombre no puede exceder 100 caracteres';
    }
  }
};

const validateForm = () => {
  errors.nombre = '';
  validateField('nombre');
  return Object.keys(errors).length === 0;
};

const handleSubmit = async () => {
  if (!validateForm()) {
    return;
  }

  isSubmitting.value = true;
  try {
    emit('submit', {
      nombre: formData.nombre.trim(),
      activo: formData.activo,
    });
  } finally {
    isSubmitting.value = false;
  }
};

const handleClose = () => {
  emit('close');
};
</script>
