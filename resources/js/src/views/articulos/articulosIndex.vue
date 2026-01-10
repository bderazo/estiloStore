<template>
  <div class="p-6">
    <!-- Encabezado con título y botón crear -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Artículos</h1>
        <p class="text-sm text-gray-600">Gestiona el catálogo de productos</p>
      </div>
      <router-link
        to="/administrador/articulos/create"
        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nuevo artículo
      </router-link>
    </div>

    <!-- Filtros -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4">
      <div class="grid grid-cols-1 gap-4 md:grid-cols-4 lg:grid-cols-5">
        <!-- Búsqueda -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">Búsqueda</label>
          <input
            v-model="filtros.q"
            type="text"
            placeholder="Nombre o SKU..."
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
            @keyup.enter="aplicarFiltros"
          />
        </div>

        <!-- Categoría -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">Categoría</label>
          <select
            v-model="filtros.categoria_id"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
            @change="aplicarFiltros"
          >
            <option :value="undefined">Todas</option>
            <option value="">Sin categoría</option>
            <!-- TODO: Cargar desde API -->
          </select>
        </div>

        <!-- Estado activo -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">Estado</label>
          <select
            v-model="filtros.activo"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
            @change="aplicarFiltros"
          >
            <option :value="undefined">Todos</option>
            <option :value="true">Activo</option>
            <option :value="false">Inactivo</option>
          </select>
        </div>

        <!-- Destacado -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">Destacado</label>
          <select
            v-model="filtros.destacado"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
            @change="aplicarFiltros"
          >
            <option :value="undefined">Todos</option>
            <option :value="true">Sí</option>
            <option :value="false">No</option>
          </select>
        </div>

        <!-- Ordenar -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">Ordenar</label>
          <select
            v-model="filtros.orden_por"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
            @change="aplicarFiltros"
          >
            <option value="created_at">Más recientes</option>
            <option value="nombre">Nombre (A-Z)</option>
            <option value="precio">Precio</option>
            <option value="stock_total">Stock</option>
          </select>
        </div>
      </div>

      <!-- Botones de acción -->
      <div class="mt-4 flex gap-2">
        <button
          @click="aplicarFiltros"
          class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
        >
          Aplicar filtros
        </button>
        <button
          @click="limpiarFiltros"
          class="rounded bg-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-400"
        >
          Limpiar
        </button>
      </div>
    </div>

    <!-- Tabla de artículos -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
      <table class="w-full">
        <thead class="border-b bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Artículo</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">SKU</th>
            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">Precio</th>
            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">Stock</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Activo</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Destacado</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <template v-if="articuloStore.articulos.length > 0">
            <tr v-for="articulo in articuloStore.articulos" :key="articulo.id" class="border-b hover:bg-gray-50">
              <td class="px-4 py-3">
                <div>
                  <p class="font-medium text-gray-900">{{ articulo.nombre }}</p>
                  <p class="text-xs text-gray-600">{{ articulo.slug }}</p>
                </div>
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ articulo.sku || '-' }}</td>
              <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                ${{ Number(articulo.precio).toFixed(2) }}
              </td>
              <td class="px-4 py-3 text-right">
                <div class="text-sm">
                  <p class="font-medium text-gray-900">{{ articulo.stock_total }}</p>
                  <p class="text-xs text-gray-600">{{ articulo.stock_disponible }} disponible</p>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <span
                  :class="[
                    'badge',
                    articulo.activo ? 'badge-outline-success' : 'badge-outline-danger'
                  ]"
                >
                  {{ articulo.activo ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td class="px-4 py-3 text-center">
                <span
                  :class="[
                    'badge',
                    articulo.destacado ? 'badge-outline-warning' : 'badge-outline-secondary'
                  ]"
                >
                  {{ articulo.destacado ? 'Destacado' : 'Normal' }}
                </span>
              </td>
              <td class="px-4 py-3 text-center">
                <!-- Dropdown de acciones -->
                <div class="dropdown" :class="{ 'show': dropdownAbierto === articulo.id }">
                  <button
                    type="button"
                    class="btn btn-sm btn-secondary dropdown-toggle"
                    :id="`dropdown-${articulo.id}`"
                    aria-expanded="false"
                    title="Acciones"
                    @click="toggleDropdown(articulo.id)"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <circle cx="5" cy="12" r="2"></circle>
                      <circle cx="12" cy="12" r="2"></circle>
                      <circle cx="19" cy="12" r="2"></circle>
                    </svg>
                  </button>
                  <ul class="dropdown-menu" :class="{ 'show': dropdownAbierto === articulo.id }" :aria-labelledby="`dropdown-${articulo.id}`">
                    <!-- Ver -->
                    <li>
                      <router-link
                        :to="`/administrador/articulos/${articulo.id}`"
                        class="dropdown-item"
                      >
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Ver detalle
                      </router-link>
                    </li>
                    <!-- Editar -->
                    <li>
                      <router-link
                        :to="`/administrador/articulos/${articulo.id}/edit`"
                        class="dropdown-item"
                      >
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                      </router-link>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <!-- Toggle Activo -->
                    <li>
                      <button
                        @click="toggleActivo(articulo)"
                        class="dropdown-item"
                        type="button"
                      >
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                        </svg>
                        {{ articulo.activo ? 'Desactivar' : 'Activar' }}
                      </button>
                    </li>
                    <!-- Toggle Destacado -->
                    <li>
                      <button
                        @click="toggleDestacado(articulo)"
                        class="dropdown-item"
                        type="button"
                      >
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        {{ articulo.destacado ? 'Des-destacar' : 'Destacar' }}
                      </button>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <!-- Eliminar -->
                    <li>
                      <button
                        @click="eliminar(articulo)"
                        class="dropdown-item text-danger"
                        type="button"
                      >
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          </template>
          <tr v-else>
            <td colspan="7" class="px-4 py-8 text-center text-gray-600">
              {{ articuloStore.loading ? 'Cargando...' : 'No hay artículos para mostrar' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div v-if="articuloStore.pagination.last_page > 1" class="mt-6 flex items-center justify-between">
      <div class="text-sm text-gray-600">
        Mostrando
        <span class="font-medium">{{ (articuloStore.pagination.current_page - 1) * articuloStore.pagination.per_page + 1 }}</span>
        a
        <span class="font-medium">{{
          Math.min(articuloStore.pagination.current_page * articuloStore.pagination.per_page, articuloStore.pagination.total)
        }}</span>
        de
        <span class="font-medium">{{ articuloStore.pagination.total }}</span>
        resultados
      </div>
      <div class="flex gap-2">
        <button
          @click="irAlaPagina(articuloStore.pagination.current_page - 1)"
          :disabled="articuloStore.pagination.current_page === 1"
          class="rounded border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50 disabled:opacity-50"
        >
          Anterior
        </button>
        <button
          v-for="page in paginasVisibles"
          :key="page"
          @click="irAlaPagina(page)"
          :class="[
            'rounded border px-3 py-2 text-sm',
            page === articuloStore.pagination.current_page
              ? 'border-blue-600 bg-blue-600 text-white'
              : 'border-gray-300 hover:bg-gray-50',
          ]"
        >
          {{ page }}
        </button>
        <button
          @click="irAlaPagina(articuloStore.pagination.current_page + 1)"
          :disabled="articuloStore.pagination.current_page === articuloStore.pagination.last_page"
          class="rounded border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50 disabled:opacity-50"
        >
          Siguiente
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useArticuloStore } from '../../stores/articuloStore';
import Swal from 'sweetalert2';
import type { Articulo } from '../../types/articulo';

const router = useRouter();
const articuloStore = useArticuloStore();

const filtros = ref({
  q: undefined as string | undefined,
  categoria_id: undefined as number | undefined,
  activo: undefined as boolean | undefined,
  destacado: undefined as boolean | undefined,
  orden_por: 'created_at' as string,
});

// Estado del dropdown
const dropdownAbierto = ref<number | null>(null);

const paginasVisibles = computed(() => {
  const current = articuloStore.pagination.current_page;
  const last = articuloStore.pagination.last_page;
  const delta = 2;
  const pages: number[] = [];

  let start = Math.max(1, current - delta);
  let end = Math.min(last, current + delta);

  if (start > 1) {
    pages.push(1);
    if (start > 2) pages.push(-1);
  }

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }

  if (end < last) {
    if (end < last - 1) pages.push(-1);
    pages.push(last);
  }

  return pages;
});

// Funciones para manejar dropdown
const toggleDropdown = (articuloId: number) => {
  if (dropdownAbierto.value === articuloId) {
    dropdownAbierto.value = null;
  } else {
    dropdownAbierto.value = articuloId;
  }
};

const cerrarDropdown = () => {
  dropdownAbierto.value = null;
};

// Event listener para cerrar dropdown al hacer clic fuera
const handleClickOutside = (event: MouseEvent) => {
  const target = event.target as HTMLElement;
  if (!target.closest('.dropdown')) {
    cerrarDropdown();
  }
};

onMounted(() => {
  articuloStore.fetchArticulos();
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

const aplicarFiltros = async () => {
  articuloStore.setFilters({
    ...filtros.value,
    page: 1,
  });
  await articuloStore.fetchArticulos();
};

const limpiarFiltros = () => {
  filtros.value = {
    q: undefined,
    categoria_id: undefined,
    activo: undefined,
    destacado: undefined,
    orden_por: 'created_at',
  };
  articuloStore.clearFilters();
  articuloStore.fetchArticulos();
};

const irAlaPagina = async (page: number) => {
  if (page > 0) {
    articuloStore.setFilters({ page });
    await articuloStore.fetchArticulos();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
};

const toggleActivo = async (articulo: Articulo) => {
  try {
    await articuloStore.toggleActivo(articulo.id);
    Swal.fire('Éxito', `Artículo ${articulo.activo ? 'desactivado' : 'activado'}`, 'success');
  } catch (error) {
    Swal.fire('Error', articuloStore.error || 'Error al actualizar artículo', 'error');
  }
};

const toggleDestacado = async (articulo: Articulo) => {
  try {
    await articuloStore.toggleDestacado(articulo.id);
  } catch (error) {
    Swal.fire('Error', articuloStore.error || 'Error al cambiar estado destacado', 'error');
  }
};

const eliminar = async (articulo: Articulo) => {
  const resultado = await Swal.fire({
    title: '¿Estás seguro?',
    text: `Se eliminará el artículo "${articulo.nombre}"`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#dc2626',
  });

  if (resultado.isConfirmed) {
    try {
      await articuloStore.deleteArticulo(articulo.id);
      Swal.fire('Eliminado', 'Artículo eliminado exitosamente', 'success');
    } catch (error) {
      Swal.fire('Error', articuloStore.error || 'Error al eliminar artículo', 'error');
    }
  }
};
</script>

<style scoped>
.badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.05em;
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

.badge-outline-warning {
  color: rgb(245 158 11);
  background-color: rgb(255 251 235);
  border: 1px solid rgb(253 230 138);
}

.badge-outline-secondary {
  color: rgb(107 114 128);
  background-color: rgb(249 250 251);
  border: 1px solid rgb(209 213 219);
}

/* Dropdown styles */
.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-toggle::after {
  display: none;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  z-index: 1000;
  display: none;
  min-width: 10rem;
  padding: 0.5rem 0;
  margin: 0.125rem 0 0;
  font-size: 0.875rem;
  color: #212529;
  text-align: left;
  list-style: none;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 0.375rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-menu.show {
  display: block;
}

.dropdown-item {
  display: flex;
  align-items: center; /* vertical center */
  gap: 0.5rem; /* espacio entre icono y texto */
  width: 100%;
  padding: 0.375rem 1rem;
  font-weight: 400;
  color: #212529;
  text-align: left;
  text-decoration: none;
  white-space: nowrap;
  background-color: transparent;
  border: 0;
  cursor: pointer;
}

.dropdown-item:hover,
.dropdown-item:focus {
  color: #1e2125;
  background-color: #f8f9fa;
}

/* Asegurar que el SVG quede a la izquierda y tamaño consistente */
.dropdown-item svg {
  width: 1rem;
  height: 1rem;
  flex: 0 0 1rem;
  display: inline-block;
  margin-right: 0.5rem;
}

/* Texto del item: ocupa el espacio restante y queda alineado a la izquierda */
.dropdown-item .item-text {
  flex: 1 1 auto;
  text-align: left;
  display: inline-block;
}

.dropdown-item.text-danger {
  color: #dc3545;
}

.dropdown-item.text-danger:hover {
  color: #bb2d3b;
  background-color: #f8d7da;
}

/* Router links dentro del dropdown */
a.dropdown-item {
  color: #374151;
  text-decoration: none;
}

a.dropdown-item:hover {
  background-color: #f3f4f6;
  color: #1f2937;
  text-decoration: none;
}

.dropdown-divider {
  height: 0;
  margin: 0.5rem 0;
  overflow: hidden;
  border-top: 1px solid rgba(0, 0, 0, 0.15);
}
</style>
