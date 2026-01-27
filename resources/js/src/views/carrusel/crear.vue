<template>
    <div>
        <div class="flex items-center gap-4 mb-6">
            <router-link
                to="/administrador/carrusel"
                class="btn btn-outline-primary"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </router-link>
            <h2 class="text-xl font-semibold">Crear Elemento del Carrusel</h2>
        </div>

        <div class="panel">
            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Información básica -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold border-b pb-2">Información Básica</h3>

                        <!-- Título -->
                        <div>
                            <label for="titulo" class="form-label required">Título</label>
                            <input
                                id="titulo"
                                v-model="form.titulo"
                                type="text"
                                class="form-input"
                                :class="{ 'border-red-500': errors.titulo }"
                                placeholder="Título del carrusel"
                                required
                            />
                            <div v-if="errors.titulo" class="text-red-500 text-sm mt-1">
                                {{ errors.titulo[0] }}
                            </div>
                        </div>

                        <!-- Subtítulo -->
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <label for="activar_subtitulo" class="flex items-center cursor-pointer">
                                    <span class="ml-2">Activar Subtítulo</span>
                                </label>
                            </div>
                            <textarea
                                v-model="form.subtitulo"
                                class="form-textarea"
                                :class="{
                                    'border-red-500': errors.subtitulo,
                                }"
                                placeholder="Subtítulo opcional"
                                rows="3"
                            ></textarea>
                            <div v-if="errors.subtitulo" class="text-red-500 text-sm mt-1">
                                {{ errors.subtitulo[0] }}
                            </div>
                        </div>

                        <!-- Posición del contenido -->
                        <div>
                            <label for="posicion_contenido" class="form-label required">Posición del Contenido</label>
                            <select
                                id="posicion_contenido"
                                v-model="form.posicion_contenido"
                                class="form-select"
                                :class="{ 'border-red-500': errors.posicion_contenido }"
                                required
                            >
                                <option value="">Selecciona una posición</option>
                                <option value="Izquierda">Izquierda</option>
                                <option value="Derecha">Derecha</option>
                            </select>
                            <div v-if="errors.posicion_contenido" class="text-red-500 text-sm mt-1">
                                {{ errors.posicion_contenido[0] }}
                            </div>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="estado" class="flex items-center cursor-pointer">
                                <input
                                    id="estado"
                                    v-model="form.estado"
                                    type="checkbox"
                                    class="form-checkbox"
                                />
                                <span class="ml-2">Elemento activo</span>
                            </label>
                        </div>
                    </div>

                    <!-- Configuración de botón e imagen -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold border-b pb-2">Configuración del Botón</h3>

                        <!-- Activar botón -->
                        <div>
                            <label for="activar_boton" class="flex items-center cursor-pointer">
                                <input
                                    id="activar_boton"
                                    v-model="form.activar_boton"
                                    type="checkbox"
                                    class="form-checkbox"
                                />
                                <span class="ml-2">Activar Botón de Acción</span>
                            </label>
                            <p class="text-sm text-gray-500 mt-1">
                                Si se desactiva, se mostrará el botón "Iniciar sesión"
                            </p>
                        </div>

                        <!-- Sección de configuración del botón personalizado -->
                        <div v-if="form.activar_boton" class="space-y-4 p-4 bg-gray-50 rounded-lg border">
                            <!-- Texto del botón -->
                            <div>
                                <label for="texto_boton" class="form-label">Texto del Botón</label>
                                <input
                                    id="texto_boton"
                                    v-model="form.texto_boton"
                                    type="text"
                                    class="form-input"
                                    :class="{ 'border-red-500': errors.texto_boton }"
                                    placeholder="Ej: Ver más, Comprar ahora, Descubrir"
                                />
                                <div v-if="errors.texto_boton" class="text-red-500 text-sm mt-1">
                                    {{ errors.texto_boton[0] }}
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    Dejar vacío para usar "Ver más"
                                </p>
                            </div>

                            <!-- Color del botón -->
                            <div>
                                <label for="color_boton" class="form-label">Color del Botón</label>
                                <div class="flex items-center gap-4">
                                    <input
                                        id="color_boton"
                                        v-model="form.color_boton"
                                        type="color"
                                        class="w-12 h-12 cursor-pointer rounded border"
                                    />
                                    <input
                                        type="text"
                                        v-model="form.color_boton"
                                        class="form-input flex-1 font-mono"
                                        :class="{ 'border-red-500': errors.color_boton }"
                                        placeholder="#3B82F6"
                                        pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                    />
                                    <div class="w-20 h-10 rounded border" :style="{ backgroundColor: form.color_boton || '#3B82F6' }"></div>
                                </div>
                                <div v-if="errors.color_boton" class="text-red-500 text-sm mt-1">
                                    {{ errors.color_boton[0] }}
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    Color en formato hexadecimal (ej: #3B82F6)
                                </p>
                            </div>

                            <!-- URL del botón -->
                            <div>
                                <label for="url_boton" class="form-label">URL del Botón</label>
                                <input
                                    id="url_boton"
                                    v-model="form.url_boton"
                                    type="url"
                                    class="form-input"
                                    :class="{ 'border-red-500': errors.url_boton }"
                                    placeholder="https://ejemplo.com"
                                />
                                <div v-if="errors.url_boton" class="text-red-500 text-sm mt-1">
                                    {{ errors.url_boton[0] }}
                                </div>
                            </div>

                            <!-- Redirigir en la misma página -->
                            <div>
                                <label for="redirigir_misma_pagina" class="flex items-center cursor-pointer">
                                    <input
                                        id="redirigir_misma_pagina"
                                        v-model="form.redirigir_misma_pagina"
                                        type="checkbox"
                                        class="form-checkbox"
                                    />
                                    <span class="ml-2">Abrir en la misma página</span>
                                </label>
                                <p class="text-sm text-gray-500 ml-6 mt-1">
                                    Si está desactivado, se abrirá en una nueva pestaña
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Imagen -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold border-b pb-2">Imagen del Carrusel</h3>
                    <div>
                        <label class="form-label required">Imagen</label>
                        <div
                            class="custom-file-container"
                            :class="{ 'border-red-500': errors.imagen }"
                            data-upload-id="carruselImage"
                        >
                            <div class="label-container">
                                <label>Subir imagen</label>
                                <a
                                    href="javascript:;"
                                    class="custom-file-container__image-clear"
                                    title="Limpiar imagen"
                                >×</a>
                            </div>
                            <label class="custom-file-container__custom-file">
                                <input
                                    type="file"
                                    class="custom-file-container__custom-file__custom-file-input"
                                    accept="image/*"
                                    @change="handleImageChange"
                                />
                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                                <span class="custom-file-container__custom-file__custom-file-control ltr:pr-20 rtl:pl-20"></span>
                            </label>
                            <div class="custom-file-container__image-preview"></div>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">
                            Formatos permitidos: JPG, PNG, GIF, SVG. Tamaño máximo: 2MB
                        </div>
                        <div v-if="errors.imagen" class="text-red-500 text-sm mt-1">
                            {{ errors.imagen[0] }}
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t">
                    <router-link
                        to="/administrador/carrusel"
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
                        <div v-if="loading" class="flex items-center gap-2">
                            <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                            Guardando...
                        </div>
                        <span v-else>Crear Carrusel</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'
import FileUploadWithPreview from "file-upload-with-preview";

// Interfaces locales
interface CarruselFormData {
    titulo: string
    subtitulo: string
    activar_subtitulo: boolean
    activar_boton: boolean
    url_boton: string
    texto_boton: string
    color_boton: string
    redirigir_misma_pagina: boolean
    posicion_contenido: string
    imagen: File | null
    estado: boolean
}

interface ValidationErrors {
    [key: string]: string[]
}

// Router
const router = useRouter()

// Estado reactivo
const loading = ref(false)
const errors = ref<ValidationErrors>({})
const imageUpload = ref<any>(null)

const form = reactive<CarruselFormData>({
    titulo: '',
    subtitulo: '',
    activar_subtitulo: true,
    activar_boton: false,
    url_boton: '',
    texto_boton: '',
    color_boton: '#3B82F6',
    redirigir_misma_pagina: false,
    posicion_contenido: '',
    imagen: null,
    estado: true
})

// Métodos
const handleImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        form.imagen = target.files[0]
    }
}

const submitForm = async () => {
    loading.value = true
    errors.value = {}

    try {
        const formData = new FormData()

        // Agregar todos los campos al FormData
        formData.append('titulo', form.titulo)
        formData.append('subtitulo', form.subtitulo || '')
        formData.append('activar_subtitulo', form.activar_subtitulo ? '1' : '0')
        formData.append('activar_boton', form.activar_boton ? '1' : '0')
        
        // Nuevos campos del botón
        formData.append('texto_boton', form.texto_boton || '')
        formData.append('color_boton', form.color_boton || '#3B82F6')
        
        // Campos existentes
        formData.append('url_boton', form.url_boton || '')
        formData.append('redirigir_misma_pagina', form.redirigir_misma_pagina ? '1' : '0')
        formData.append('posicion_contenido', form.posicion_contenido)
        formData.append('estado', form.estado ? '1' : '0')

        if (form.imagen) {
            formData.append('imagen', form.imagen)
        }

        const response = await fetch('/tienda/public/api/carrusel', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
            },
            body: formData
        })

        const data = await response.json()

        if (data.success) {
            await Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Carrusel creado exitosamente',
                timer: 2000,
                showConfirmButton: false
            })
            router.push('/administrador/carrusel')
        } else {
            if (data.errors) {
                errors.value = data.errors
            } else {
                throw new Error(data.message || 'Error al crear el carrusel')
            }
        }
    } catch (error: any) {
        console.error('Error al crear carrusel:', error)
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No se pudo crear el carrusel'
        })
    } finally {
        loading.value = false
    }
}

// Lifecycle
onMounted(() => {
    // Inicializar el componente de subida de archivos
    imageUpload.value = new FileUploadWithPreview('carruselImage', {
        images: {
            baseImage: '/assets/images/file-preview.svg',
            backgroundImage: '',
        },
        multiple: false,
        text: {
            chooseFile: 'Seleccionar imagen',
            browse: 'Examinar',
            selectedCount: 'archivo seleccionado'
        }
    })
})
</script>

<style scoped>
input[type="color"] {
    -webkit-appearance: none;
    border: 2px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0;
}

input[type="color"]::-webkit-color-swatch-wrapper {
    padding: 0;
}

input[type="color"]::-webkit-color-swatch {
    border: none;
    border-radius: 0.25rem;
}

/* Estilo para el visualizador de color */
.w-20.h-10 {
    min-width: 5rem;
}
</style>

<style>
.custom-file-container {
    border: 2px dashed #d1d5db;
    border-radius: 0.5rem;
    padding: 1rem;
}

.custom-file-container.border-red-500 {
    border-color: #ef4444 !important;
}

.form-label.required::after {
    content: ' *';
    color: #ef4444;
}
.custom-file-container {
    border: 2px dashed #d1d5db;
    border-radius: 0.5rem;
    padding: 1rem;
}

.custom-file-container.border-red-500 {
    border-color: #ef4444 !important;
}

.form-label.required::after {
    content: ' *';
    color: #ef4444;
}

</style>
