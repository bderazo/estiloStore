<template>
  <div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Sabores</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gestiona los sabores del sistema</p>
      </div>
      <button
        @click="showFormModal = true"
        class="btn btn-primary"
      >
        <IconPlus class="w-4 h-4 mr-2" />
        Nuevo Sabor
      </button>
    </div>

    <!-- Filtros de búsqueda -->
    <div class="rounded-lg bg-white p-4 shadow-sm dark:bg-gray-800">
      <div class="grid gap-4 md:grid-cols-3">
        <!-- Búsqueda -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Buscar</label>
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Buscar por nombre..."
            class="form-input"
          />
        </div>

        <!-- Filtro de estado -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Estado</label>
          <select
            v-model="statusFilter"
            @change="handleStatusFilter"
            class="form-select"
          >
            <option value="">Todos</option>
            <option :value="true">Activos</option>
            <option :value="false">Inactivos</option>
          </select>
        </div>

        <!-- Items por página -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Items por página</label>
          <select
            v-model.number="saborStore.filters.per_page"
            @change="handlePerPageChange"
            class="form-select"
          >
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Tabla de sabores -->
    <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800">
      <div v-if="saborStore.loading" class="flex items-center justify-center py-12">
        <div class="text-center">
          <div class="mx-auto mb-4 h-12 w-12 animate-spin rounded-full border-4 border-gray-300 border-t-blue-600"></div>
          <p class="text-gray-600 dark:text-gray-400">Cargando sabores...</p>
        </div>
      </div>

      <div v-else-if="saborStore.sabores.length === 0" class="px-6 py-12 text-center">
        <IconBox class="mx-auto mb-4 h-12 w-12 text-gray-400" />
        <p class="text-gray-600 dark:text-gray-400">No hay sabores disponibles</p>
        <button
          @click="showFormModal = true"
          class="mt-4 btn btn-primary"
        >
          <IconPlus class="w-4 h-4 mr-2" />
          Crear primer sabor
        </button>
      </div>

      <table v-else class="w-full">
        <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">ID</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Nombre</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Estado</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Fecha de Creación</th>
            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900 dark:text-white">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr
            v-for="sabor in saborStore.sabores"
            :key="sabor.id"
            class="hover:bg-gray-50 dark:hover:bg-gray-700"
          >
            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
              {{ sabor.id }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
              {{ sabor.nombre }}
            </td>
            <td class="px-6 py-4">
              <span
                :class="[
                  'badge',
                  sabor.activo ? 'badge-outline-success' : 'badge-outline-danger'
                ]"
              >
                {{ sabor.activo ? 'Activo' : 'Inactivo' }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
              {{ sabor.formatted_created_at }}
            </td>
            <td class="px-6 py-4 text-center">
              <div class="flex items-center justify-center gap-2">
                <!-- Toggle estado -->
                <button
                  @click="handleToggleActivo(sabor.id)"
                  :class="[
                    'btn btn-sm',
                    sabor.activo ? 'btn-warning' : 'btn-success'
                  ]"
                  :title="sabor.activo ? 'Desactivar' : 'Activar'"
                >
                  <IconPower class="w-4 h-4" />
                </button>

                <!-- Editar -->
                <button
                  @click="handleEdit(sabor)"
                  class="btn btn-sm btn-primary"
                  title="Editar"
                >
                  <IconEdit class="w-4 h-4" />
                </button>

                <!-- Eliminar -->
                <button
                  @click="handleDelete(sabor.id)"
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
    <div v-if="saborStore.pagination.total > 0" class="flex items-center justify-between mt-4">
      <div class="text-sm text-gray-500">
        Mostrando {{ saborStore.pagination.from }} a {{ saborStore.pagination.to }} de {{ saborStore.pagination.total }} registros
      </div>

      <div class="flex items-center gap-2">
        <!-- Registros por página -->
        <select
          v-model="saborStore.filters.per_page"
          @change="changePage(1)"
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
            @click="changePage(saborStore.pagination.current_page - 1)"
            :disabled="saborStore.pagination.current_page <= 1"
            class="btn btn-sm btn-outline-primary disabled:opacity-50"
          >
            Anterior
          </button>
          <button
            @click="changePage(saborStore.pagination.current_page + 1)"
            :disabled="saborStore.pagination.current_page >= saborStore.pagination.last_page"
            class="btn btn-sm btn-outline-primary disabled:opacity-50 ml-1"
          >
            Siguiente
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de formulario -->
    <SaborFormModal
      :visible="showFormModal"
      :sabor="selectedSaborForEdit"
      @close="handleCloseModal"
      @saved="handleFormSubmit"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useSaborStore } from '@/stores/saborStore';
import SaborFormModal from './SaborFormModal.vue';
import { IconPlus, IconBox, IconEdit, IconTrash, IconPower } from '@tabler/icons-vue';
import Swal from 'sweetalert2';
import type { Sabor, SaborForm } from '@/types/sabor';

const saborStore = useSaborStore();
const showFormModal = ref(false);
const selectedSaborForEdit = ref<Sabor | null>(null);
const searchQuery = ref('');
const statusFilter = ref<boolean | string>('');

onMounted(() => {
  saborStore.fetchSabores();
});

const handleSearch = () => {
  saborStore.setFilters({
    search: searchQuery.value,
  });
  saborStore.fetchSabores();
};

const handleStatusFilter = () => {
  saborStore.setFilters({
    activo: statusFilter.value,
  });
  saborStore.fetchSabores();
};

const handlePerPageChange = () => {
  saborStore.fetchSabores();
};

const handleEdit = (sabor: Sabor) => {
  selectedSaborForEdit.value = sabor;
  showFormModal.value = true;
};

const handleDelete = async (id: number) => {
  const result = await Swal.fire({
    title: '¿Eliminar sabor?',
    text: 'Esta acción no se puede deshacer',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Eliminar',
    cancelButtonText: 'Cancelar',
  });

  if (result.isConfirmed) {
    const response = await saborStore.deleteSabor(id);
    if (response.success) {
      await Swal.fire('Eliminado', 'Sabor eliminado correctamente', 'success');
    } else {
      await Swal.fire('Error', response.message || 'Error al eliminar sabor', 'error');
    }
  }
};

const handleToggleActivo = async (id: number) => {
  await saborStore.toggleActivo(id);
};

const handleFormSubmit = async () => {
  handleCloseModal();
  await saborStore.fetchSabores();
};

const handleCloseModal = () => {
  showFormModal.value = false;
  selectedSaborForEdit.value = null;
};

const changePage = (page: number) => {
  if (page >= 1 && page <= saborStore.pagination.last_page) {
    saborStore.setFilters({
      ...saborStore.filters,
      current_page: page,
    });
    saborStore.fetchSabores();
  }
};
</script>
