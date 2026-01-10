<template>
  <div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Medidas</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gestiona las medidas del sistema</p>
      </div>
      <button
        @click="showFormModal = true"
        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
      >
        <IconPlus class="h-5 w-5" />
        Nueva Medida
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
            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
          />
        </div>

        <!-- Filtro de estado -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Estado</label>
          <select
            v-model="statusFilter"
            @change="handleStatusFilter"
            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
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
            v-model.number="medidaStore.filters.per_page"
            @change="handlePerPageChange"
            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
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

    <!-- Tabla de medidas -->
    <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800">
      <div v-if="medidaStore.loading" class="flex items-center justify-center py-12">
        <div class="text-center">
          <div class="mx-auto mb-4 h-12 w-12 animate-spin rounded-full border-4 border-gray-300 border-t-blue-600"></div>
          <p class="text-gray-600 dark:text-gray-400">Cargando medidas...</p>
        </div>
      </div>

      <div v-else-if="medidaStore.medidas.length === 0" class="px-6 py-12 text-center">
        <IconBox class="mx-auto mb-4 h-12 w-12 text-gray-400" />
        <p class="text-gray-600 dark:text-gray-400">No hay medidas disponibles</p>
        <button
          @click="showFormModal = true"
          class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700"
        >
          <IconPlus class="h-5 w-5" />
          Crear primera medida
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
            v-for="medida in medidaStore.medidas"
            :key="medida.id"
            class="hover:bg-gray-50 dark:hover:bg-gray-700"
          >
            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
              {{ medida.id }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
              {{ medida.nombre }}
            </td>
            <td class="px-6 py-4">
              <span
                :class="[
                  'badge',
                  medida.activo ? 'badge-outline-success' : 'badge-outline-danger'
                ]"
              >
                {{ medida.activo ? 'Activa' : 'Inactiva' }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
              {{ medida.formatted_created_at }}
            </td>
            <td class="px-6 py-4 text-center">
              <div class="flex items-center justify-center gap-2">
                <!-- Toggle estado -->
                <button
                  @click="handleToggleActivo(medida.id)"
                  :class="[
                    'btn btn-sm',
                    medida.activo ? 'btn-warning' : 'btn-success'
                  ]"
                  :title="medida.activo ? 'Desactivar' : 'Activar'"
                >
                  <IconPower class="w-4 h-4" />
                </button>

                <!-- Editar -->
                <button
                  @click="handleEdit(medida)"
                  class="btn btn-sm btn-primary"
                  title="Editar"
                >
                  <IconEdit class="w-4 h-4" />
                </button>

                <!-- Eliminar -->
                <button
                  @click="handleDelete(medida.id)"
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
    <div v-if="medidaStore.pagination.total > 0" class="flex items-center justify-between mt-4">
      <div class="text-sm text-gray-500">
        Mostrando {{ medidaStore.pagination.from }} a {{ medidaStore.pagination.to }} de {{ medidaStore.pagination.total }} registros
      </div>

      <div class="flex items-center gap-2">
        <!-- Registros por página -->
        <select
          v-model="medidaStore.filters.per_page"
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
            @click="changePage(medidaStore.pagination.current_page - 1)"
            :disabled="medidaStore.pagination.current_page <= 1"
            class="btn btn-sm btn-outline-primary disabled:opacity-50"
          >
            Anterior
          </button>
          <button
            @click="changePage(medidaStore.pagination.current_page + 1)"
            :disabled="medidaStore.pagination.current_page >= medidaStore.pagination.last_page"
            class="btn btn-sm btn-outline-primary disabled:opacity-50 ml-1"
          >
            Siguiente
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de formulario -->
    <MedidaFormModal
      v-if="showFormModal"
      :medida="selectedMedidaForEdit"
      @close="handleCloseModal"
      @submit="handleFormSubmit"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useMedidaStore } from '@/stores/medidaStore';
import MedidaFormModal from './MedidaFormModal.vue';
import { IconPlus, IconBox, IconEdit, IconTrash, IconPower } from '@tabler/icons-vue';
import Swal from 'sweetalert2';
import type { Medida, MedidaForm } from '@/types/medida';

const medidaStore = useMedidaStore();
const showFormModal = ref(false);
const selectedMedidaForEdit = ref<Medida | null>(null);
const searchQuery = ref('');
const statusFilter = ref<boolean | string>('');

onMounted(() => {
  medidaStore.fetchMedidas();
});

const handleSearch = () => {
  medidaStore.setFilters({
    search: searchQuery.value,
  });
  medidaStore.fetchMedidas();
};

const handleStatusFilter = () => {
  medidaStore.setFilters({
    activo: statusFilter.value,
  });
  medidaStore.fetchMedidas();
};

const handlePerPageChange = () => {
  medidaStore.fetchMedidas();
};

const handleEdit = (medida: Medida) => {
  selectedMedidaForEdit.value = medida;
  showFormModal.value = true;
};

const handleDelete = async (id: number) => {
  const result = await Swal.fire({
    title: '¿Eliminar medida?',
    text: 'Esta acción no se puede deshacer',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Eliminar',
    cancelButtonText: 'Cancelar',
  });

  if (result.isConfirmed) {
    const response = await medidaStore.deleteMedida(id);
    if (response.success) {
      await Swal.fire('Eliminado', 'Medida eliminada correctamente', 'success');
    } else {
      await Swal.fire('Error', response.message || 'Error al eliminar medida', 'error');
    }
  }
};

const handleToggleActivo = async (id: number) => {
  await medidaStore.toggleActivo(id);
};

const handleFormSubmit = async (data: MedidaForm) => {
  if (selectedMedidaForEdit.value) {
    const response = await medidaStore.updateMedida(selectedMedidaForEdit.value.id, data);
    if (response.success) {
      await Swal.fire('Actualizado', 'Medida actualizada correctamente', 'success');
      showFormModal.value = false;
      selectedMedidaForEdit.value = null;
    } else {
      await Swal.fire('Error', response.message || 'Error al actualizar medida', 'error');
    }
  } else {
    const response = await medidaStore.createMedida(data);
    if (response.success) {
      await Swal.fire('Creado', 'Medida creada correctamente', 'success');
      showFormModal.value = false;
    } else {
      await Swal.fire('Error', response.message || 'Error al crear medida', 'error');
    }
  }
};

const handleCloseModal = () => {
  showFormModal.value = false;
  selectedMedidaForEdit.value = null;
};

const changePage = (page: number) => {
  if (page >= 1 && page <= medidaStore.pagination.last_page) {
    medidaStore.setFilters({
      ...medidaStore.filters,
      current_page: page,
    });
    medidaStore.fetchMedidas();
  }
};
</script>
