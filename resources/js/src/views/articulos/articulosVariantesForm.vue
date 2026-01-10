<template>
  <div class="space-y-4 rounded-lg bg-gray-50 p-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <h2 class="text-lg font-semibold text-gray-800">Variantes de Stock</h2>
        <span v-if="modelValue.length > 0" class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800">
          {{ modelValue.length }} variante{{ modelValue.length === 1 ? '' : 's' }}
        </span>
      </div>
      <div class="flex items-center gap-2">
        <button
          type="button"
          @click="agregarVariante"
          class="inline-flex items-center gap-2 rounded bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700"
        >
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Agregar variante
        </button>
        <button
          v-if="modelValue.length > 0"
          type="button"
          @click="mostrarVariantes = !mostrarVariantes"
          class="inline-flex items-center gap-2 rounded border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50"
        >
          <svg
            class="h-4 w-4 transition-transform"
            :class="{ 'rotate-180': mostrarVariantes }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
          {{ mostrarVariantes ? 'Ocultar' : 'Mostrar' }}
        </button>
      </div>
    </div>

    <p class="text-sm text-gray-600">
      Cada variante puede tener atributos diferentes (talla, color, etc) y su propio stock
    </p>

    <!-- Variantes existentes con collapse -->
    <div v-if="modelValue.length > 0 && mostrarVariantes" class="space-y-3">
      <div v-for="(variante, idx) in modelValue" :key="idx" class="rounded-lg border border-gray-200 bg-white p-4">
        <div class="mb-4 flex items-center justify-between">
          <h3 class="font-medium text-gray-800">
            Variante {{ idx + 1 }}
            <span v-if="Object.keys(variante.atributos).length > 0" class="ml-2 text-sm text-gray-500">
              ({{ obtenerResumenAtributos(variante.atributos) }})
            </span>
          </h3>
          <button
            type="button"
            @click="confirmarEliminarVariante(idx)"
            class="inline-flex items-center gap-1 rounded bg-red-100 px-2 py-1 text-sm text-red-700 hover:bg-red-200"
          >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Eliminar
          </button>
        </div>

        <!-- Atributos -->
        <div class="mb-4 rounded-lg bg-gray-50 p-3">
          <label class="mb-2 block text-sm font-medium text-gray-700">Atributos</label>
          <div class="space-y-2">
            <!-- Color -->
            <div v-if="colores && colores.length > 0">
              <label class="text-xs text-gray-600">Color:</label>
              <select
                :value="modelValue[idx].atributos.colores || ''"
                @change="(e) => actualizarAtributo(idx, 'colores', (e.target as HTMLSelectElement).value ? Number((e.target as HTMLSelectElement).value) : null)"
                class="w-full rounded border border-gray-300 px-2 py-1 text-sm"
              >
                <option value="">Selecciona color</option>
                <option v-for="col in colores" :key="col.id" :value="col.id">{{ col.nombre }}</option>
              </select>
            </div>

            <!-- Talla -->
            <div v-if="tallas && tallas.length > 0">
              <label class="text-xs text-gray-600">Talla:</label>
              <select
                :value="modelValue[idx].atributos.tallas || ''"
                @change="(e) => actualizarAtributo(idx, 'tallas', (e.target as HTMLSelectElement).value ? Number((e.target as HTMLSelectElement).value) : null)"
                class="w-full rounded border border-gray-300 px-2 py-1 text-sm"
              >
                <option value="">Selecciona talla</option>
                <option v-for="tal in tallas" :key="tal.id" :value="tal.id">{{ tal.nombre }}</option>
              </select>
            </div>

            <!-- Sabor -->
            <div v-if="sabores && sabores.length > 0">
              <label class="text-xs text-gray-600">Sabor:</label>
              <select
                :value="modelValue[idx].atributos.sabores || ''"
                @change="(e) => actualizarAtributo(idx, 'sabores', (e.target as HTMLSelectElement).value ? Number((e.target as HTMLSelectElement).value) : null)"
                class="w-full rounded border border-gray-300 px-2 py-1 text-sm"
              >
                <option value="">Selecciona sabor</option>
                <option v-for="sab in sabores" :key="sab.id" :value="sab.id">{{ sab.nombre }}</option>
              </select>
            </div>

            <!-- Modelo -->
            <div v-if="modelos && modelos.length > 0">
              <label class="text-xs text-gray-600">Modelo:</label>
              <select
                :value="modelValue[idx].atributos.modelos || ''"
                @change="(e) => actualizarAtributo(idx, 'modelos', (e.target as HTMLSelectElement).value ? Number((e.target as HTMLSelectElement).value) : null)"
                class="w-full rounded border border-gray-300 px-2 py-1 text-sm"
              >
                <option value="">Selecciona modelo</option>
                <option v-for="mod in modelos" :key="mod.id" :value="mod.id">{{ mod.nombre }}</option>
              </select>
            </div>

            <!-- Tono -->
            <div v-if="tonos && tonos.length > 0">
              <label class="text-xs text-gray-600">Tono:</label>
              <select
                :value="modelValue[idx].atributos.tonos || ''"
                @change="(e) => actualizarAtributo(idx, 'tonos', (e.target as HTMLSelectElement).value ? Number((e.target as HTMLSelectElement).value) : null)"
                class="w-full rounded border border-gray-300 px-2 py-1 text-sm"
              >
                <option value="">Selecciona tono</option>
                <option v-for="ton in tonos" :key="ton.id" :value="ton.id">{{ ton.nombre }}</option>
              </select>
            </div>

            <!-- Medida -->
            <div v-if="medidas && medidas.length > 0">
              <label class="text-xs text-gray-600">Medida:</label>
              <select
                :value="modelValue[idx].atributos.medidas || ''"
                @change="(e) => actualizarAtributo(idx, 'medidas', (e.target as HTMLSelectElement).value ? Number((e.target as HTMLSelectElement).value) : null)"
                class="w-full rounded border border-gray-300 px-2 py-1 text-sm"
              >
                <option value="">Selecciona medida</option>
                <option v-for="med in medidas" :key="med.id" :value="med.id">{{ med.nombre }}</option>
              </select>
            </div>

            <!-- Plaza -->
            <div v-if="plazas && plazas.length > 0">
              <label class="text-xs text-gray-600">Plaza:</label>
              <select
                :value="modelValue[idx].atributos.plazas || ''"
                @change="(e) => actualizarAtributo(idx, 'plazas', (e.target as HTMLSelectElement).value ? Number((e.target as HTMLSelectElement).value) : null)"
                class="w-full rounded border border-gray-300 px-2 py-1 text-sm"
              >
                <option value="">Selecciona plaza</option>
                <option v-for="plz in plazas" :key="plz.id" :value="plz.id">{{ plz.nombre }}</option>
              </select>
            </div>
          </div>

          <!-- Resumen de atributos seleccionados -->
          <div v-if="Object.keys(modelValue[idx].atributos).length > 0" class="mt-3 flex flex-wrap gap-2">
            <span
              v-for="(valor, tipo) in modelValue[idx].atributos"
              :key="tipo"
              class="inline-block rounded-full bg-blue-100 px-3 py-1 text-xs text-blue-600"
            >
              {{ tipo }}: {{ obtenerNombreAtributo(tipo, valor) }}
            </span>
          </div>
        </div>

        <!-- Solo Stock - SKU y precio se manejan a nivel de artículo -->
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
          <div>
            <label class="mb-1 block text-xs font-medium text-gray-700">Stock *</label>
            <input
              v-model.number="variante.stock"
              type="number"
              min="0"
              placeholder="0"
              class="w-full rounded border border-gray-300 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none"
            />
          </div>
        </div>

        <!-- Estado -->
        <div class="mt-3">
          <label class="flex items-center gap-2">
            <input v-model="variante.activo" type="checkbox" class="h-4 w-4 rounded border-gray-300" />
            <span class="text-sm text-gray-700">Variante activa</span>
          </label>
        </div>
      </div>
    </div>

    <!-- Mensaje si no hay variantes -->
    <div v-if="modelValue.length === 0" class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center">
      <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
      </svg>
      <p class="text-gray-600 font-medium">No hay variantes agregadas</p>
      <p class="text-xs text-gray-500 mt-1">Crea variantes para manejar diferentes combinaciones de atributos</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Swal from 'sweetalert2';

const mostrarVariantes = ref(true);

const props = defineProps<{
  modelValue: Array<{
    id?: number;
    atributos: Record<string, number>;
    sku?: string;
    precio?: number;
    stock: number;
    activo?: boolean;
  }>;
  variantesEliminar: number[];
  colores?: Array<{ id: number; nombre: string }>;
  tallas?: Array<{ id: number; nombre: string }>;
  sabores?: Array<{ id: number; nombre: string }>;
  modelos?: Array<{ id: number; nombre: string }>;
  tonos?: Array<{ id: number; nombre: string }>;
  medidas?: Array<{ id: number; nombre: string }>;
  plazas?: Array<{ id: number; nombre: string }>;
}>();

const emit = defineEmits<{
  'update:modelValue': [value: typeof props.modelValue];
  'update:variantesEliminar': [value: number[]];
}>();

const agregarVariante = () => {
  const nuevasVariantes = [
    ...props.modelValue,
    {
      atributos: {},
      stock: 0,
      activo: true,
    },
  ];
  emit('update:modelValue', nuevasVariantes);
};

const eliminarVariante = (idx: number) => {
  const varianteAEliminar = props.modelValue[idx];

  // Si la variante tiene ID, significa que existe en la base de datos y debe ser eliminada
  if (varianteAEliminar.id) {
    const nuevasVariantesEliminar = [...props.variantesEliminar, varianteAEliminar.id];
    emit('update:variantesEliminar', nuevasVariantesEliminar);
  }

  // Remover del array local
  const nuevasVariantes = props.modelValue.filter((_, i) => i !== idx);
  emit('update:modelValue', nuevasVariantes);
};

const confirmarEliminarVariante = async (idx: number) => {
  const variante = props.modelValue[idx];
  const resumen = obtenerResumenAtributos(variante.atributos);

  const resultado = await Swal.fire({
    title: '¿Eliminar variante?',
    text: `Se eliminará la variante ${idx + 1}${resumen ? ` (${resumen})` : ''}`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#dc2626',
  });

  if (resultado.isConfirmed) {
    eliminarVariante(idx);
    Swal.fire({
      icon: 'success',
      title: 'Eliminado',
      text: 'Variante eliminada correctamente',
      timer: 2000,
      showConfirmButton: false,
    });
  }
};

const actualizarAtributo = (idx: number, tipo: string, valor: number | null) => {
  const nuevasVariantes = [...props.modelValue];
  if (valor === null || valor === undefined) {
    delete nuevasVariantes[idx].atributos[tipo];
  } else {
    nuevasVariantes[idx].atributos[tipo] = valor;
  }
  emit('update:modelValue', nuevasVariantes);
};

const obtenerNombreAtributo = (tipo: string, valor: number): string => {
  const lista = props[tipo as keyof typeof props] as Array<{ id: number; nombre: string }> | undefined;
  if (!lista) return '';
  const item = lista.find(i => i.id === valor);
  return item?.nombre || '';
};

const obtenerResumenAtributos = (atributos: Record<string, number>): string => {
  const resumen: string[] = [];

  for (const [tipo, valor] of Object.entries(atributos)) {
    const nombre = obtenerNombreAtributo(tipo, valor);
    if (nombre) {
      resumen.push(`${tipo}: ${nombre}`);
    }
  }

  return resumen.join(', ');
};
</script>
