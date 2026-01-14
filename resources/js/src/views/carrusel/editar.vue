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
            <h2 class="text-xl font-semibold">Editar Elemento del Carrusel</h2>
        </div>

        <div v-if="loading" class="flex justify-center py-10">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
        </div>

        <div v-else-if="form.id" class="panel">
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
                                placeholder="Título del carrusel"
                                required
                            />
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
                                placeholder="Subtítulo opcional"
                                rows="3"
                            ></textarea>
                        </div>

                        <!-- Posición del contenido -->
                        <div>
                            <label for="posicion_contenido" class="form-label required">Posición del Contenido</label>
                            <select
                                id="posicion_contenido"
                                v-model="form.posicion_contenido"
                                class="form-select"
                                required
                            >
                                <option value="">Selecciona una posición</option>
                                <option value="Izquierda">Izquierda</option>
                                <option value="Derecha">Derecha</option>
                            </select>
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

                    <!-- Configuración de botón -->
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
                        </div>

                        <!-- URL del botón -->
                        <div v-if="form.activar_boton">
                            <label for="url_boton" class="form-label">URL del Botón</label>
                            <input
                                id="url_boton"
                                v-model="form.url_boton"
                                type="url"
                                class="form-input"
                                placeholder="https://ejemplo.com"
                            />
                        </div>

                        <!-- Redirigir en la misma página -->
                        <div v-if="form.activar_boton">
                            <label for="redirigir_misma_pagina" class="flex items-center cursor-pointer">
                                <input
                                    id="redirigir_misma_pagina"
                                    v-model="form.redirigir_misma_pagina"
                                    type="checkbox"
                                    class="form-checkbox"
                                />
                                <span class="ml-2">Abrir en la misma página</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Imagen -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold border-b pb-2">Imagen del Carrusel</h3>

                    <!-- Imagen actual -->
                    <div v-if="form.imagen_url && !newImageSelected" class="mb-4">
                        <label class="form-label">Imagen Actual</label>
                        <div class="flex items-start gap-4">
                            <div class="w-32 h-24 rounded-lg overflow-hidden bg-gray-100 border">
                                <img
                                    :src="form.imagen_url"
                                    :alt="form.titulo"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-2">
                                    Esta es la imagen actual del carrusel. Puedes cambiarla seleccionando una nueva imagen.
                                </p>
                                <button
                                    type="button"
                                    @click="showImageUpload"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Cambiar Imagen
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Upload de nueva imagen -->
                    <div v-if="!form.imagen_url || newImageSelected">
                        <label class="form-label">{{ form.imagen_url ? 'Nueva Imagen' : 'Imagen' }}</label>
                        <div
                            class="custom-file-container"
                            data-upload-id="carruselImageEdit"
                        >
                            <div class="label-container">
                                <label>{{ form.imagen_url ? 'Subir nueva imagen' : 'Subir imagen' }}</label>
                                <a
                                    href="javascript:;"
                                    class="custom-file-container__image-clear"
                                    title="Limpiar imagen"
                                    @click="cancelImageChange"
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
                        <div v-if="form.imagen_url && newImageSelected" class="mt-2">
                            <button
                                type="button"
                                @click="cancelImageChange"
                                class="btn btn-sm btn-outline-secondary"
                            >
                                Mantener imagen actual
                            </button>
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
                        :disabled="submitting"
                        class="btn btn-primary"
                        :class="{ 'opacity-50 cursor-not-allowed': submitting }"
                    >
                        <div v-if="submitting" class="flex items-center gap-2">
                            <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                            Actualizando...
                        </div>
                        <span v-else>Actualizar Carrusel</span>
                    </button>
                </div>
            </form>
        </div>

        <div v-else class="text-center py-10">
            <p class="text-gray-500">No se pudo cargar el carrusel.</p>
            <router-link to="/administrador/carrusel" class="btn btn-primary mt-4">
                Volver al listado
            </router-link>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import Swal from 'sweetalert2'
import FileUploadWithPreview from "file-upload-with-preview";

// Interfaces locales
interface CarruselFormData {
    id: number | null
    titulo: string
    subtitulo: string
    activar_subtitulo: boolean
    activar_boton: boolean
    url_boton: string
    redirigir_misma_pagina: boolean
    posicion_contenido: string
    imagen: File | null
    imagen_url: string
    estado: boolean
}

interface ValidationErrors {
    [key: string]: string[]
}

// Router
const router = useRouter()
const route = useRoute()

// Estado reactivo
const loading = ref(true)
const submitting = ref(false)
const errors = ref<ValidationErrors>({})
const newImageSelected = ref(false)
const imageUpload = ref<any>(null)

const form = reactive<CarruselFormData>({
    id: null,
    titulo: '',
    subtitulo: '',
    activar_subtitulo: true,
    activar_boton: false,
    url_boton: '',
    redirigir_misma_pagina: false,
    posicion_contenido: '',
    imagen: null,
    imagen_url: '',
    estado: true
})

// Métodos
const loadCarrusel = async () => {
    try {
        const response = await fetch(`/tienda/public/api/carrusel/${route.params.id}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
            }
        })

        const data = await response.json()

        if (data.success) {
            Object.assign(form, data.data)
        } else {
            throw new Error(data.message)
        }
    } catch (error: any) {
        console.error('Error al cargar carrusel:', error)
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo cargar el carrusel'
        }).then(() => {
            router.push('/administrador/carrusel')
        })
    } finally {
        loading.value = false
    }
}

const showImageUpload = () => {
    newImageSelected.value = true
    // Inicializar el componente de subida
    setTimeout(() => {
        if (!imageUpload.value) {
            imageUpload.value = new FileUploadWithPreview('carruselImageEdit', {
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
        }
    }, 100)
}

const cancelImageChange = () => {
    newImageSelected.value = false
    form.imagen = null
    if (imageUpload.value) {
        imageUpload.value.clearPreviewPanel()
    }
}

const handleImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        form.imagen = target.files[0]
        newImageSelected.value = true
    }
}

const submitForm = async () => {
    submitting.value = true
    errors.value = {}

    try {
        const formData = new FormData()

        // Agregar todos los campos al FormData
        formData.append('titulo', form.titulo)
        formData.append('subtitulo', form.subtitulo || '')
        formData.append('activar_subtitulo', form.activar_subtitulo ? '1' : '0')
        formData.append('activar_boton', form.activar_boton ? '1' : '0')
        formData.append('url_boton', form.url_boton || '')
        formData.append('redirigir_misma_pagina', form.redirigir_misma_pagina ? '1' : '0')
        formData.append('posicion_contenido', form.posicion_contenido)
        formData.append('estado', form.estado ? '1' : '0')
        formData.append('_method', 'PUT')

        // Solo agregar imagen si se seleccionó una nueva
        if (form.imagen) {
            formData.append('imagen', form.imagen)
        }

        const response = await fetch(`/tienda/public/api/carrusel/${form.id}`, {
            method: 'POST', // Usar POST con _method para manejar archivos
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
                text: 'Carrusel actualizado exitosamente',
                timer: 2000,
                showConfirmButton: false
            })
            router.push('/administrador/carrusel')
        } else {
            if (data.errors) {
                if (typeof data.errors === 'object') {
                    errors.value = data.errors
                } else {
                    throw new Error(data.message || 'Error de validación')
                }
            } else {
                throw new Error(data.message || 'Error al actualizar el carrusel')
            }
        }
    } catch (error: any) {
        console.error('Error al actualizar carrusel:', error)
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo actualizar el carrusel'
        })
    } finally {
        submitting.value = false
    }
}

// Lifecycle
onMounted(() => {
    loadCarrusel()
})
</script>

<style>
.custom-file-container {
    border: 2px dashed #d1d5db;
    border-radius: 0.5rem;
    padding: 1rem;
}

.form-label.required::after {
    content: ' *';
    color: #ef4444;
}
</style>
