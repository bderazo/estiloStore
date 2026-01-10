<template>
  <div class="crear-categoria-container">
    <!-- Header -->
    <div class="panel-header">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nueva Categoría</h1>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Crear una nueva categoría en el sistema
          </p>
        </div>
        <div class="flex items-center gap-2">
          <RouterLink
            :to="{ name: 'categorias.index' }"
            class="btn btn-secondary"
          >
            <IconArrowLeft class="w-4 h-4 mr-2" />
            Volver al listado
          </RouterLink>
        </div>
      </div>
    </div>

    <!-- Formulario -->
    <div class="panel">
      <form @submit.prevent="crearCategoria" class="space-y-6">
        <!-- Información básica -->
        <div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            Información Básica
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div class="form-group">
              <label for="nombre" class="form-label required">
                Nombre *
              </label>
              <input
                id="nombre"
                v-model="form.nombre"
                type="text"
                class="form-input"
                :class="{ 'form-input-error': errors.nombre }"
                placeholder="Ingrese el nombre de la categoría"
                maxlength="255"
                @input="generateSlug"
              />
              <div v-if="errors.nombre" class="form-error">
                {{ errors.nombre[0] }}
              </div>
              <div class="form-help">
                El nombre será usado como título visible de la categoría
              </div>
            </div>

            <!-- Slug -->
            <div class="form-group">
              <label for="slug" class="form-label">
                Slug
              </label>
              <input
                id="slug"
                v-model="slugValue"
                type="text"
                class="form-input"
                :class="{ 'form-input-error': errors.slug }"
                placeholder="se-generara-automaticamente"
                maxlength="255"
              />
              <div v-if="errors.slug" class="form-error">
                {{ errors.slug[0] }}
              </div>
              <div class="form-help">
                Se genera automáticamente desde el nombre. Usado para URLs amigables.
              </div>
            </div>
          </div>
        </div>

        <!-- Descripción -->
        <div class="form-group">
          <label for="descripcion" class="form-label">
            Descripción
          </label>
          <textarea
            id="descripcion"
            v-model="form.descripcion"
            rows="4"
            class="form-textarea"
            :class="{ 'form-input-error': errors.descripcion }"
            placeholder="Descripción opcional de la categoría"
            maxlength="1000"
          ></textarea>
          <div v-if="errors.descripcion" class="form-error">
            {{ errors.descripcion[0] }}
          </div>
          <div class="form-help">
            Descripción opcional que ayuda a entender el propósito de la categoría
          </div>
        </div>

        <!-- Jerarquía -->
        <div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            Estructura Jerárquica
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Categoría Padre -->
            <div class="form-group">
              <label for="parent_id" class="form-label">
                Categoría Padre
              </label>
              <select
                id="parent_id"
                v-model="form.parent_id"
                class="form-select"
                :class="{ 'form-input-error': errors.parent_id }"
                :disabled="loading"
              >
                <option value="">Sin categoría padre (Categoría principal)</option>
                <option
                  v-for="option in parentOptions"
                  :key="option.id"
                  :value="option.id"
                >
                  {{ option.label }}
                </option>
              </select>
              <div v-if="errors.parent_id" class="form-error">
                {{ errors.parent_id[0] }}
              </div>
              <div class="form-help">
                Seleccione una categoría padre para crear una subcategoría
              </div>
            </div>

            <!-- Orden -->
            <div class="form-group">
              <label for="orden" class="form-label">
                Orden
              </label>
              <input
                id="orden"
                v-model.number="form.orden"
                type="number"
                min="0"
                class="form-input"
                :class="{ 'form-input-error': errors.orden }"
                placeholder="0"
              />
              <div v-if="errors.orden" class="form-error">
                {{ errors.orden[0] }}
              </div>
              <div class="form-help">
                Orden de visualización. Si no se especifica, se asignará automáticamente.
              </div>
            </div>
          </div>
        </div>

        <!-- Estado -->
        <div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            Configuración
          </h3>

          <div class="form-group">
            <div class="flex items-center">
              <input
                id="activo"
                v-model="form.activo"
                type="checkbox"
                class="form-checkbox"
              />
              <label for="activo" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                Categoría activa
              </label>
            </div>
            <div class="form-help ml-6">
              Las categorías inactivas no serán visibles en el sistema
            </div>
          </div>
        </div>

        <!-- Vista previa de la ruta -->
        <div v-if="form.parent_id && selectedParent" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4">
          <div class="flex items-center">
            <IconInfoCircle class="w-5 h-5 text-blue-500 mr-2" />
            <div>
              <h4 class="text-sm font-medium text-blue-900 dark:text-blue-200">
                Vista previa de la estructura
              </h4>
              <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                <span class="font-medium">Ruta:</span> {{ previewPath }}
              </p>
            </div>
          </div>
        </div>

        <!-- Botones -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
          <RouterLink
            :to="{ name: 'categorias.index' }"
            class="btn btn-secondary"
            :class="{ 'pointer-events-none opacity-50': loading }"
          >
            Cancelar
          </RouterLink>

          <button
            type="submit"
            class="btn btn-primary"
            :disabled="loading || !isFormValid"
          >
            <IconLoader v-if="loading" class="w-4 h-4 mr-2 animate-spin" />
            <IconDeviceFloppy v-else class="w-4 h-4 mr-2" />
            {{ loading ? 'Creando...' : 'Crear Categoría' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Ayuda -->
    <div class="panel">
      <div class="flex items-start space-x-3">
        <IconBulb class="w-5 h-5 text-yellow-500 mt-0.5 flex-shrink-0" />
        <div>
          <h3 class="text-sm font-medium text-gray-900 dark:text-white">
            Consejos para crear categorías
          </h3>
          <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-1">
            <li>• Use nombres descriptivos y concisos para las categorías</li>
            <li>• El slug se genera automáticamente pero puede editarse manualmente</li>
            <li>• Las categorías principales no tienen categoría padre</li>
            <li>• El orden determina la posición en listados y menús</li>
            <li>• Las categorías inactivas permanecen ocultas hasta ser activadas</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { useCategoriaStore } from '../../stores/categoriaStore';
import Swal from 'sweetalert2';

// Icons
import {
  IconArrowLeft,
  IconDeviceFloppy,
  IconLoader,
  IconInfoCircle,
  IconBulb
} from '@tabler/icons-vue';

import type { CreateCategoriaRequest, CategoriaSelectOption } from '../../types/categoria';

// Router
const router = useRouter();

// Store
const categoriaStore = useCategoriaStore();

// Reactive data
const loading = ref(false);
const errors = ref<Record<string, string[]>>({});
const slugValue = ref('');

const form = ref<CreateCategoriaRequest>({
  nombre: '',
  descripcion: '',
  parent_id: null,
  activo: true,
  orden: undefined
});

// Computeds
const parentOptions = computed(() => categoriaStore.parentOptions);

const selectedParent = computed(() => {
  if (!form.value.parent_id) return null;
  return parentOptions.value.find(option => option.id === form.value.parent_id) || null;
});

const previewPath = computed(() => {
  if (!selectedParent.value || !form.value.nombre) return '';

  const parentName = selectedParent.value.nombre.replace(/^└─\s*/, '');
  return `${parentName} > ${form.value.nombre}`;
});

const isFormValid = computed(() => {
  return form.value.nombre.trim().length > 0 &&
         Object.keys(errors.value).length === 0;
});

// Methods
const generateSlug = () => {
  if (!form.value.nombre) return;

  slugValue.value = form.value.nombre
    .toLowerCase()
    .trim()
    .replace(/[áàäâã]/g, 'a')
    .replace(/[éèëê]/g, 'e')
    .replace(/[íìïî]/g, 'i')
    .replace(/[óòöôõ]/g, 'o')
    .replace(/[úùüû]/g, 'u')
    .replace(/[ñ]/g, 'n')
    .replace(/[ç]/g, 'c')
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .replace(/^-|-$/g, '');
};const clearErrors = () => {
  errors.value = {};
};

const crearCategoria = async () => {
  if (loading.value || !isFormValid.value) return;

  clearErrors();
  loading.value = true;

  try {
    // Limpiar datos vacíos
    const data: CreateCategoriaRequest = {
      nombre: form.value.nombre.trim(),
      activo: form.value.activo
    };

    if (form.value.descripcion?.trim()) {
      data.descripcion = form.value.descripcion.trim();
    }

    if (form.value.parent_id) {
      data.parent_id = form.value.parent_id;
    }

    if (form.value.orden !== undefined && form.value.orden !== null) {
      data.orden = form.value.orden;
    }

    const categoria = await categoriaStore.createCategoria(data);

    await Swal.fire({
      title: '¡Categoría creada!',
      text: `La categoría "${categoria.nombre}" ha sido creada exitosamente.`,
      icon: 'success',
      timer: 2000,
      showConfirmButton: false
    });

    // Redirigir al listado
    router.push({ name: 'categorias.index' });

  } catch (error: any) {
    console.error('Error al crear categoría:', error);

    if (error.response?.status === 422) {
      // Errores de validación
      errors.value = error.response.data.errors || {};

      Swal.fire({
        title: 'Errores de validación',
        text: 'Por favor corrige los errores en el formulario.',
        icon: 'error'
      });
    } else {
      // Error general
      Swal.fire({
        title: 'Error',
        text: error.response?.data?.message || 'No se pudo crear la categoría',
        icon: 'error'
      });
    }
  } finally {
    loading.value = false;
  }
};

const resetForm = () => {
  form.value = {
    nombre: '',
    descripcion: '',
    parent_id: null,
    activo: true,
    orden: undefined
  };
  clearErrors();
};

// Lifecycle
onMounted(async () => {
  try {
    await categoriaStore.fetchParentOptions();
  } catch (error) {
    console.error('Error al cargar opciones de categorías padre:', error);
  }
});

// Watchers
watch(() => form.value.nombre, () => {
  if (errors.value.nombre) {
    delete errors.value.nombre;
  }
  if (errors.value.slug) {
    delete errors.value.slug;
  }
});

watch(() => form.value.parent_id, () => {
  if (errors.value.parent_id) {
    delete errors.value.parent_id;
  }
});

watch(() => form.value.descripcion, () => {
  if (errors.value.descripcion) {
    delete errors.value.descripcion;
  }
});

watch(() => form.value.orden, () => {
  if (errors.value.orden) {
    delete errors.value.orden;
  }
});
</script>

<style scoped>
.crear-categoria-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.panel-header {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  padding: 1.5rem;
}

.panel {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  padding: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.form-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.form-label.required::after {
  content: ' *';
  color: #ef4444;
}

.form-input {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  outline: none;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 1px #3b82f6;
}

.form-input-error {
  border-color: #ef4444;
}

.form-input-error:focus {
  border-color: #ef4444;
  box-shadow: 0 0 0 1px #ef4444;
}

.form-textarea {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  outline: none;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  resize: vertical;
  min-height: 100px;
}

.form-textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 1px #3b82f6;
}

.form-select {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  outline: none;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-select:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 1px #3b82f6;
}

.form-checkbox {
  width: 1rem;
  height: 1rem;
  color: #3b82f6;
  border: 1px solid #d1d5db;
  border-radius: 0.25rem;
  outline: none;
}

.form-checkbox:focus {
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
}

.form-error {
  font-size: 0.875rem;
  color: #ef4444;
  margin-top: 0.25rem;
}

.form-help {
  font-size: 0.75rem;
  color: #6b7280;
  margin-top: 0.25rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  padding: 0.5rem 1rem;
  border: 1px solid transparent;
  font-weight: 500;
  border-radius: 0.375rem;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  font-size: 0.875rem;
  outline: none;
  transition: all 0.15s ease-in-out;
  cursor: pointer;
  text-decoration: none;
}

.btn:focus {
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-primary {
  color: white;
  background-color: #3b82f6;
  border-color: transparent;
}

.btn-primary:hover:not(:disabled) {
  background-color: #2563eb;
}

.btn-secondary {
  color: #374151;
  background-color: white;
  border-color: #d1d5db;
}

.btn-secondary:hover:not(:disabled) {
  background-color: #f9fafb;
}

/* Dark mode styles */
:global(.dark) .panel-header,
:global(.dark) .panel {
  background-color: #1f2937;
}

:global(.dark) .form-label {
  color: #d1d5db;
}

:global(.dark) .form-input,
:global(.dark) .form-textarea,
:global(.dark) .form-select {
  background-color: #374151;
  border-color: #4b5563;
  color: white;
}

:global(.dark) .form-help {
  color: #9ca3af;
}

:global(.dark) .btn-secondary {
  background-color: #374151;
  border-color: #4b5563;
  color: #d1d5db;
}

:global(.dark) .btn-secondary:hover:not(:disabled) {
  background-color: #4b5563;
}
</style>
