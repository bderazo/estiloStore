<template>
  <div v-if="visible" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal"></div>

    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ isEditing ? 'Editar Color' : 'Nuevo Color' }}
          </h3>
          <button
            @click="closeModal"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
          >
            <IconX class="w-5 h-5" />
          </button>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleSubmit" class="p-6">
          <!-- Nombre -->
          <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Nombre del Color *
            </label>
            <input
              id="nombre"
              v-model="form.nombre"
              type="text"
              placeholder="Ej: Azul Marino"
              class="form-input w-full"
              :class="{ 'border-red-500': errors.nombre }"
              required
            />
            <p v-if="errors.nombre" class="text-red-500 text-xs mt-1">
              {{ errors.nombre[0] }}
            </p>
          </div>

          <!-- Código Hex -->
          <div class="mb-4">
            <label for="codigo_hex" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Código Hexadecimal *
            </label>
            <div class="flex gap-2">
              <!-- Input de color -->
              <input
                id="color_picker"
                v-model="form.codigo_hex"
                type="color"
                class="w-12 h-10 border border-gray-300 rounded cursor-pointer"
                @change="updateHexFromPicker"
              />
              <!-- Input de texto -->
              <input
                id="codigo_hex"
                v-model="form.codigo_hex"
                type="text"
                placeholder="#000000"
                class="form-input flex-1"
                :class="{ 'border-red-500': errors.codigo_hex }"
                @input="validateAndFormatHex"
                required
              />
            </div>
            <p v-if="errors.codigo_hex" class="text-red-500 text-xs mt-1">
              {{ errors.codigo_hex[0] }}
            </p>
            <p class="text-gray-500 text-xs mt-1">
              Formato válido: #000000 o #000
            </p>
          </div>

          <!-- Preview del color -->
          <div v-if="isValidHex" class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Vista Previa
            </label>
            <div class="flex items-center gap-4">
              <!-- Cuadro de color -->
              <div
                :style="{ backgroundColor: form.codigo_hex }"
                class="w-16 h-16 rounded-lg border border-gray-300 shadow-sm"
              ></div>

              <!-- Información del color -->
              <div class="flex-1">
                <div
                  :style="{
                    backgroundColor: form.codigo_hex,
                    color: contrastColor
                  }"
                  class="px-3 py-2 rounded text-sm font-medium mb-2"
                >
                  {{ form.nombre || 'Nombre del color' }}
                </div>
                <div class="text-xs text-gray-500">
                  <p>Código: {{ form.codigo_hex }}</p>
                  <p>Contraste: {{ contrastColor }}</p>
                  <p>{{ isDark ? 'Color oscuro' : 'Color claro' }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Estado activo -->
          <div class="mb-6">
            <label class="flex items-center">
              <input
                v-model="form.activo"
                type="checkbox"
                class="form-checkbox h-4 w-4 text-primary"
              />
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Color activo
              </span>
            </label>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-3">
            <button
              type="button"
              @click="closeModal"
              class="btn btn-outline-primary"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="loading || !isValidForm"
              class="btn btn-primary"
            >
              <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2 inline-block"></span>
              {{ isEditing ? 'Actualizar' : 'Crear' }}
            </button>
          </div>

          <!-- Debug info (temporal) -->
          <div class="mt-2 text-xs text-gray-500" v-if="false">
            Valid form: {{ isValidForm }} |
            Valid hex: {{ isValidHex }} |
            Name: {{ form.nombre.trim() !== '' }} |
            Loading: {{ loading }}
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { useColorStore } from '../../stores/colorStore';
import { colorService } from '../../services/colorService';
import type { Color, ColorForm, ColorValidationError } from '../../types/color';

// Icons
import { IconX } from '@tabler/icons-vue';

// Props
interface Props {
  visible: boolean;
  color?: Color | null;
}

const props = withDefaults(defineProps<Props>(), {
  color: null,
});

// Emits
const emit = defineEmits<{
  close: [];
  saved: [];
}>();

// Store
const colorStore = useColorStore();

// Estado local
const loading = ref(false);
const errors = ref<ColorValidationError>({});

// Formulario
const form = ref<ColorForm>({
  nombre: '',
  codigo_hex: '#000000',
  activo: true,
});

// Computeds
const isEditing = computed(() => !!props.color);

const isValidHex = computed(() => {
  return colorService.validateHexColor(form.value.codigo_hex);
});

const contrastColor = computed(() => {
  if (!isValidHex.value) return '#000000';
  return colorService.getContrastColor(form.value.codigo_hex);
});

const isDark = computed(() => {
  if (!isValidHex.value) return false;
  return colorService.isDarkColor(form.value.codigo_hex);
});

const isValidForm = computed(() => {
  return form.value.nombre.trim() !== '' &&
         isValidHex.value &&
         !loading.value;
});

// Métodos
const resetForm = () => {
  form.value = {
    nombre: '',
    codigo_hex: '#000000',
    activo: true,
  };
  errors.value = {};
};

const loadColorData = () => {
  if (props.color) {
    form.value = {
      nombre: props.color.nombre,
      codigo_hex: props.color.codigo_hex,
      activo: props.color.activo,
    };
  } else {
    resetForm();
  }
};

const validateAndFormatHex = () => {
  let hex = form.value.codigo_hex.trim();

  // Agregar # si no lo tiene
  if (hex && !hex.startsWith('#')) {
    hex = '#' + hex;
  }

  // Limpiar caracteres inválidos
  hex = hex.replace(/[^#0-9A-Fa-f]/g, '');

  // Limitar longitud
  if (hex.length > 7) {
    hex = hex.substring(0, 7);
  }

  form.value.codigo_hex = hex;

  // Limpiar error si el formato es válido
  if (colorService.validateHexColor(hex)) {
    delete errors.value.codigo_hex;
  }
};

const updateHexFromPicker = () => {
  // El input de color ya proporciona el formato correcto
  validateAndFormatHex();
};

const generateRandomColor = () => {
  form.value.codigo_hex = colorService.generateRandomColor();
};

const closeModal = () => {
  resetForm();
  emit('close');
};

const handleSubmit = async () => {
  loading.value = true;
  errors.value = {};

  try {
    if (isEditing.value && props.color) {
      await colorStore.updateColor(props.color.id, form.value);
    } else {
      await colorStore.createColor(form.value);
    }

    emit('saved');
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors;
    }
  } finally {
    loading.value = false;
  }
};

// Watchers
watch(() => props.visible, (newValue) => {
  if (newValue) {
    loadColorData();
  }
});

// Lifecycle
onMounted(() => {
  if (props.visible) {
    loadColorData();
  }
});
</script>

<style scoped>
.form-input {
  display: block;
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid rgb(209 213 219);
  border-radius: 0.375rem;
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}

.form-input:focus {
  outline: 2px solid transparent;
  outline-offset: 2px;
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.dark .form-input {
  background-color: rgb(55 65 81);
  border-color: rgb(75 85 99);
  color: white;
}

.form-checkbox {
  border-radius: 0.25rem;
  border-color: rgb(209 213 219);
  color: #4f46e5;
}

.form-checkbox:focus {
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.dark .form-checkbox {
  background-color: rgb(55 65 81);
  border-color: rgb(75 85 99);
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 500;
  font-size: 0.875rem;
  line-height: 1.25rem;
  transition: all 0.2s;
  border: 1px solid transparent;
  cursor: pointer;
  text-decoration: none;
}

.btn:focus {
  outline: 2px solid transparent;
  outline-offset: 2px;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.btn-primary {
  background-color: #4f46e5;
  color: white;
  border-color: #4f46e5;
}

.btn-primary:hover {
  background-color: #4338ca;
  border-color: #4338ca;
}

.btn-outline-primary {
  border: 1px solid #4f46e5;
  color: #4f46e5;
  background-color: transparent;
}

.btn-outline-primary:hover {
  background-color: #4f46e5;
  color: white;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn:disabled:hover {
  background-color: #4f46e5;
  border-color: #4f46e5;
}
</style>
