<template>
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Encabezado -->
    <div class="mb-6">
      <div class="flex items-center gap-3 mb-2">
        <router-link
          to="/administrador/empresa"
          class="text-gray-400 hover:text-gray-600 transition-colors"
          title="Volver al listado"
        >
          <ArrowLeftIcon class="w-5 h-5" />
        </router-link>
        <h1 class="text-2xl font-bold text-gray-800">Crear Nuevo Dato</h1>
      </div>
      <p class="text-gray-600">Completa el formulario para agregar un nuevo dato de empresa</p>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow">
      <form @submit.prevent="guardar" class="p-6">
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
                  :disabled="clavesUsadas.includes(option.value)"
                >
                  {{ option.label }} 
                  <span v-if="clavesUsadas.includes(option.value)" class="text-gray-400">
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
            <!-- Imagen -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Imagen
              </label>
              
              <!-- Preview de imagen -->
              <div v-if="previewImage" class="mb-4">
                <div class="relative w-full h-48 rounded-lg overflow-hidden border border-gray-300">
                  <img
                    :src="previewImage"
                    alt="Preview"
                    class="w-full h-full object-cover"
                  />
                  <button
                    type="button"
                    @click="eliminarImagen"
                    class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors"
                    title="Eliminar imagen"
                  >
                    <XMarkIcon class="w-4 h-4" />
                  </button>
                </div>
              </div>

              <!-- Dropzone -->
              <div
                v-if="!previewImage"
                @click="triggerFileInput"
                @dragover.prevent="dragOver = true"
                @dragleave.prevent="dragOver = false"
                @drop.prevent="handleDrop"
                :class="[
                  'border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition-colors',
                  dragOver 
                    ? 'border-blue-500 bg-blue-50' 
                    : 'border-gray-300 hover:border-blue-400 hover:bg-blue-50',
                  errores.imagen ? 'border-red-300' : ''
                ]"
              >
                <div class="space-y-3">
                  <PhotoIcon class="w-12 h-12 text-gray-400 mx-auto" />
                  <div>
                    <p class="text-sm font-medium text-gray-700">
                      Arrastra una imagen o haz clic para subir
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                      PNG, JPG, GIF, WEBP (Máx. 5MB)
                    </p>
                  </div>
                  <button
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
                  >
                    <ArrowUpTrayIcon class="w-4 h-4" />
                    Seleccionar imagen
                  </button>
                </div>
              </div>

              <input
                ref="fileInput"
                type="file"
                accept="image/*"
                class="hidden"
                @change="handleFileSelect"
              />

              <div v-if="errores.imagen" class="mt-1 text-sm text-red-600">
                {{ errores.imagen[0] }}
              </div>
              <p class="mt-1 text-xs text-gray-500">
                Imagen relacionada con el dato (opcional)
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
        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end gap-3">
          <router-link
            to="/administrador/empresa"
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
              Guardando...
            </span>
            <span v-else class="flex items-center gap-2">
              <CheckIcon class="w-4 h-4" />
              Guardar
            </span>
          </button>
        </div>
      </form>
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
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useEmpresaDatoStore } from '../../stores/empresa-dato'
import { EmpresaDatoFormData } from '../../types/empresa-dato'
import {
  ArrowLeftIcon,
  ArrowUpTrayIcon,
  PhotoIcon,
  XMarkIcon,
  CheckIcon,
  CheckCircleIcon,
  ExclamationCircleIcon
} from '@heroicons/vue/24/outline'

// Store y Router
const store = useEmpresaDatoStore()
const router = useRouter()

// Refs
const fileInput = ref<HTMLInputElement>()
const previewImage = ref<string | null>(null)
const dragOver = ref(false)
const guardando = ref(false)
const showToast = ref(false)
const toastMessage = ref('')
const toastType = ref<'success' | 'error'>('success')

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

// Claves ya usadas
const clavesUsadas = computed(() => {
  return store.empresaDatos.map(dato => dato.clave)
})

// Métodos
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

const handleDrop = (event: DragEvent) => {
  dragOver.value = false
  const files = event.dataTransfer?.files
  if (files && files[0]) {
    const file = files[0]
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

const eliminarImagen = () => {
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

const guardar = async () => {
  // Validación básica
  if (!formData.clave) {
    mostrarError('La clave es requerida', 'clave')
    return
  }

  if (clavesUsadas.value.includes(formData.clave)) {
    mostrarError('Esta clave ya está en uso', 'clave')
    return
  }

  // DEBUG: Ver qué tenemos en formData antes de enviar
  console.group('📋 Form Data antes de enviar')
  console.log('formData completo:', formData)
  console.log('activo:', formData.activo, 'type:', typeof formData.activo)
  console.log('orden:', formData.orden, 'type:', typeof formData.orden)
  console.log('clave:', formData.clave)
  console.log('imagen:', formData.imagen)
  console.groupEnd()

  guardando.value = true
  try {
    // Limpiar errores previos
    Object.keys(errores).forEach(key => {
      errores[key] = []
    })

    await store.createEmpresaDato(formData)
    
    mostrarExito('Dato creado exitosamente')
    
    // Redirigir después de un segundo
    setTimeout(() => {
      router.push('/administrador/empresa')
    }, 1000)
    
  } catch (error: any) {
    // DEBUG: Mostrar error completo
    console.error('🔥 Error completo en guardar:', error)
    
    // Manejar errores de validación
    if (error.response?.data?.errors) {
      console.error('Errores de validación:', error.response.data.errors)
      Object.entries(error.response.data.errors).forEach(([field, messages]) => {
        errores[field] = messages as string[]
      })
      mostrarError('Por favor, corrige los errores en el formulario')
    } else {
      mostrarError(error.response?.data?.message || 'Error al crear el dato')
    }
  } finally {
    guardando.value = false
  }
}

// Lifecycle
onMounted(async () => {
  await store.fetchEmpresaDatos()
  await store.fetchClavesDisponibles()
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