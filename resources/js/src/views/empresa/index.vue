<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Datos de Empresa</h1>
        <p class="text-gray-600 mt-1">Administra la información de la empresa</p>
      </div>
      <router-link
        to="/administrador/empresa/crear"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
      >
        <PlusIcon class="w-5 h-5" />
        Nuevo Dato
      </router-link>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Buscar -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
          <div class="relative">
            <SearchIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
            <input
              v-model="filters.search"
              type="text"
              placeholder="Título, contenido o clave..."
              class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              @input="debouncedSearch"
            />
          </div>
        </div>

        <!-- Estado -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
          <select
            v-model="filters.activo"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            @change="fetchData"
          >
            <option :value="null">Todos</option>
            <option :value="true">Activo</option>
            <option :value="false">Inactivo</option>
          </select>
        </div>

        <!-- Ordenar por -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Ordenar por</label>
          <select
            v-model="filters.sort_by"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            @change="fetchData"
          >
            <option value="orden">Orden</option>
            <option value="titulo">Título</option>
            <option value="clave">Clave</option>
            <option value="created_at">Fecha creación</option>
          </select>
        </div>

        <!-- Dirección -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
          <select
            v-model="filters.sort_order"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            @change="fetchData"
          >
            <option value="asc">Ascendente</option>
            <option value="desc">Descendente</option>
          </select>
        </div>
      </div>

      <!-- Botón limpiar filtros -->
      <div class="mt-4 flex justify-end">
        <button
          @click="limpiarFiltros"
          class="text-sm text-gray-600 hover:text-gray-800 flex items-center gap-1"
        >
          <XMarkIcon class="w-4 h-4" />
          Limpiar filtros
        </button>
      </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Total de Datos</p>
            <p class="text-2xl font-bold text-gray-800">{{ store.paginationMeta.total }}</p>
          </div>
          <div class="bg-blue-100 p-3 rounded-full">
            <DocumentTextIcon class="w-6 h-6 text-blue-600" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Activos</p>
            <p class="text-2xl font-bold text-green-600">
              {{ empresaDatos.filter(d => d.activo).length }}
            </p>
          </div>
          <div class="bg-green-100 p-3 rounded-full">
            <CheckCircleIcon class="w-6 h-6 text-green-600" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Inactivos</p>
            <p class="text-2xl font-bold text-red-600">
              {{ empresaDatos.filter(d => !d.activo).length }}
            </p>
          </div>
          <div class="bg-red-100 p-3 rounded-full">
            <XCircleIcon class="w-6 h-6 text-red-600" />
          </div>
        </div>
      </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <!-- Loading -->
      <div v-if="store.loading" class="p-8 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
        <p class="mt-4 text-gray-600">Cargando datos...</p>
      </div>

      <!-- Error -->
      <div v-else-if="store.error" class="p-8 text-center">
        <div class="bg-red-50 border border-red-200 rounded-md p-6 max-w-md mx-auto">
          <ExclamationTriangleIcon class="w-12 h-12 text-red-500 mx-auto mb-4" />
          <p class="text-red-600 font-medium mb-2">{{ store.error }}</p>
          <button
            @click="fetchData"
            class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors"
          >
            Reintentar
          </button>
        </div>
      </div>

      <!-- Tabla de datos -->
      <div v-else>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  ID
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Clave / Título
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Imagen
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Estado
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Orden
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Fecha
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="item in empresaDatos" :key="item.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm font-mono text-gray-500">#{{ item.id }}</span>
                </td>
                <td class="px-6 py-4">
                  <div class="space-y-1">
                    <div class="flex items-center gap-2">
                      <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                        {{ item.clave }}
                      </span>
                      <span class="text-xs text-gray-500">{{ item.clave_label }}</span>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-900 truncate max-w-md">
                        {{ item.titulo || 'Sin título' }}
                      </p>
                      <p v-if="item.contenido" class="text-xs text-gray-500 truncate max-w-md mt-1">
                        {{ truncateText(item.contenido, 60) }}
                      </p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-if="item.imagen_url" class="relative group">
                    <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                      <img
                        :src="item.imagen_url"
                        :alt="item.titulo || 'Imagen'"
                        class="w-full h-full object-cover"
                      />
                    </div>
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                      <EyeIcon class="w-5 h-5 text-white" />
                    </div>
                  </div>
                  <span v-else class="text-sm text-gray-400 italic">Sin imagen</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button
                    @click="toggleEstado(item.id)"
                    :class="[
                      'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-all',
                      item.activo
                        ? 'bg-green-100 text-green-800 hover:bg-green-200'
                        : 'bg-red-100 text-red-800 hover:bg-red-200'
                    ]"
                  >
                    <PowerIcon class="w-3 h-3 mr-1" />
                    {{ item.activo_label }}
                  </button>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm font-medium text-gray-900">{{ item.orden }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500 space-y-1">
                    <div class="flex items-center gap-1">
                      <CalendarIcon class="w-3 h-3" />
                      {{ formatDate(item.created_at) }}
                    </div>
                    <div v-if="item.updated_at !== item.created_at" class="text-xs text-gray-400">
                      Editado: {{ formatDate(item.updated_at) }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-2">
                    <router-link
                      :to="`/administrador/empresa/${item.id}/editar`"
                      class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                      title="Editar"
                    >
                      <PencilSquareIcon class="w-5 h-5" />
                    </router-link>
                    
                    <button
                      @click="mostrarModalEliminar(item)"
                      class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                      title="Eliminar"
                    >
                      <TrashIcon class="w-5 h-5" />
                    </button>
                    
                    <button
                      @click="copiarClave(item.clave)"
                      class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-lg transition-colors"
                      title="Copiar clave"
                    >
                      <ClipboardIcon class="w-5 h-5" />
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Sin datos -->
              <tr v-if="empresaDatos.length === 0">
                <td colspan="7" class="px-6 py-16 text-center">
                  <div class="space-y-3">
                    <DocumentTextIcon class="w-16 h-16 text-gray-300 mx-auto" />
                    <div>
                      <p class="text-lg font-medium text-gray-500">No hay datos de empresa</p>
                      <p class="text-gray-400 mt-1">Comienza creando uno nuevo</p>
                    </div>
                    <router-link
                      to="/empresa-datos/crear"
                      class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium"
                    >
                      <PlusIcon class="w-5 h-5" />
                      Crear primer dato
                    </router-link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Paginación -->
        <div v-if="store.paginationMeta.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
          <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
              Mostrando
              <span class="font-medium">{{ (store.paginationMeta.current_page - 1) * store.paginationMeta.per_page + 1 }}</span>
              a
              <span class="font-medium">{{ Math.min(store.paginationMeta.current_page * store.paginationMeta.per_page, store.paginationMeta.total) }}</span>
              de
              <span class="font-medium">{{ store.paginationMeta.total }}</span>
              resultados
            </div>
            
            <div class="flex items-center gap-4">
              <!-- Elementos por página -->
              <div class="flex items-center gap-2">
                <span class="text-sm text-gray-700">Por página:</span>
                <select
                  v-model="filters.per_page"
                  @change="cambiarElementosPorPagina"
                  class="text-sm border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                  <option value="10">10</option>
                  <option value="15">15</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                </select>
              </div>

              <!-- Navegación -->
              <div class="flex gap-2">
                <button
                  @click="cambiarPagina(store.paginationMeta.current_page - 1)"
                  :disabled="store.paginationMeta.current_page === 1"
                  :class="[
                    'px-3 py-1 rounded border flex items-center gap-1 transition-colors',
                    store.paginationMeta.current_page === 1
                      ? 'text-gray-400 border-gray-300 cursor-not-allowed'
                      : 'text-gray-700 border-gray-300 hover:bg-gray-100'
                  ]"
                >
                  <ChevronLeftIcon class="w-4 h-4" />
                  Anterior
                </button>
                
                <!-- Números de página -->
                <div class="flex gap-1">
                  <button
                    v-for="page in paginas"
                    :key="page"
                    @click="cambiarPagina(page)"
                    :class="[
                      'w-8 h-8 rounded flex items-center justify-center text-sm transition-colors',
                      page === store.paginationMeta.current_page
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 border border-gray-300 hover:bg-gray-50'
                    ]"
                  >
                    {{ page }}
                  </button>
                </div>
                
                <button
                  @click="cambiarPagina(store.paginationMeta.current_page + 1)"
                  :disabled="store.paginationMeta.current_page === store.paginationMeta.last_page"
                  :class="[
                    'px-3 py-1 rounded border flex items-center gap-1 transition-colors',
                    store.paginationMeta.current_page === store.paginationMeta.last_page
                      ? 'text-gray-400 border-gray-300 cursor-not-allowed'
                      : 'text-gray-700 border-gray-300 hover:bg-gray-100'
                  ]"
                >
                  Siguiente
                  <ChevronRightIcon class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Confirmación de Eliminación -->
  <TransitionRoot appear :show="showDeleteModal" as="template">
    <Dialog as="div" @close="cerrarModalEliminar" class="relative z-50">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <ExclamationTriangleIcon class="w-6 h-6 text-red-600" />
              </div>
              
              <DialogTitle as="h3" class="text-lg font-medium text-gray-900 text-center mb-2">
                ¿Eliminar dato?
              </DialogTitle>
              
              <div class="mt-2 text-center">
                <p class="text-sm text-gray-500">
                  ¿Estás seguro de eliminar el dato "<span class="font-medium">{{ datoAEliminar?.titulo || datoAEliminar?.clave_label }}</span>"?
                </p>
                <p class="text-xs text-gray-400 mt-2">
                  Esta acción no se puede deshacer.
                </p>
              </div>

              <div class="mt-6 flex justify-center gap-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                  @click="cerrarModalEliminar"
                >
                  Cancelar
                </button>
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                  @click="confirmarEliminar"
                  :disabled="eliminando"
                >
                  <span v-if="eliminando" class="flex items-center gap-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                    Eliminando...
                  </span>
                  <span v-else>Eliminar</span>
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Toast de confirmación -->
  <div v-if="showToast" class="fixed bottom-4 right-4 z-50">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg animate-slide-up">
      <div class="flex items-center gap-2">
        <CheckCircleIcon class="w-5 h-5" />
        <span>{{ toastMessage }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useEmpresaDatoStore } from '../../stores/empresa-dato'
import { EmpresaDato, EmpresaDatoFilter } from '../../types/empresa-dato'
import { 
  TransitionRoot, 
  TransitionChild, 
  Dialog, 
  DialogPanel, 
  DialogTitle 
} from '@headlessui/vue'
import debounce from 'lodash/debounce'
import {
  PlusIcon,
  StarIcon,
  XMarkIcon,
  DocumentTextIcon,
  CheckCircleIcon,
  XCircleIcon,
  ExclamationTriangleIcon,
  PowerIcon,
  PencilSquareIcon,
  TrashIcon,
  ClipboardIcon,
  CalendarIcon,
  EyeIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  PencilIcon
} from '@heroicons/vue/24/outline'

// Store y Router
const store = useEmpresaDatoStore()
const router = useRouter()

// Estado
const filters = ref<EmpresaDatoFilter>({
  search: '',
  activo: null,
  sort_by: 'orden',
  sort_order: 'asc',
  page: 1,
  per_page: 15
})

const showDeleteModal = ref(false)
const datoAEliminar = ref<EmpresaDato | null>(null)
const eliminando = ref(false)
const showToast = ref(false)
const toastMessage = ref('')

// Computed
const empresaDatos = computed(() => store.empresaDatos)
const paginas = computed(() => {
  const meta = store.paginationMeta
  const current = meta.current_page
  const last = meta.last_page
  const delta = 2
  const range = []
  const rangeWithDots = []
  
  for (let i = 1; i <= last; i++) {
    if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
      range.push(i)
    }
  }
  
  let prev = 0
  for (const i of range) {
    if (prev !== i - 1) {
      rangeWithDots.push('...')
    }
    rangeWithDots.push(i)
    prev = i
  }
  
  return rangeWithDots.filter(p => typeof p === 'number')
})

// Métodos
const fetchData = async () => {
  await store.fetchEmpresaDatos(filters.value)
}

const debouncedSearch = debounce(() => {
  filters.value.page = 1
  fetchData()
}, 500)

const cambiarPagina = (page: number) => {
  if (page >= 1 && page <= store.paginationMeta.last_page) {
    filters.value.page = page
    fetchData()
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const cambiarElementosPorPagina = () => {
  filters.value.page = 1
  fetchData()
}

const limpiarFiltros = () => {
  filters.value = {
    search: '',
    activo: null,
    sort_by: 'orden',
    sort_order: 'asc',
    page: 1,
    per_page: 15
  }
  fetchData()
}

const toggleEstado = async (id: number) => {
  try {
    await store.toggleActivo(id)
    mostrarToast('Estado actualizado correctamente')
  } catch (error) {
    mostrarToast('Error al cambiar el estado', 'error')
  }
}

const mostrarModalEliminar = (dato: EmpresaDato) => {
  datoAEliminar.value = dato
  showDeleteModal.value = true
}

const cerrarModalEliminar = () => {
  showDeleteModal.value = false
  setTimeout(() => {
    datoAEliminar.value = null
    eliminando.value = false
  }, 300)
}

const confirmarEliminar = async () => {
  if (!datoAEliminar.value) return
  
  eliminando.value = true
  try {
    await store.deleteEmpresaDato(datoAEliminar.value.id)
    cerrarModalEliminar()
    mostrarToast('Dato eliminado correctamente')
  } catch (error) {
    mostrarToast('Error al eliminar el dato', 'error')
    eliminando.value = false
  }
}

const copiarClave = (clave: string) => {
  navigator.clipboard.writeText(clave)
  mostrarToast('Clave copiada al portapapeles')
}

const mostrarToast = (mensaje: string, tipo: 'success' | 'error' = 'success') => {
  toastMessage.value = mensaje
  showToast.value = true
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

const truncateText = (text: string, length: number) => {
  if (text.length <= length) return text
  return text.substring(0, length) + '...'
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

// Lifecycle
onMounted(async () => {
  await fetchData()
  await store.fetchClavesDisponibles()
})

// Watch para cambios en los filtros
watch(
  () => [filters.value.activo, filters.value.sort_by, filters.value.sort_order],
  () => {
    filters.value.page = 1
    fetchData()
  }
)
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