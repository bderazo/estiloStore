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
        <span>Plazas</span>
      </li>
    </ol>

    <div class="panel mt-6">
      <div class="flex items-center justify-between mb-6">
        <h5 class="font-semibold text-lg dark:text-white-light">
          Gestión de Plazas
        </h5>
        <button
          @click="openCreateModal"
          class="btn btn-primary gap-2"
          type="button"
        >
          <IconPlus class="w-4 h-4" />
          Nueva Plaza
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
            placeholder="Buscar por nombre..."
            class="form-input pl-10"
          />
          <IconSearch class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
        </div>

        <!-- Estado -->
        <select v-model="filters.activo" @change="applyFilters" class="form-select">
          <option value="">Todos los estados</option>
          <option :value="true">Activas</option>
          <option :value="false">Inactivas</option>
        </select>

        <!-- Ordenar por -->
        <select v-model="filters.sort_by" @change="applyFilters" class="form-select">
          <option value="nombre">Ordenar por Nombre</option>
          <option value="created_at">Ordenar por Fecha</option>
        </select>

        <!-- Orden -->
        <select v-model="filters.sort_order" @change="applyFilters" class="form-select">
          <option value="asc">Ascendente</option>
          <option value="desc">Descendente</option>
        </select>
      </div>

      <!-- Tabla de plazas -->
      <div class="datatables" v-if="!loading">
        <div class="table-responsive">
          <table class="table-hover">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Fecha Creación</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="plaza in plazas" :key="plaza.id">
                <!-- Nombre -->
                <td>
                  <div class="font-semibold">{{ plaza.nombre }}</div>
                  <div v-if="plaza.nombre_length" class="text-xs text-gray-500">
                    {{ plaza.nombre_length }} caracteres
                  </div>
                </td>

                <!-- Estado -->
                <td>
                  <span
                    :class="[
                      'badge',
                      plaza.activo ? 'badge-outline-success' : 'badge-outline-danger'
                    ]"
                  >
                    {{ plaza.activo ? 'Activa' : 'Inactiva' }}
                  </span>
                </td>

                <!-- Fecha -->
                <td>
                  <div class="text-sm">
                    {{ plaza.created_at_formatted }}
                  </div>
                  <div class="text-xs text-gray-500">
                    {{ plaza.created_ago }}
                  </div>
                </td>

                <!-- Acciones -->
                <td class="text-center">
                  <div class="flex items-center justify-center gap-2">
                    <!-- Toggle estado -->
                    <button
                      @click="togglePlazaStatus(plaza.id)"
                      :class="[
                        'btn btn-sm',
                        plaza.activo ? 'btn-warning' : 'btn-success'
                      ]"
                      :title="plaza.activo ? 'Desactivar' : 'Activar'"
                    >
                      <IconPower class="w-4 h-4" />
                    </button>

                    <!-- Editar -->
                    <button
                      @click="openEditModal(plaza)"
                      class="btn btn-sm btn-primary"
                      title="Editar"
                    >
                      <IconEdit class="w-4 h-4" />
                    </button>

                    <!-- Eliminar -->
                    <button
                      @click="deletePlaza(plaza.id)"
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
        <div v-if="plazas.length === 0" class="text-center py-8">
          <div class="text-gray-500 mb-4">
            <IconBuildingCommunity class="w-16 h-16 mx-auto mb-2 opacity-30" />
            <p>No se encontraron plazas</p>
          </div>
          <button @click="openCreateModal" class="btn btn-primary">
            Crear primera plaza
          </button>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
      </div>
    </div>

    <!-- Modal de formulario -->
    <PlazaFormModal
      v-if="showModal"
      :visible="showModal"
      :plaza="selectedPlaza"
      @close="closeModal"
      @saved="handlePlazaSaved"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { usePlazaStore } from '../../stores/plazaStore';
import type { Plaza, PlazaFilters } from '../../types/plaza';
import PlazaFormModal from './PlazaFormModal.vue';

// Icons
import {
  IconHome,
  IconPlus,
  IconSearch,
  IconEdit,
  IconTrash,
  IconPower,
  IconBuildingCommunity,
} from '@tabler/icons-vue';

// Store
const plazaStore = usePlazaStore();
const { plazas, loading, error, pagination, estadisticas, hasPlazas } = storeToRefs(plazaStore);

// Estados locales
const showModal = ref(false);
const selectedPlaza = ref<Plaza | null>(null);
const searchTimeout = ref<number | null>(null);

// Filtros reactivos
const filters = ref<PlazaFilters>({
  search: '',
  activo: '',
  sort_by: 'nombre',
  sort_order: 'asc',
  per_page: 15,
});

// Métodos
const loadPlazas = async () => {
  await plazaStore.fetchPlazas(filters.value);
};

const applyFilters = () => {
  loadPlazas();
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
    loadPlazas();
  }
};

const openCreateModal = () => {
  selectedPlaza.value = null;
  showModal.value = true;
};

const openEditModal = (plaza: Plaza) => {
  selectedPlaza.value = plaza;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedPlaza.value = null;
};

const handlePlazaSaved = () => {
  closeModal();
  loadPlazas();
};

const togglePlazaStatus = async (id: number) => {
  await plazaStore.toggleActivo(id);
};

const deletePlaza = async (id: number) => {
  await plazaStore.deletePlaza(id);
};

// Lifecycle
onMounted(() => {
  loadPlazas();
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
