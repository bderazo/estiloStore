<template>
  <div class="space-y-4 rounded-lg bg-gray-50 p-4">
    <h2 class="text-lg font-semibold text-gray-800">Imágenes</h2>

    <!-- Zona de carga -->
    <div
      @dragover.prevent="dragover = true"
      @dragleave.prevent="dragover = false"
      @drop.prevent="manejarDropped"
      :class="[
        'rounded-lg border-2 border-dashed px-6 py-8 text-center transition-colors',
        dragover ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-100',
      ]"
    >
      <input
        ref="fileInput"
        type="file"
        multiple
        accept="image/*"
        class="hidden"
        @change="manejarSeleccion"
      />

      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
        />
      </svg>

      <p class="mt-2 text-sm text-gray-600">
        Arrastra imágenes aquí o
        <button
          type="button"
          @click="() => (fileInput as any)?.click()"
          class="text-blue-600 hover:text-blue-700 underline"
        >
          selecciona archivos
        </button>
      </p>
      <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 5MB cada una (máximo 10 imágenes)</p>
    </div>

    <!-- Previsualizaciones -->
    <div v-if="previsualizaciones.length > 0" class="space-y-2">
      <p class="text-sm font-medium text-gray-700">Imágenes ({{ previsualizaciones.length }})</p>
      <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
        <div
          v-for="(preview, idx) in previsualizaciones"
          :key="idx"
          class="group relative rounded-lg overflow-hidden border border-gray-200"
        >
          <img :src="preview.src" :alt="preview.name" class="h-32 w-full object-cover" />
          <div class="absolute inset-0 flex items-center justify-center gap-2 bg-black bg-opacity-50 opacity-0 transition-opacity group-hover:opacity-100">
            <button
              type="button"
              @click="marcarPrincipal(idx)"
              :class="[
                'rounded bg-gray-700 px-2 py-1 text-xs text-white hover:bg-gray-800',
                preview.principal ? 'bg-yellow-600' : '',
              ]"
              :title="preview.principal ? 'Imagen principal' : 'Marcar como principal'"
            >
              ★
            </button>
            <button
              type="button"
              @click="eliminarImagen(idx)"
              class="rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700"
            >
              ✕
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';

interface Preview {
  src: string;
  name: string;
  file?: File;
  id?: number;
  principal: boolean;
}

const props = defineProps<{
  imagenes: File[];
  imagenesEliminar: number[];
  imagenesExistentes?: Array<{ id: number; ruta: string; principal: boolean; orden: number }>;
}>();

const emit = defineEmits<{
  'update:imagenes': [value: File[]];
  'update:imagenes-eliminar': [value: number[]];
}>();

const fileInput = ref<HTMLInputElement>();
const dragover = ref(false);
const previsualizaciones = ref<Preview[]>([]);

// Función para obtener URL completa
const getImageUrl = (path: string): string => {
  if (!path) return '';
  const baseUrl = window.location.origin;
  return `${baseUrl}/tienda/public/storage/${path}`;
};

// Cargar imágenes existentes cuando cambian las props
const cargarImagenesExistentes = () => {
  if (props.imagenesExistentes && props.imagenesExistentes.length > 0) {
    const imagenesExistentesFormateadas = props.imagenesExistentes
      .sort((a, b) => a.orden - b.orden)
      .map((img) => ({
        src: getImageUrl(img.ruta), // Usar función getImageUrl
        name: `imagen_${img.id}`,
        id: img.id,
        principal: img.principal,
      }));

    // Solo agregar las imágenes existentes si no están ya cargadas
    const imagenesExistentesFiltradas = imagenesExistentesFormateadas.filter(
      (imgExistente) => !previsualizaciones.value.some((prev) => prev.id === imgExistente.id)
    );

    previsualizaciones.value = [...imagenesExistentesFiltradas, ...previsualizaciones.value.filter((p) => p.file)];
  }
};

onMounted(() => {
  cargarImagenesExistentes();
});

watch(() => props.imagenesExistentes, () => {
  cargarImagenesExistentes();
}, { deep: true });

const manejarSeleccion = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files) {
    agregarArchivos(Array.from(target.files));
  }
};

const manejarDropped = (e: DragEvent) => {
  dragover.value = false;
  if (e.dataTransfer?.files) {
    agregarArchivos(Array.from(e.dataTransfer.files));
  }
};

const agregarArchivos = (archivos: File[]) => {
  for (const archivo of archivos) {
    if (previsualizaciones.value.length >= 10) {
      break;
    }

    if (!archivo.type.startsWith('image/')) {
      continue;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
      previsualizaciones.value.push({
        src: e.target?.result as string,
        name: archivo.name,
        file: archivo,
        principal: previsualizaciones.value.length === 0,
      });

      const nuevasImagenes = previsualizaciones.value.filter((p) => p.file).map((p) => p.file!);
      emit('update:imagenes', nuevasImagenes);
    };
    reader.readAsDataURL(archivo);
  }
};

const marcarPrincipal = (idx: number) => {
  previsualizaciones.value.forEach((p, i) => {
    p.principal = i === idx;
  });
};

const eliminarImagen = (idx: number) => {
  const preview = previsualizaciones.value[idx];
  if (preview.id) {
    emit('update:imagenes-eliminar', [...props.imagenesEliminar, preview.id]);
  }
  previsualizaciones.value.splice(idx, 1);

  const nuevasImagenes = previsualizaciones.value.filter((p) => p.file).map((p) => p.file!);
  emit('update:imagenes', nuevasImagenes);
};
</script>
