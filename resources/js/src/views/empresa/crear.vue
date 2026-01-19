<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <router-link
                        to="/administrador/empresa"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 transition mr-4"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </router-link>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Crear Nuevo Dato
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Completa el formulario para agregar un nuevo dato de empresa
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="p-6">
                    <!-- Mensajes de Error -->
                    <div v-if="erroresGenerales" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-700 dark:text-red-400">
                                    Hay errores en el formulario:
                                </p>
                                <ul class="mt-2 text-sm text-red-600 dark:text-red-300 list-disc list-inside">
                                    <li v-for="(errorList, field) in erroresGenerales" :key="field">
                                        {{ errorList[0] }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="guardar">
                        <!-- Grid de 2 columnas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Columna izquierda -->
                            <div class="space-y-6">
                                <!-- Clave -->
                                <div>
                                    <label for="clave" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Clave <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="formData.clave"
                                        id="clave"
                                        :class="[
                                            'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                            errores.clave ? 'border-red-300 dark:border-red-700' : ''
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
                                            <span v-if="clavesUsadas.includes(option.value)" class="text-gray-400 dark:text-gray-500">
                                                (Ya en uso)
                                            </span>
                                        </option>
                                    </select>
                                    <div v-if="errores.clave" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ errores.clave[0] }}
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Identificador único para este dato
                                    </p>
                                </div>

                                <!-- Título -->
                                <div>
                                    <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Título
                                    </label>
                                    <input
                                        v-model="formData.titulo"
                                        id="titulo"
                                        type="text"
                                        :class="[
                                            'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                            errores.titulo ? 'border-red-300 dark:border-red-700' : ''
                                        ]"
                                        @blur="validarCampo('titulo')"
                                    />
                                    <div v-if="errores.titulo" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ errores.titulo[0] }}
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Título descriptivo del dato
                                    </p>
                                </div>

                                <!-- Orden -->
                                <div>
                                    <label for="orden" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Orden
                                    </label>
                                    <input
                                        v-model.number="formData.orden"
                                        id="orden"
                                        type="number"
                                        min="0"
                                        :class="[
                                            'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                            errores.orden ? 'border-red-300 dark:border-red-700' : ''
                                        ]"
                                        @blur="validarCampo('orden')"
                                    />
                                    <div v-if="errores.orden" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ errores.orden[0] }}
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Número para ordenar los datos (menor = primero)
                                    </p>
                                </div>

                                <!-- Estado -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Estado
                                    </label>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Controla si el dato está visible en el sitio
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            @click="formData.activo = !formData.activo"
                                            :class="[
                                                'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                                                formData.activo ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                                    formData.activo ? 'translate-x-5' : 'translate-x-0'
                                                ]"
                                            />
                                        </button>
                                    </div>
                                    <div class="mt-2 flex items-center">
                                        <span 
                                            :class="[
                                                'px-2 py-1 text-xs font-medium rounded-full',
                                                formData.activo 
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                            ]"
                                        >
                                            {{ formData.activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                        <p class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            {{ formData.activo ? 'Los usuarios podrán ver este dato.' : 'Este dato estará oculto para los usuarios.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna derecha - Imagen -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Imagen
                                </label>
                                
                                <!-- Preview de imagen -->
                                <div v-if="previewImage" class="mb-4">
                                    <div class="relative w-full h-64 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600">
                                        <img
                                            :src="previewImage"
                                            alt="Preview"
                                            class="w-full h-full object-cover"
                                        />
                                        <button
                                            type="button"
                                            @click="eliminarImagen"
                                            class="absolute top-3 right-3 p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500"
                                            title="Eliminar imagen"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Dropzone -->
                                <div
                                    v-if="!previewImage"
                                    @click="triggerFileInput"
                                    @dragover.prevent="handleDragOver"
                                    @dragleave.prevent="handleDragLeave"
                                    @drop.prevent="handleDrop"
                                    :class="[
                                        'border-2 border-dashed rounded-lg p-8 text-center transition-all duration-200 cursor-pointer',
                                        isDragging 
                                            ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' 
                                            : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500',
                                        errores.imagen ? 'border-red-300 dark:border-red-700' : ''
                                    ]"
                                >
                                    <input
                                        ref="fileInput"
                                        type="file"
                                        accept="image/*"
                                        class="hidden"
                                        @change="handleFileSelect"
                                    />
                                    
                                    <div class="space-y-4">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                <span 
                                                    class="font-medium text-blue-600 dark:text-blue-400 cursor-pointer"
                                                    @click.stop="triggerFileInput"
                                                >
                                                    Haz clic para subir
                                                </span>
                                                o arrastra y suelta tu archivo
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                PNG, JPG, GIF, WEBP (Máx. 5MB)
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            @click.stop="triggerFileInput"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            Seleccionar imagen
                                        </button>
                                    </div>
                                </div>

                                <div v-if="errores.imagen" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ errores.imagen[0] }}
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Imagen relacionada con el dato (opcional)
                                </p>
                            </div>
                        </div>

                        <!-- Contenido (full width) -->
                        <div class="mb-8">
                            <label for="contenido" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Contenido
                            </label>
                            <textarea
                                v-model="formData.contenido"
                                id="contenido"
                                rows="6"
                                :class="[
                                    'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition resize-none',
                                    errores.contenido ? 'border-red-300 dark:border-red-700' : ''
                                ]"
                                @blur="validarCampo('contenido')"
                                placeholder="Ingresa el contenido del dato..."
                            />
                            <div v-if="errores.contenido" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ errores.contenido[0] }}
                            </div>
                            <div class="mt-1 flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <p>Puedes usar formato HTML si es necesario</p>
                                <span>{{ formData.contenido?.length || 0 }}/5000</span>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t dark:border-gray-700">
                            <router-link
                                to="/administrador/empresa"
                                class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition"
                            >
                                Cancelar
                            </router-link>
                            <button
                                type="submit"
                                :disabled="guardando || !isFormValid"
                                :class="[
                                    'px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition',
                                    (guardando || !isFormValid) 
                                        ? 'opacity-50 cursor-not-allowed' 
                                        : ''
                                ]"
                            >
                                <span v-if="guardando" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Guardando...
                                </span>
                                <span v-else class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Guardar
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <div v-if="showToast" class="fixed bottom-4 right-4 z-50">
            <div 
                :class="[
                    'px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 transition-all duration-300',
                    toastType === 'success' 
                        ? 'bg-green-100 border border-green-400 text-green-700 dark:bg-green-900/30 dark:border-green-800 dark:text-green-200'
                        : 'bg-red-100 border border-red-400 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-200'
                ]"
            >
                <svg v-if="toastType === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
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

// Store y Router
const store = useEmpresaDatoStore()
const router = useRouter()

// Refs
const fileInput = ref<HTMLInputElement>()
const previewImage = ref<string | null>(null)
const isDragging = ref(false)
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

// Validación del formulario
const isFormValid = computed(() => {
    return formData.clave.trim() !== '' && 
           !clavesUsadas.value.includes(formData.clave)
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

const handleDragOver = (event: DragEvent) => {
    event.preventDefault()
    isDragging.value = true
}

const handleDragLeave = (event: DragEvent) => {
    event.preventDefault()
    isDragging.value = false
}

const handleDrop = (event: DragEvent) => {
    event.preventDefault()
    isDragging.value = false
    
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
/* La animación del toast se maneja con Tailwind transition */
</style>