<template>
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Encabezado -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-3">
          <router-link
            to="/empresa-datos"
            class="text-gray-400 hover:text-gray-600 transition-colors"
            title="Volver al listado"
          >
            <ArrowLeftIcon class="w-5 h-5" />
          </router-link>
          <h1 class="text-2xl font-bold text-gray-800">Editar Dato</h1>
        </div>
        <div class="flex items-center gap-2">
          <span :class="[
            'px-2 py-1 text-xs font-medium rounded',
            empresaDato?.activo 
              ? 'bg-green-100 text-green-800' 
              : 'bg-red-100 text-red-800'
          ]">
            {{ empresaDato?.activo_label }}
          </span>
          <span class="text-sm text-gray-500">ID: {{ empresaDato?.id }}</span>
        </div>
      </div>
      <p class="text-gray-600">Edita la información del dato de empresa</p>
    </div>

    <!-- Loading -->
    <div v-if="store.loading && !empresaDato" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
      <p class="mt-4 text-gray-600">Cargando información...</p>
    </div>

    <!-- Error al cargar -->
    <div v-else-if="errorCarga" class="text-center py-12">
      <ExclamationTriangleIcon class="w-16 h-16 text-red-500 mx-auto mb-4" />
      <p class="text-lg font-medium text-gray-800 mb-2">{{ errorCarga }}</p>
      <div class="flex justify-center gap-3 mt-6">
        <router-link
          to="/empresa-datos"
          class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors"
        >
          Volver al listado
        </router-link>
        <button
          @click="cargarDato"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
        >
          Reintentar
        </button>
      </div>
    </div>

    <!-- Formulario -->
    <div v-else-if="empresaDato" class="bg-white rounded-lg shadow">
      <form @submit.prevent="actualizar" class="p-6">
        <!-- Mensajes de error -->
        <div v-if="erroresGenerales" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
          <div class="flex items-start gap-3">
            <ExclamationCircleIcon class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" />
            <div>
              <p class="text-sm font-medium text-red-800">Hay errores en el formulario:</p>
              <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                <li v-for="(error, field) in erroresGenerales" :key="field">
                  {{ error[0] }}
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Grid de 2 columnas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Columna izquierda -->
          <div class="space-y-6">
            <!-- Clave -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Clave <span class="text-red-500">*</span>
              </label>
              <select
                v-model="formData.clave"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                  errores.clave ? 'border-red-300' : 'border-gray-300'
                ]"
                @blur="validarCampo('clave')"
              >
                <option value="">Selecciona una clave</option>
                <option 
                  v-for="option in store.getClavesOptions" 
                  :key="option.value" 
                  :value="option.value"
                  :disabled="clavesUsadas.includes(option.value) && option.value !== empresaDato.clave"
                >
                  {{ option.label }} 
                  <span v-if="clavesUsadas.includes(option.value) && option.value !== empresaDato.clave" 
                        class="text-gray-400">
                    (Ya en uso)
                  </span>
                </option>
              </select>
              <div v-if="errores.clave" class="mt-1 text-sm text-red-600">
                {{ errores.clave[0] }}
              </div>
              <p class="mt-1 text-xs text-gray-500">
                Identificador único para este dato
              </p>
            </div>

            <!-- Título -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Título
              </label>
              <input
                v-model="formData.titulo"
                type="text"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                  errores.titulo ? 'border-red-300' : 'border-gray-300'
                ]"
                @blur="validarCampo('titulo')"
              />
              <div v-if="errores.titulo" class="mt-1 text-sm text-red-600">
                {{ errores.titulo[0] }}
              </div>
              <p class="mt-1 text-xs text-gray-500">
                Título descriptivo del dato
              </p>
            </div>

            <!-- Orden -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Orden
              </label>
              <input
                v-model.number="formData.orden"
                type="number"
                min="0"
                :class="[
                  'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                  errores.orden ? 'border-red-300' : 'border-gray-300'
                ]"
                @blur="validarCampo('orden')"
              />
              <div v-if="errores.orden" class="mt-1 text-sm text-red-600">
                {{ errores.orden[0] }}
              </div>
              <p class="mt-1 text-xs text-gray-500">
                Número para ordenar los datos (menor = primero)
              </p>
            </div>

            <!-- Estado -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Estado
              </label>
              <div class="flex items-center gap-3">
                <button
                  type="button"
                  @click="formData.activo = true"
                  :class="[
                    'flex-1 py-2 px-4 rounded-lg border flex items-center justify-center gap-2 transition-colors',
                    formData.activo 
                      ? 'bg-green-100 border-green-500 text-green-700' 
                      : 'bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100'
                  ]"
                >
                  <CheckIcon class="w-4 h-4" />
                  Activo
                </button>
                <button
                  type="button"
                  @click="formData.activo = false"
                  :class="[
                    'flex-1 py-2 px-4 rounded-lg border flex items-center justify-center gap-2 transition-colors',
                    !formData.activo 
                      ? 'bg-red-100 border-red-500 text-red-700' 
                      : 'bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100'
                  ]"
                >
                  <XMarkIcon class="w-4 h-4" />
                  Inactivo
                </button>
              </div>
              <p class="mt-1 text-xs text-gray-500">
                Controla si el dato está visible en el sitio
              </p>
            </div>
          </div>

          <!-- Columna derecha -->
          <div class="space-y-6">
            <!-- Imagen Actual -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Imagen Actual
              </label>
              
              <div v-if="empresaDato.imagen_url" class="space-y-3">
                <div class="relative w-full h-48 rounded-lg overflow-hidden border border-gray-300">
                  <img
                    :src="empresaDato.imagen_url"
                    :alt="empresaDato.titulo || 'Imagen'"
                    class="w-full h-full object-cover"
                  />
                  <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-opacity flex items-center justify-center">
                    <a
                      :href="empresaDato.imagen_url"
                      target="_blank"
                      class="opacity-0 hover:opacity-100 p-2 bg-white bg-opacity-80 rounded-full transition-opacity"
                      title="Ver imagen completa"
                    >
                      <ArrowTopRightOnSquareIcon class="w-5 h-5" />
                    </a>
                  </div>
                </div>
                
                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    @click="eliminarImagenActual = true"
                    :class="[
                      'px-3 py-1.5 text-sm rounded-md flex items-center gap-1 transition-colors',
                      eliminarImagenActual
                        ? 'bg-red-600 text-white hover:bg-red-700'
                        : 'bg-red-100 text-red-700 hover:bg-red-200'
                    ]"
                  >
                    <TrashIcon class="w-4 h-4" />
                    {{ eliminarImagenActual ? 'Se eliminará' : 'Eliminar imagen' }}
                  </button>
                  
                  <button
                    type="button"
                    @click="triggerFileInput"
                    class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center gap-1"
                  >
                    <ArrowUpTrayIcon class="w-4 h-4" />
                    Cambiar imagen
                  </button>
                </div>
                
                <p v-if="eliminarImagenActual" class="text-sm text-amber-600 flex items-center gap-1">
                  <ExclamationTriangleIcon class="w-4 h-4" />
                  La imagen actual se eliminará al guardar
                </p>
              </div>

              <!-- Sin imagen actual -->
              <div v-else>
                <div class="border border-dashed border-gray-300 rounded-lg p-8 text-center">
                  <PhotoIcon class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                  <p class="text-sm text-gray-600 mb-3">No hay imagen actual</p>
                  <button
                    type="button"
                    @click="triggerFileInput"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
                  >
                    <ArrowUpTrayIcon class="w-4 h-4" />
                    Subir imagen
                  </button>
                </div>
              </div>
            </div>

            <!-- Nueva Imagen Preview -->
            <div v-if="previewImage" class="space-y-3">
              <label class="block text-sm font-medium text-gray-700">
                Vista previa nueva imagen
              </label>
              <div class="relative w-full h-48 rounded-lg overflow-hidden border border-gray-300">
                <img
                  :src="previewImage"
                  alt="Preview"
                  class="w-full h-full object-cover"
                />
                <button
                  type="button"
                  @click="eliminarNuevaImagen"
                  class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors"
                  title="Eliminar nueva imagen"
                >
                  <XMarkIcon class="w-4 h-4" />
                </button>
              </div>
              <p class="text-sm text-green-600 flex items-center gap-1">
                <CheckCircleIcon class="w-4 h-4" />
                Nueva imagen seleccionada
              </p>
            </div>
          </div>
        </div>

        <!-- Contenido (full width) -->
        <div class="mt-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Contenido
          </label>
          <textarea
            v-model="formData.contenido"
            rows="6"
            :class="[
              'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
              errores.contenido ? 'border-red-300' : 'border-gray-300'
            ]"
            @blur="validarCampo('contenido')"
            placeholder="Ingresa el contenido del dato..."
          />
          <div v-if="errores.contenido" class="mt-1 text-sm text-red-600">
            {{ errores.contenido[0] }}
          </div>
          <div class="flex justify-between items-center mt-1">
            <p class="text-xs text-gray-500">
              Puedes usar formato HTML si es necesario
            </p>
            <span class="text-xs text-gray-400">
              {{ formData.contenido?.length || 0 }} caracteres
            </span>
          </div>
        </div>

        <!-- Botones -->
        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
          <div>
            <button
              type="button"
              @click="toggleEstado"
              :class="[
                'px-4 py-2 rounded-md flex items-center gap-2 transition-colors',
                empresaDato.activo
                  ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'
                  : 'bg-green-100 text-green-700 hover:bg-green-200'
              ]"
              :disabled="cambiandoEstado"
            >
              <PowerIcon class="w-4 h-4" />
              <span v-if="cambiandoEstado">Cambiando...</span>
              <span v-else>
                {{ empresaDato.activo ? 'Desactivar' : 'Activar' }}
              </span>
            </button>
          </div>
          
          <div class="flex gap-3">
            <router-link
              to="/empresa-datos"
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            >
              Cancelar
            </router-link>
            <button
              type="submit"
              :disabled="guardando"
              :class="[
                'px-6 py-2 bg-blue-600 text-white rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                guardando 
                  ? 'opacity-70 cursor-not-allowed' 
                  : 'hover:bg-blue-700'
              ]"
            >
              <span v-if="guardando" class="flex items-center gap-2">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                Actualizando...
              </span>
              <span v-else class="flex items-center gap-2">
                <CheckIcon class="w-4 h-4" />
                Actualizar
              </span>
            </button>
          </div>
        </div>
      </form>

      <!-- Información adicional -->
      <div class="px-6 pb-6">
        <div class="border-t border-gray-200 pt-6">
          <h3 class="text-sm font-medium text-gray-700 mb-3">Información adicional</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
              <span class="text-gray-500">Creado:</span>
              <span class="ml-2 font-medium">{{ formatDate(empresaDato.created_at) }}</span>
            </div>
            <div>
              <span class="text-gray-500">Última modificación:</span>
              <span class="ml-2 font-medium">{{ formatDate(empresaDato.updated_at) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div v-if="showToast" class="fixed bottom-4 right-4 z-50">
      <div :class="[
        'px-4 py-3 rounded-lg shadow-lg animate-slide-up flex items-center gap-2',
        toastType === 'success' 
          ? 'bg-green-100 border border-green-400 text-green-700'
          : 'bg-red-100 border border-red-400 text-red-700'
      ]">
        <CheckCircleIcon v-if="toastType === 'success'" class="w-5 h-5" />
        <ExclamationCircleIcon v-else class="w-5 h-5" />
        <span>{{ toastMessage }}</span>
      </div>
    </div>

    <!-- Input de archivo oculto -->
    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      class="hidden"
      @change="handleFileSelect"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useEmpresaDatoStore } from '../../stores/empresa-dato'
import { EmpresaDatoFormData } from '../../types/empresa-dato'
import {
  ArrowLeftIcon,
  ArrowUpTrayIcon,
  ArrowTopRightOnSquareIcon,
  PhotoIcon,
  XMarkIcon,
  CheckIcon,
  CheckCircleIcon,
  ExclamationCircleIcon,
  ExclamationTriangleIcon,
  PowerIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'

// Route, Router y Store
const route = useRoute()
const router = useRouter()
const store = useEmpresaDatoStore()

// Refs
const fileInput = ref<HTMLInputElement>()
const previewImage = ref<string | null>(null)
const guardando = ref(false)
const cambiandoEstado = ref(false)
const eliminarImagenActual = ref(false)
const showToast = ref(false)
const toastMessage = ref('')
const toastType = ref<'success' | 'error'>('success')
const errorCarga = ref<string | null>(null)

// Computed
const empresaDato = computed(() => store.empresaDato)
const clavesUsadas = computed(() => {
  return store.empresaDatos
    .filter(dato => dato.id !== Number(route.params.id))
    .map(dato => dato.clave)
})

// Form Data
const formData = reactive<EmpresaDatoFormData>({
  clave: '',
  titulo: '',
  contenido: '',
  imagen: null,
  activo: true,
  orden: 0
})

// Errores
const errores = reactive<Record<string, string[]>>({})
const erroresGenerales = computed(() => {
  const generalErrors: Record<string, string[]> = {}
  Object.entries(errores).forEach(([key, value]) => {
    if (value && value.length > 0) {
      generalErrors[key] = value
    }
  })
  return Object.keys(generalErrors).length > 0 ? generalErrors : null
})

// Métodos
const cargarDato = async () => {
  errorCarga.value = null
  const id = Number(route.params.id)
  
  if (!id || isNaN(id)) {
    errorCarga.value = 'ID inválido'
    return
  }

  try {
    const dato = await store.fetchEmpresaDato(id)
    
    if (!dato) {
      errorCarga.value = 'Dato no encontrado'
      return
    }

    // Llenar formData con los datos actuales
    formData.clave = dato.clave
    formData.titulo = dato.titulo || ''
    formData.contenido = dato.contenido || ''
    formData.activo = dato.activo
    formData.orden = dato.orden
    
  } catch (error: any) {
    errorCarga.value = error.message || 'Error al cargar el dato'
  }
}

const triggerFileInput = () => {
  fileInput.value?.click()
}

const handleFileSelect = (event: Event) => {
  const input = event.target as HTMLInputElement
  if (input.files && input.files[0]) {
    const file = input.files[0]
    validarArchivo(file)
  }
}

const validarArchivo = (file: File) => {
  // Validar tamaño
  if (file.size > 5 * 1024 * 1024) {
    mostrarError('La imagen no debe superar los 5MB', 'imagen')
    return
  }

  // Validar tipo
  const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
  if (!validTypes.includes(file.type)) {
    mostrarError('Formato de imagen no válido', 'imagen')
    return
  }

  // Asignar archivo y mostrar preview
  formData.imagen = file
  eliminarImagenActual.value = false // Cancelar eliminación si se selecciona nueva imagen
  
  const reader = new FileReader()
  reader.onload = (e) => {
    previewImage.value = e.target?.result as string
  }
  reader.readAsDataURL(file)
  
  // Limpiar error si existe
  if (errores.imagen) {
    errores.imagen = []
  }
}

const eliminarNuevaImagen = () => {
  formData.imagen = null
  previewImage.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const validarCampo = (campo: keyof EmpresaDatoFormData) => {
  if (errores[campo]) {
    errores[campo] = []
  }
}

const mostrarError = (mensaje: string, campo?: string) => {
  if (campo) {
    errores[campo] = [mensaje]
  }
  toastMessage.value = mensaje
  toastType.value = 'error'
  showToast.value = true
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

const mostrarExito = (mensaje: string) => {
  toastMessage.value = mensaje
  toastType.value = 'success'
  showToast.value = true
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

const toggleEstado = async () => {
  if (!empresaDato.value) return
  
  cambiandoEstado.value = true
  try {
    await store.toggleActivo(empresaDato.value.id)
    // Actualizar formData con el nuevo estado
    formData.activo = !formData.activo
    mostrarExito('Estado actualizado correctamente')
  } catch (error: any) {
    mostrarError(error.response?.data?.message || 'Error al cambiar el estado')
  } finally {
    cambiandoEstado.value = false
  }
}

const actualizar = async () => {
  // Validación básica
  if (!formData.clave) {
    mostrarError('La clave es requerida', 'clave')
    return
  }

  if (clavesUsadas.value.includes(formData.clave)) {
    mostrarError('Esta clave ya está en uso', 'clave')
    return
  }

  guardando.value = true
  try {
    // Limpiar errores previos
    Object.keys(errores).forEach(key => {
      errores[key] = []
    })

    // Preparar datos para enviar
    const datosParaEnviar = {
      ...formData,
      remove_imagen: eliminarImagenActual.value && !formData.imagen
    }

    await store.updateEmpresaDato(Number(route.params.id), datosParaEnviar)
    
    mostrarExito('Dato actualizado exitosamente')
    
    // Recargar datos actualizados
    await cargarDato()
    
    // Resetear estado de imagen
    eliminarImagenActual.value = false
    previewImage.value = null
    
  } catch (error: any) {
    // Manejar errores de validación
    if (error.response?.data?.errors) {
      Object.entries(error.response.data.errors).forEach(([field, messages]) => {
        errores[field] = messages as string[]
      })
      mostrarError('Por favor, corrige los errores en el formulario')
    } else {
      mostrarError(error.response?.data?.message || 'Error al actualizar el dato')
    }
  } finally {
    guardando.value = false
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Lifecycle
onMounted(async () => {
  await store.fetchEmpresaDatos()
  await store.fetchClavesDisponibles()
  await cargarDato()
})
</script>

<style scoped>
.animate-slide-up {
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from {
    transform: translateY(100%);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
</style>