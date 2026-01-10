<template>
  <div>
    <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
      <li>
        <button class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
          <IconHome class="w-4 h-4" />
        </button>
      </li>
      <li class="before:content-['/'] before:px-1.5">
        <span class="text-black dark:text-white-light">Administración</span>
      </li>
      <li class="before:content-['/'] before:px-1.5">
        <span>Tallas</span>
      </li>
    </ol>

    <div class="panel mt-6">
      <div class="flex items-center justify-between mb-6">
        <h5 class="font-semibold text-lg dark:text-white-light">
          Gestión de Tallas
        </h5>
        <button
          @click="openCreateModal"
          class="btn btn-primary gap-2"
          type="button"
        >
          <IconPlus class="w-4 h-4" />
          Nueva Talla
        </button>
      </div>

      <!-- Filtros -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Búsqueda -->
        <div class="relative">
          <input
            v-model="filters.search"
            @input="debounceSearch"
            type="text"
            placeholder="Buscar por nombre o descripción..."
            class="form-input pl-10"
          />
          <IconSearch class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
        </div>

        <!-- Estado -->
        <select v-model="filters.activo" @change="applyFilters" class="form-select">
          <option value="">Todos los estados</option>
          <option :value="true">Activos</option>
          <option :value="false">Inactivos</option>
        </select>

        <!-- Ordenar por -->
        <select v-model="filters.sort_by" @change="applyFilters" class="form-select">
          <option value="nombre">Ordenar por Nombre</option>
          <option value="descripcion">Ordenar por Descripción</option>
          <option value="created_at">Ordenar por Fecha</option>
        </select>

        <!-- Orden -->
        <select v-model="filters.sort_order" @change="applyFilters" class="form-select">
          <option value="asc">Ascendente</option>
          <option value="desc">Descendente</option>
        </select>
      </div>

      <!-- Estadísticas -->
     <!--  <div v-if="!loading && hasTallas" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-primary-light rounded-lg p-4 text-center">
          <div class="text-2xl font-bold text-primary">{{ estadisticas.total }}</div>
          <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
        </div>
        <div class="bg-green-100 dark:bg-green-900 rounded-lg p-4 text-center">
          <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ estadisticas.activas }}</div>
          <div class="text-sm text-gray-600 dark:text-gray-400">Activas</div>
        </div>
        <div class="bg-red-100 dark:bg-red-900 rounded-lg p-4 text-center">
          <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ estadisticas.inactivas }}</div>
          <div class="text-sm text-gray-600 dark:text-gray-400">Inactivas</div>
        </div>
        <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-4 text-center">
          <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ estadisticas.conDescripcion }}</div>
          <div class="text-sm text-gray-600 dark:text-gray-400">Con Descripción</div>
        </div>
      </div> -->

      <!-- Tabla de tallas -->
      <div class="datatables" v-if="!loading">
        <div class="table-responsive">
          <table class="table-hover">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Fecha Creación</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="talla in tallas" :key="talla.id">
                <!-- Nombre -->
                <td>
                  <div class="font-semibold">{{ talla.nombre }}</div>
                  <div v-if="talla.nombre_length" class="text-xs text-gray-500">
                    {{ talla.nombre_length }} caracteres
                  </div>
                </td>

                <!-- Descripción -->
                <td>
                  <div v-if="talla.descripcion" class="text-sm">
                    {{ talla.descripcion }}
                    <div class="text-xs text-gray-500 mt-1">
                      {{ talla.descripcion_length }} caracteres
                    </div>
                  </div>
                  <div v-else class="text-gray-400 italic text-sm">
                    Sin descripción
                  </div>
                </td>

                <!-- Estado -->
                <td>
                  <span
                    :class="[
                      'badge',
                      talla.activo ? 'badge-outline-success' : 'badge-outline-danger'
                    ]"
                  >
                    {{ talla.estado_texto }}
                  </span>
                </td>

                <!-- Fecha -->
                <td>
                  <div class="text-sm">
                    {{ talla.created_at_formatted }}
                  </div>
                  <div class="text-xs text-gray-500">
                    {{ talla.created_ago }}
                  </div>
                </td>

                <!-- Acciones -->
                <td class="text-center">
                  <div class="flex items-center justify-center gap-2">
                    <!-- Toggle estado -->
                    <button
                      @click="toggleTallaStatus(talla.id)"
                      :class="[
                        'btn btn-sm',
                        talla.activo ? 'btn-warning' : 'btn-success'
                      ]"
                      :title="talla.activo ? 'Desactivar' : 'Activar'"
                    >
                      <IconPower class="w-4 h-4" />
                    </button>

                    <!-- Editar -->
                    <button
                      @click="openEditModal(talla)"
                      class="btn btn-sm btn-primary"
                      title="Editar"
                    >
                      <IconEdit class="w-4 h-4" />
                    </button>

                    <!-- Eliminar -->
                    <button
                      @click="deleteTalla(talla.id)"
                      class="btn btn-sm btn-danger"
                      title="Eliminar"
                    >
                      <IconTrash class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Paginación -->
        <div v-if="pagination.total > 0" class="flex items-center justify-between mt-4">
          <div class="text-sm text-gray-500">
            Mostrando {{ pagination.from }} a {{ pagination.to }} de {{ pagination.total }} registros
          </div>

          <div class="flex items-center gap-2">
            <!-- Registros por página -->
            <select
              v-model="filters.per_page"
              @change="applyFilters"
              class="form-select text-sm w-20"
            >
              <option :value="10">10</option>
              <option :value="15">15</option>
              <option :value="25">25</option>
              <option :value="50">50</option>
            </select>

            <!-- Navegación -->
            <div class="flex">
              <button
                @click="changePage(pagination.current_page - 1)"
                :disabled="pagination.current_page <= 1"
                class="btn btn-sm btn-outline-primary disabled:opacity-50"
              >
                Anterior
              </button>
              <button
                @click="changePage(pagination.current_page + 1)"
                :disabled="pagination.current_page >= pagination.last_page"
                class="btn btn-sm btn-outline-primary disabled:opacity-50 ml-1"
              >
                Siguiente
              </button>
            </div>
          </div>
        </div>

        <!-- Estado vacío -->
        <div v-if="tallas.length === 0" class="text-center py-8">
          <div class="text-gray-500 mb-4">
            <IconRuler class="w-16 h-16 mx-auto mb-2 opacity-30" />
            <p>No se encontraron tallas</p>
          </div>
          <button @click="openCreateModal" class="btn btn-primary">
            Crear primera talla
          </button>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
      </div>
    </div>

    <!-- Modal de formulario -->
    <TallaFormModal
      v-if="showModal"
      :visible="showModal"
      :talla="selectedTalla"
      @close="closeModal"
      @saved="handleTallaSaved"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useTallaStore } from '../../stores/tallaStore';
import type { Talla, TallaFilters } from '../../types/talla';
import TallaFormModal from './TallaFormModal.vue';

// Icons
import {
  IconHome,
  IconPlus,
  IconSearch,
  IconEdit,
  IconTrash,
  IconPower,
  IconRuler,
} from '@tabler/icons-vue';

// Store
const tallaStore = useTallaStore();
const { tallas, loading, error, pagination, estadisticas, hasTallas } = storeToRefs(tallaStore);

// Estados locales
const showModal = ref(false);
const selectedTalla = ref<Talla | null>(null);
const searchTimeout = ref<number | null>(null);

// Filtros reactivos
const filters = ref<TallaFilters>({
  search: '',
  activo: '',
  sort_by: 'nombre',
  sort_order: 'asc',
  per_page: 15,
});

// Métodos
const loadTallas = async () => {
  await tallaStore.fetchTallas(filters.value);
};

const applyFilters = () => {
  loadTallas();
};

const debounceSearch = () => {
  if (searchTimeout.value) {
    clearTimeout(searchTimeout.value);
  }

  searchTimeout.value = setTimeout(() => {
    applyFilters();
  }, 500);
};

const changePage = (page: number) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    filters.value = { ...filters.value, current_page: page };
    loadTallas();
  }
};

const openCreateModal = () => {
  selectedTalla.value = null;
  showModal.value = true;
};

const openEditModal = (talla: Talla) => {
  selectedTalla.value = talla;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedTalla.value = null;
};

const handleTallaSaved = () => {
  closeModal();
  loadTallas();
};

const toggleTallaStatus = async (id: number) => {
  await tallaStore.toggleActivo(id);
};

const deleteTalla = async (id: number) => {
  await tallaStore.deleteTalla(id);
};

// Lifecycle
onMounted(() => {
  loadTallas();
});
</script>

<style scoped>
.table-hover tbody tr:hover {
  background-color: rgba(0, 0, 0, 0.05);
}

.dark .table-hover tbody tr:hover {
  background-color: rgba(255, 255, 255, 0.05);
}

.badge {
  display: inline-flex;
  align-items: center;
  padding: 0.125rem 0.625rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.badge-outline-success {
  color: rgb(34 197 94);
  background-color: rgb(240 253 244);
  border: 1px solid rgb(187 247 208);
}

.badge-outline-danger {
  color: rgb(239 68 68);
  background-color: rgb(254 242 242);
  border: 1px solid rgb(252 165 165);
}

.dark .badge-outline-success {
  color: rgb(187 247 208);
  background-color: rgb(20 83 45);
  border-color: rgb(34 197 94);
}

.dark .badge-outline-danger {
  color: rgb(252 165 165);
  background-color: rgb(127 29 29);
  border-color: rgb(239 68 68);
}
</style>
