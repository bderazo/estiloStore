<template>
  <div
    v-if="visible"
    class="fixed inset-0 z-[999] bg-[black]/60 flex items-center justify-center px-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white dark:bg-[#1b2e4b] rounded-lg w-full max-w-md">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b border-[#e0e6ed] dark:border-[#1b2e4b]">
        <h5 class="text-lg font-semibold text-[#3b3f5c] dark:text-white-light">
          {{ isEditing ? 'Editar Plaza' : 'Nueva Plaza' }}
        </h5>
        <button
          @click="$emit('close')"
          class="text-gray-500 hover:text-gray-700 dark:text-white-dark dark:hover:text-white"
        >
          <IconX class="w-5 h-5" />
        </button>
      </div>

      <!-- Body -->
      <form @submit.prevent="handleSubmit" class="p-6">
        <!-- Nombre -->
        <div class="mb-5">
          <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-white-light mb-2">
            Nombre <span class="text-red-500">*</span>
          </label>
          <input
            id="nombre"
            v-model="form.nombre"
            type="text"
            class="form-input"
            :class="{ 'border-red-500': errors.nombre }"
            placeholder="Ingrese el nombre de la plaza"
            required
            maxlength="100"
          />
          <div v-if="errors.nombre" class="text-red-500 text-sm mt-1">
            {{ errors.nombre[0] }}
          </div>
          <div class="text-xs text-gray-500 mt-1">
            {{ form.nombre?.length || 0 }}/100 caracteres
          </div>
        </div>

        <!-- Estado -->
        <div class="mb-6">
          <label class="flex items-center cursor-pointer">
            <input
              v-model="form.activo"
              type="checkbox"
              class="form-checkbox text-primary"
            />
            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-white-light">
              Activa
            </span>
          </label>
          <div class="text-xs text-gray-500 mt-1">
            {{ form.activo ? 'La plaza estará disponible' : 'La plaza estará oculta' }}
          </div>
        </div>

        <!-- Error general -->
        <div v-if="generalError" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
          {{ generalError }}
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3">
          <button
            type="button"
            @click="$emit('close')"
            class="btn btn-outline-secondary"
            :disabled="loading"
          >
            Cancelar
          </button>
          <button
            type="submit"
            class="btn btn-primary"
            :disabled="loading || !isFormValid"
          >
            <IconLoader v-if="loading" class="w-4 h-4 mr-2 animate-spin" />
            <IconCheck v-else class="w-4 h-4 mr-2" />
            {{ isEditing ? 'Actualizar' : 'Crear' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, nextTick } from 'vue';
import { storeToRefs } from 'pinia';
import { usePlazaStore } from '../../stores/plazaStore';
import type { Plaza, PlazaForm, CreatePlazaRequest, UpdatePlazaRequest } from '../../types/plaza';

// Icons
import { IconX, IconCheck, IconLoader } from '@tabler/icons-vue';

// Props y emits
interface Props {
  visible: boolean;
  plaza?: Plaza | null;
}

const props = withDefaults(defineProps<Props>(), {
  plaza: null,
});

const emit = defineEmits<{
  close: [];
  saved: [];
}>();

// Store
const plazaStore = usePlazaStore();
const { loading } = storeToRefs(plazaStore);

// Estado del formulario
const form = ref<PlazaForm>({
  nombre: '',
  activo: true,
});

const errors = ref<Record<string, string[]>>({});
const generalError = ref<string>('');

// Computed
const isEditing = computed(() => !!props.plaza);

const isFormValid = computed(() => {
  return form.value.nombre.trim().length > 0;
});

// Métodos
const resetForm = () => {
  form.value = {
    nombre: '',
    activo: true,
  };
  errors.value = {};
  generalError.value = '';
};

const loadPlazaData = () => {
  if (props.plaza) {
    form.value.nombre = props.plaza.nombre || '';
    form.value.activo = props.plaza.activo;
  }
};

// Watchers
watch(
  () => props.visible,
  (newValue) => {
    if (newValue) {
      resetForm();
      nextTick(() => {
        if (props.plaza) {
          loadPlazaData();
        }
        const nombreInput = document.getElementById('nombre') as HTMLInputElement;
        if (nombreInput) {
          nombreInput.focus();
        }
      });
    }
  }
);

// Watcher adicional para cuando cambia la plaza prop
watch(
  () => props.plaza,
  (newPlaza) => {
    if (newPlaza && props.visible) {
      loadPlazaData();
    }
  },
  { immediate: true }
);

watch(
  () => form.value.nombre,
  () => {
    if (errors.value.nombre) {
      delete errors.value.nombre;
    }
    generalError.value = '';
  }
);

const handleSubmit = async () => {
  try {
    errors.value = {};
    generalError.value = '';

    // Preparar datos para envío
    const formData: PlazaForm = {
      nombre: form.value.nombre.trim(),
      activo: form.value.activo,
    };

    if (isEditing.value && props.plaza) {
      await plazaStore.updatePlaza(props.plaza.id, formData);
    } else {
      await plazaStore.createPlaza(formData);
    }

    emit('saved');
  } catch (error: any) {
    console.error('Error al guardar plaza:', error);

    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else if (error.response?.data?.message) {
      generalError.value = error.response.data.message;
    } else {
      generalError.value = 'Ocurrió un error inesperado. Por favor intente nuevamente.';
    }
  }
};
</script>

<style scoped>
.form-input:focus,
.form-checkbox:focus {
  border-color: rgb(59 130 246);
  box-shadow: 0 0 0 3px rgb(59 130 246 / 0.1);
}

.form-input.border-red-500 {
  border-color: rgb(239 68 68);
}

.form-input.border-red-500:focus {
  border-color: rgb(239 68 68);
  box-shadow: 0 0 0 3px rgb(239 68 68 / 0.1);
}

/* Animación de entrada del modal */
.fixed {
  animation: fadeIn 0.2s ease-out;
}

.bg-white {
  animation: slideIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(-20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}
</style>
