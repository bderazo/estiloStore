<template>
  <div class="rounded-lg border border-gray-200 bg-white p-4">
    <div v-if="imagenes.length > 0" class="space-y-4">
      <!-- Imagen principal -->
      <div class="aspect-square overflow-hidden rounded-lg bg-gray-100">
        <img
          :src="imagenes[imagenActualIdx]?.url"
          :alt="imagenes[imagenActualIdx]?.ruta"
          class="h-full w-full object-cover"
        />
      </div>

      <!-- Thumbnails -->
      <div class="flex gap-2 overflow-x-auto pb-2">
        <button
          v-for="(imagen, idx) in imagenes"
          :key="imagen.id"
          @click="imagenActualIdx = idx"
          :class="[
            'relative h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg border-2 transition-colors',
            imagenActualIdx === idx ? 'border-blue-600' : 'border-gray-200 hover:border-gray-300',
          ]"
        >
          <img :src="imagen.url" :alt="imagen.ruta" class="h-full w-full object-cover" />
        </button>
      </div>

      <!-- Información -->
      <div class="text-sm text-gray-600">
        <p>{{ imagenActualIdx + 1 }} de {{ imagenes.length }}</p>
      </div>
    </div>
    <div v-else class="aspect-square flex items-center justify-center rounded-lg bg-gray-100">
      <p class="text-gray-500">Sin imágenes</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IArticuloImagen } from '../../interfaces/articulo';

defineProps<{
  imagenes: IArticuloImagen[];
}>();

const imagenActualIdx = ref(0);
</script>
