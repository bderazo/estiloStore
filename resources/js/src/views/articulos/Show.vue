<template>
  <div class="p-6">
    <!-- Botón volver -->
    <div class="mb-6">
      <router-link
        to="/administrador/articulos"
        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Volver a la lista
      </router-link>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-10">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
    </div>

    <!-- Detalle del artículo -->
    <div v-else-if="articulo" class="space-y-6">
      <!-- Encabezado -->
      <div class="flex items-start justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">{{ articulo.nombre }}</h1>
          <p class="text-sm text-gray-600">{{ articulo.slug }}</p>
        </div>
        <div class="flex gap-2">
          <router-link
            :to="`/administrador/articulos/${articulo.id}/edit`"
            class="btn btn-primary"
          >
            Editar
          </router-link>
          <button
            @click="eliminarArticulo"
            class="btn btn-danger"
          >
            Eliminar
          </button>
        </div>
      </div>

      <!-- Información básica -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Columna izquierda -->
        <div class="space-y-6">
          <!-- Imágenes -->
          <div class="bg-white rounded-lg border p-4">
            <h2 class="text-lg font-semibold mb-4">Imágenes</h2>
            <div v-if="articulo.imagenes && articulo.imagenes.length > 0" class="grid grid-cols-2 gap-4">
              <div v-for="imagen in articulo.imagenes" :key="imagen.id" class="relative">
                <img :src="getImageUrl(imagen.ruta)" alt="Imagen" class="w-full h-48 object-cover rounded">
              </div>
            </div>
            <div v-else class="text-center py-8 text-gray-500">
              No hay imágenes
            </div>
          </div>

          <!-- Descripción -->
          <div class="bg-white rounded-lg border p-4">
            <h2 class="text-lg font-semibold mb-4">Descripción</h2>
            <p class="text-gray-700">{{ articulo.descripcion || 'Sin descripción' }}</p>
          </div>
        </div>

        <!-- Columna derecha -->
        <div class="space-y-6">
          <!-- Información general -->
          <div class="bg-white rounded-lg border p-4">
            <h2 class="text-lg font-semibold mb-4">Información General</h2>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">SKU</dt>
                <dd class="mt-1">{{ articulo.sku || 'No definido' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Precio</dt>
                <dd class="mt-1 font-semibold">${{ Number(articulo.precio).toFixed(2) }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Categoría</dt>
                <dd class="mt-1">{{ articulo.categoria?.nombre || 'Sin categoría' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Marca</dt>
                <dd class="mt-1">{{ articulo.marca?.nombre || 'Sin marca' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                <dd class="mt-1">
                  <span :class="['badge', articulo.activo ? 'badge-success' : 'badge-danger']">
                    {{ articulo.activo ? 'Activo' : 'Inactivo' }}
                  </span>
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Destacado</dt>
                <dd class="mt-1">
                  <span :class="['badge', articulo.destacado ? 'badge-warning' : 'badge-secondary']">
                    {{ articulo.destacado ? 'Sí' : 'No' }}
                  </span>
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Creado</dt>
                <dd class="mt-1">{{ formatDate(articulo.created_at) }}</dd>
              </div>
            </dl>
          </div>

          <!-- Variantes -->
          <div class="bg-white rounded-lg border p-4">
            <h2 class="text-lg font-semibold mb-4">Variantes de Stock</h2>
            <div v-if="articulo.variantes && articulo.variantes.length > 0">
              <table class="w-full text-sm">
                <thead>
                  <tr class="border-b">
                    <th class="py-2 text-left">Atributos</th>
                    <th class="py-2 text-right">Stock</th>
                    <th class="py-2 text-center">Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="variante in articulo.variantes" :key="variante.id" class="border-b">
                    <td class="py-2">
                      <div v-if="variante.atributos && Object.keys(variante.atributos).length > 0">
                        <span v-for="(valor, clave) in variante.atributos" 
                              :key="clave" 
                              class="inline-block bg-gray-100 rounded px-2 py-1 text-xs mr-1 mb-1">
                          {{ clave }}: {{ valor }}
                        </span>
                      </div>
                      <span v-else class="text-gray-500">Sin atributos</span>
                    </td>
                    <td class="py-2 text-right font-medium">{{ variante.stock }}</td>
                    <td class="py-2 text-center">
                      <span :class="['badge', variante.activo ? 'badge-success' : 'badge-danger']">
                        {{ variante.activo ? 'Activa' : 'Inactiva' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr class="bg-gray-50">
                    <td class="py-2 font-medium">Total</td>
                    <td class="py-2 text-right font-bold">{{ articulo.stock_total || 0 }}</td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div v-else class="text-center py-4 text-gray-500">
              No hay variantes
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Error -->
    <div v-else class="text-center py-10">
      <p class="text-red-600">Artículo no encontrado</p>
      <router-link to="/administrador/articulos" class="text-blue-600 hover:underline mt-2 inline-block">
        Volver a la lista
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import axios from 'axios';

const route = useRoute();
const router = useRouter();

const articulo = ref(null);
const loading = ref(true);

const getImageUrl = (path) => {
  if (!path) return '';
  return `/storage/${path}`;
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const fetchArticulo = async () => {
  loading.value = true;
  try {
    const response = await axios.get(`/tienda/public/api/articulos/${route.params.id}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Accept': 'application/json'
      }
    });
    
    if (response.data.success) {
      articulo.value = response.data.data;
    } else {
      throw new Error('Artículo no encontrado');
    }
  } catch (error) {
    console.error('Error al cargar artículo:', error);
    Swal.fire('Error', 'No se pudo cargar el artículo', 'error');
  } finally {
    loading.value = false;
  }
};

const eliminarArticulo = async () => {
  const result = await Swal.fire({
    title: '¿Estás seguro?',
    text: "Esta acción no se puede deshacer",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/tienda/public/api/articulos/${route.params.id}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`,
          'Accept': 'application/json'
        }
      });
      
      Swal.fire('Eliminado', 'Artículo eliminado correctamente', 'success');
      router.push('/administrador/articulos');
    } catch (error) {
      Swal.fire('Error', 'No se pudo eliminar el artículo', 'error');
    }
  }
};

onMounted(() => {
  fetchArticulo();
});
</script>

<style scoped>
.badge {
  @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
}

.badge-success {
  @apply bg-green-100 text-green-800;
}

.badge-danger {
  @apply bg-red-100 text-red-800;
}

.badge-warning {
  @apply bg-yellow-100 text-yellow-800;
}

.badge-secondary {
  @apply bg-gray-100 text-gray-800;
}

.btn {
  @apply px-4 py-2 rounded font-medium transition-colors;
}

.btn-primary {
  @apply bg-blue-600 text-white hover:bg-blue-700;
}

.btn-danger {
  @apply bg-red-600 text-white hover:bg-red-700;
}
</style>