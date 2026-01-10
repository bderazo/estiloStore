<template>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h2 class="text-xl font-semibold">Gestión de Carrusel</h2>
            <div class="flex items-center gap-3">
                <router-link
                    to="/administrador/carrusel/crear"
                    class="btn btn-primary gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Agregar Carrusel
                </router-link>
            </div>
        </div>

        <div class="panel">
            <!-- Filtros -->
            <div class="mb-5">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1">
                        <input
                            v-model="filters.search"
                            type="text"
                            class="form-input"
                            placeholder="Buscar por título..."
                            @input="search"
                        />
                    </div>
                    <div>
                        <select v-model="filters.estado" class="form-select" @change="search">
                            <option value="">Todos los estados</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                    <div>
                        <select v-model="filters.per_page" class="form-select" @change="search">
                            <option value="10">10 por página</option>
                            <option value="25">25 por página</option>
                            <option value="50">50 por página</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="flex justify-center py-10">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            </div>

            <!-- Tabla -->
            <div v-else-if="carruseles.length > 0" class="table-responsive">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Subtítulo</th>
                            <th>Posición</th>
                            <th>Estado</th>
                            <th>Creado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="carrusel in carruseles" :key="carrusel.id">
                            <td>
                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100">
                                    <img
                                        v-if="carrusel.imagen_url"
                                        :src="carrusel.imagen_url"
                                        :alt="carrusel.titulo"
                                        class="w-full h-full object-cover"
                                    />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-semibold">{{ carrusel.titulo }}</div>
                                <div v-if="carrusel.activar_boton && carrusel.url_boton" class="text-xs text-gray-500">
                                    <a :href="carrusel.url_boton" target="_blank" class="text-primary hover:underline">
                                        Ver enlace
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div v-if="carrusel.activar_subtitulo && carrusel.subtitulo" class="text-sm">
                                    {{ carrusel.subtitulo }}
                                </div>
                                <span v-else class="text-gray-400 text-sm">Sin subtítulo</span>
                            </td>
                            <td>
                                <span class="badge badge-outline-primary">
                                    {{ carrusel.posicion_contenido }}
                                </span>
                            </td>
                            <td>
                                <button
                                    @click="toggleEstado(carrusel)"
                                    :class="{
                                        'badge badge-outline-success': carrusel.estado,
                                        'badge badge-outline-danger': !carrusel.estado
                                    }"
                                    class="cursor-pointer"
                                >
                                    {{ carrusel.estado ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>
                            <td>
                                <div class="text-sm">{{ formatDate(carrusel.created_at) }}</div>
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <router-link
                                        :to="`/administrador/carrusel/${carrusel.id}/editar`"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Editar"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </router-link>
                                    <button
                                        @click="deleteCarrusel(carrusel)"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Eliminar"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Sin datos -->
            <div v-else class="text-center py-10">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1l-.867 10.142A2 2 0 0118.138 18H5.862a2 2 0 01-1.995-1.858L3 6H2a1 1 0 110-2h4zM7 6v10a1 1 0 102 0V6a1 1 0 10-2 0zm6 0v10a1 1 0 102 0V6a1 1 0 10-2 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay carruseles</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo elemento del carrusel.</p>
                <div class="mt-6">
                    <router-link
                        to="/administrador/carrusel/crear"
                        class="btn btn-primary"
                    >
                        Crear Carrusel
                    </router-link>
                </div>
            </div>

            <!-- Paginación -->
            <div v-if="pagination.total > 0" class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Mostrando {{ pagination.from }} a {{ pagination.to }} de {{ pagination.total }} resultados
                </div>
                <div class="flex items-center gap-2">
                    <button
                        @click="changePage(pagination.current_page - 1)"
                        :disabled="pagination.current_page <= 1"
                        class="btn btn-sm btn-outline-primary"
                        :class="{ 'opacity-50 cursor-not-allowed': pagination.current_page <= 1 }"
                    >
                        Anterior
                    </button>
                    <span class="px-3 py-1 text-sm bg-primary text-white rounded">
                        {{ pagination.current_page }}
                    </span>
                    <button
                        @click="changePage(pagination.current_page + 1)"
                        :disabled="pagination.current_page >= pagination.last_page"
                        class="btn btn-sm btn-outline-primary"
                        :class="{ 'opacity-50 cursor-not-allowed': pagination.current_page >= pagination.last_page }"
                    >
                        Siguiente
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

// Interfaces locales
interface Carrusel {
    id: number
    titulo: string
    subtitulo: string | null
    activar_subtitulo: boolean
    activar_boton: boolean
    url_boton: string | null
    redirigir_misma_pagina: boolean
    posicion_contenido: 'Izquierda' | 'Derecha'
    imagen: string | null
    imagen_url: string | null
    estado: boolean
    created_at: string
    updated_at: string
}

interface CarruselFilters {
    search: string
    estado: string
    per_page: number
    page: number
}

interface CarruselPagination {
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
}

// Router
const router = useRouter()

// Estado reactivo
const loading = ref(false)
const carruseles = ref<Carrusel[]>([])
const pagination = ref<CarruselPagination>({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
    from: 0,
    to: 0
})

const filters = reactive<CarruselFilters>({
    search: '',
    estado: '',
    per_page: 10,
    page: 1
})

// Métodos
const loadCarruseles = async () => {
    loading.value = true
    try {
        const params = new URLSearchParams()

        if (filters.search) params.append('search', filters.search)
        if (filters.estado !== '') params.append('estado', filters.estado)
        params.append('per_page', filters.per_page.toString())
        params.append('page', filters.page.toString())

        const response = await fetch(`/api/carrusel?${params}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
            }
        })

        const data = await response.json()

        if (data.success) {
            carruseles.value = data.data
            pagination.value = data.pagination
        } else {
            throw new Error(data.message)
        }
    } catch (error: any) {
        console.error('Error al cargar carruseles:', error)
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar los carruseles'
        })
    } finally {
        loading.value = false
    }
}

const search = () => {
    filters.page = 1
    loadCarruseles()
}

const changePage = (page: number) => {
    if (page >= 1 && page <= pagination.value.last_page) {
        filters.page = page
        loadCarruseles()
    }
}

const toggleEstado = async (carrusel: Carrusel) => {
    const result = await Swal.fire({
        title: `¿${carrusel.estado ? 'Desactivar' : 'Activar'} carrusel?`,
        text: `¿Estás seguro de que quieres ${carrusel.estado ? 'desactivar' : 'activar'} este elemento del carrusel?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Sí, ${carrusel.estado ? 'desactivar' : 'activar'}`,
        cancelButtonText: 'Cancelar'
    })

    if (result.isConfirmed) {
        try {
            const response = await fetch(`/api/carrusel/${carrusel.id}/toggle-estado`, {
                method: 'PATCH',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })

            const data = await response.json()

            if (data.success) {
                await loadCarruseles()
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                })
            } else {
                throw new Error(data.message)
            }
        } catch (error: any) {
            console.error('Error al cambiar estado:', error)
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cambiar el estado del carrusel'
            })
        }
    }
}

const deleteCarrusel = async (carrusel: Carrusel) => {
    const result = await Swal.fire({
        title: '¿Eliminar carrusel?',
        text: '¿Estás seguro de que quieres eliminar este elemento del carrusel? Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    })

    if (result.isConfirmed) {
        try {
            const response = await fetch(`/api/carrusel/${carrusel.id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json'
                }
            })

            const data = await response.json()

            if (data.success) {
                await loadCarruseles()
                Swal.fire({
                    icon: 'success',
                    title: 'Eliminado',
                    text: 'El carrusel ha sido eliminado exitosamente',
                    timer: 2000,
                    showConfirmButton: false
                })
            } else {
                throw new Error(data.message)
            }
        } catch (error: any) {
            console.error('Error al eliminar carrusel:', error)
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar el carrusel'
            })
        }
    }
}

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

// Lifecycle
onMounted(() => {
    loadCarruseles()
})
</script>

<style scoped>
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.dark .table-hover tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.02);
}
</style>
