<template>
  <div class="p-6">
    <!-- Loading overlay -->
    <div v-if="cargandoDatos" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="rounded-lg bg-white p-8 text-center shadow-lg">
        <div class="mb-4 inline-block">
          <svg class="h-12 w-12 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
        <p class="text-gray-700">Cargando datos del artículo...</p>
      </div>
    </div>

    <div v-if="!cargandoDatos">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ isEditing ? 'Editar artículo' : 'Crear artículo' }}</h1>
        <p class="text-sm text-gray-600">{{ isEditing ? 'Modifica los datos del artículo' : 'Agrega un nuevo producto al catálogo' }}</p>
      </div>

      <form @submit.prevent="guardar" class="space-y-6 rounded-lg border border-gray-200 bg-white p-6">
      <!-- Información básica -->
      <div class="space-y-4 rounded-lg bg-gray-50 p-4">
        <h2 class="text-lg font-semibold text-gray-800">Información básica</h2>

        <!-- Nombre -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">Nombre *</label>
          <input
            v-model="formulario.nombre"
            type="text"
            placeholder="Nombre del artículo"
            class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
            @blur="generarSlug"
          />
          <span v-if="errores.nombre" class="mt-1 text-xs text-red-600">{{ errores.nombre[0] }}</span>
        </div>

        <!-- Slug -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">Slug (URL amigable) *</label>
          <input
            v-model="formulario.slug"
            type="text"
            placeholder="slug-del-articulo"
            class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
          />
          <span v-if="errores.slug" class="mt-1 text-xs text-red-600">{{ errores.slug[0] }}</span>
        </div>

        <!-- Descripción -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">Descripción</label>
          <textarea
            v-model="formulario.descripcion"
            placeholder="Descripción del artículo"
            rows="4"
            class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
          />
          <span v-if="errores.descripcion" class="mt-1 text-xs text-red-600">{{ errores.descripcion[0] }}</span>
        </div>
      </div>

      <!-- Pricing y Stock base -->
      <div class="space-y-4 rounded-lg bg-gray-50 p-4">
        <h2 class="text-lg font-semibold text-gray-800">Pricing</h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <!-- Precio base -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Precio base *</label>
            <input
              v-model.number="formulario.precio"
              type="number"
              step="0.01"
              placeholder="0.00"
              class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
            />
            <span v-if="errores.precio" class="mt-1 text-xs text-red-600">{{ errores.precio[0] }}</span>
          </div>

          <!-- SKU -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">SKU</label>
            <input
              v-model="formulario.sku"
              type="text"
              placeholder="SKU-001"
              class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
            />
            <span v-if="errores.sku" class="mt-1 text-xs text-red-600">{{ errores.sku[0] }}</span>
          </div>
        </div>
      </div>

      <!-- Categoría y Marca -->
      <div class="space-y-4 rounded-lg bg-gray-50 p-4">
        <h2 class="text-lg font-semibold text-gray-800">Clasificación</h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <!-- Categoría -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Categoría</label>
            <select
              v-model.number="formulario.categoria_id"
              class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
            >
              <option :value="undefined">Selecciona una categoría</option>
              <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
            </select>
          </div>

          <!-- Marca -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Marca</label>
            <select
              v-model.number="formulario.marca_id"
              class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
            >
              <option :value="undefined">Selecciona una marca</option>
              <option v-for="marc in marcas" :key="marc.id" :value="marc.id">{{ marc.nombre }}</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Estado -->
      <div class="space-y-4 rounded-lg bg-gray-50 p-4">
        <h2 class="text-lg font-semibold text-gray-800">Estado</h2>

        <div class="flex gap-6">
          <label class="flex items-center gap-2">
            <input v-model="formulario.activo" type="checkbox" class="h-4 w-4 rounded border-gray-300" />
            <span class="text-sm text-gray-700">Artículo activo</span>
          </label>
          <label class="flex items-center gap-2">
            <input v-model="formulario.destacado" type="checkbox" class="h-4 w-4 rounded border-gray-300" />
            <span class="text-sm text-gray-700">Destacado en inicio</span>
          </label>
        </div>
      </div>

      <!-- Imágenes -->
      <articulosImagesForm
        v-model:imagenes="formulario.imagenes"
        v-model:imagenes-eliminar="formulario.imagenes_eliminar"
        :imagenes-existentes="imagenesExistentes"
      />

      <!-- Variantes -->
      <articulosVariantesForm
        v-model="formulario.variantes"
        v-model:variantes-eliminar="formulario.variantes_eliminar"
        :colores="colores"
        :tallas="tallas"
        :sabores="sabores"
        :modelos="modelos"
        :tonos="tonos"
        :medidas="medidas"
        :plazas="plazas"
      />

      <!-- Botones de acción -->
      <div class="flex gap-3 border-t pt-6">
        <button
          type="submit"
          :disabled="cargando"
          class="rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
        >
          {{ cargando ? 'Guardando...' : isEditing ? 'Actualizar' : 'Crear artículo' }}
        </button>
        <router-link
          to="/administrador/articulos"
          class="rounded border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50"
        >
          Cancelar
        </router-link>
      </div>
    </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useArticuloStore } from '../../stores/articuloStore';
import { articuloService } from '../../services/articuloService';
import articulosImagesForm from './articulosImagesForm.vue';
import articulosVariantesForm from './articulosVariantesForm.vue';
import Swal from 'sweetalert2';

const router = useRouter();
const route = useRoute();
const articuloStore = useArticuloStore();

const isEditing = computed(() => !!route.params.id);
const cargando = ref(false);
const cargandoDatos = ref(false);
const errores = ref<Record<string, string[]>>({});
const categorias = ref<any[]>([]);
const marcas = ref<any[]>([]);
const colores = ref<any[]>([]);
const tallas = ref<any[]>([]);
const plazas = ref<any[]>([]);
const medidas = ref<any[]>([]);
const sabores = ref<any[]>([]);
const modelos = ref<any[]>([]);
const tonos = ref<any[]>([]);
const imagenesExistentes = ref<any[]>([]);

const formulario = ref({
  nombre: '',
  slug: '',
  descripcion: '',
  especificaciones: '',
  precio: 0,
  sku: '',
  categoria_id: undefined as number | undefined,
  marca_id: undefined as number | undefined,
  activo: true,
  destacado: false,
  imagenes: [] as File[],
  imagenes_eliminar: [] as number[],
  imagenes_orden: [] as number[],
  variantes: [] as any[],
  variantes_eliminar: [] as number[],
});

onMounted(async () => {
  cargandoDatos.value = true;

  // Cargar todas las opciones de select
  try {
    const [cats, ms, cols, tals, plaz, meds, sabs, mods, tons] = await Promise.all([
      articuloService.getCategoriasForSelect(),
      articuloService.getMarcasForSelect(),
      articuloService.getColoresForSelect(),
      articuloService.getTallasForSelect(),
      articuloService.getPlazasForSelect(),
      articuloService.getMedidasForSelect(),
      articuloService.getSaboresForSelect(),
      articuloService.getModelosForSelect(),
      articuloService.getTonosForSelect(),
    ]);

    categorias.value = cats || [];
    marcas.value = ms || [];
    colores.value = cols || [];
    tallas.value = tals || [];
    plazas.value = plaz || [];
    medidas.value = meds || [];
    sabores.value = sabs || [];
    modelos.value = mods || [];
    tonos.value = tons || [];
  } catch (error) {
    console.error('Error cargando datos de referencia:', error);
  }

  if (isEditing.value) {
    try {
      await articuloStore.fetchArticulo(Number(route.params.id));
      console.log('Artículo cargado:', articuloStore.selectedArticulo);

      if (articuloStore.selectedArticulo) {
        const art = articuloStore.selectedArticulo;
        console.log('Datos del artículo:', art);
        console.log('Variantes en artículo:', art.variantes);

        const variantesFormateadas = art.variantes && Array.isArray(art.variantes)
          ? art.variantes.map((v: any) => ({
              id: v.id,
              atributos: convertirAtributosAIds(v.atributos || {}),
              stock: v.stock || 0,
              activo: v.activo ?? true,
            }))
          : [];

        console.log('Variantes formateadas:', variantesFormateadas);

        // Cargar imágenes existentes
        imagenesExistentes.value = art.imagenes && Array.isArray(art.imagenes)
          ? art.imagenes.map((img: any) => ({
              id: img.id,
              ruta: img.ruta,
              principal: img.principal || false,
              orden: img.orden || 0
            }))
          : [];

        formulario.value = {
          nombre: art.nombre || '',
          slug: art.slug || '',
          descripcion: art.descripcion || '',
          especificaciones: art.especificaciones || '',
          precio: art.precio || 0,
          sku: art.sku || '',
          categoria_id: art.categoria_id || undefined,
          marca_id: art.marca_id || undefined,
          activo: art.activo ?? true,
          destacado: art.destacado ?? false,
          imagenes: [],
          imagenes_eliminar: [],
          imagenes_orden: art.imagenes && Array.isArray(art.imagenes) ? art.imagenes.map((i: any) => i.id) : [],
          variantes: variantesFormateadas,
          variantes_eliminar: [],
        };
        console.log('Formulario actualizado:', formulario.value);
        console.log('Imágenes existentes:', imagenesExistentes.value);
      } else {
        console.log('No se cargó el artículo');
      }
    } catch (error) {
      console.error('Error cargando artículo:', error);
    }
  }

  cargandoDatos.value = false;
});

const generarSlug = () => {
  if (formulario.value.nombre && !isEditing.value) {
    formulario.value.slug = formulario.value.nombre
      .toLowerCase()
      .replace(/[^\w\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-');
  }
};

const convertirAtributosAIds = (atributos: Record<string, string>): Record<string, number> => {
  const atributosConvertidos: Record<string, number> = {};

  for (const [tipo, nombre] of Object.entries(atributos)) {
    let lista: Array<{ id: number; nombre: string }> | undefined;

    if (tipo === 'colores') lista = colores.value;
    else if (tipo === 'tallas') lista = tallas.value;
    else if (tipo === 'sabores') lista = sabores.value;
    else if (tipo === 'modelos') lista = modelos.value;
    else if (tipo === 'tonos') lista = tonos.value;
    else if (tipo === 'medidas') lista = medidas.value;
    else if (tipo === 'plazas') lista = plazas.value;

    if (lista) {
      const item = lista.find(i => i.nombre === nombre);
      if (item) {
        atributosConvertidos[tipo] = item.id;
      }
    }
  }

  return atributosConvertidos;
};

const guardar = async () => {
  cargando.value = true;
  errores.value = {};

  try {
    // Filtrar solo imágenes que son archivos File válidos
    const imagenesValidas = formulario.value.imagenes.filter(img => img instanceof File);
    console.log('Imágenes válidas a enviar:', imagenesValidas);

    // Convertir IDs de atributos a nombres antes de enviar
    const variantes = formulario.value.variantes.map(variante => {
      const atributosConvertidos: Record<string, string> = {};

      for (const [tipo, id] of Object.entries(variante.atributos)) {
        let lista: Array<{ id: number; nombre: string }> | undefined;

        if (tipo === 'colores') lista = colores.value;
        else if (tipo === 'tallas') lista = tallas.value;
        else if (tipo === 'sabores') lista = sabores.value;
        else if (tipo === 'modelos') lista = modelos.value;
        else if (tipo === 'tonos') lista = tonos.value;
        else if (tipo === 'medidas') lista = medidas.value;
        else if (tipo === 'plazas') lista = plazas.value;

        if (lista) {
          const item = lista.find(i => i.id === id);
          if (item) {
            atributosConvertidos[tipo] = item.nombre;
          }
        }
      }

      return {
        ...variante,
        atributos: atributosConvertidos
      };
    });

    // Preparar datos con tipos correctos
    const datosArticulo = {
      nombre: formulario.value.nombre,
      slug: formulario.value.slug,
      descripcion: formulario.value.descripcion,
      especificaciones: formulario.value.especificaciones,
      precio: Number(formulario.value.precio),
      sku: formulario.value.sku,
      categoria_id: formulario.value.categoria_id || null,
      marca_id: formulario.value.marca_id || null,
      activo: Boolean(formulario.value.activo),
      destacado: Boolean(formulario.value.destacado),
      imagenes: imagenesValidas,
      imagenes_eliminar: Array.isArray(formulario.value.imagenes_eliminar) ? formulario.value.imagenes_eliminar : [],
      imagenes_orden: Array.isArray(formulario.value.imagenes_orden) ? formulario.value.imagenes_orden : [],
      variantes: Array.isArray(variantes) ? variantes : [],
      variantes_eliminar: Array.isArray(formulario.value.variantes_eliminar) ? formulario.value.variantes_eliminar : [],
    };

    console.log('Datos a enviar:', datosArticulo);

    if (isEditing.value) {
      await articuloStore.updateArticulo(Number(route.params.id), datosArticulo);
      Swal.fire('Éxito', 'Artículo actualizado correctamente', 'success');
    } else {
      await articuloStore.createArticulo(datosArticulo);
      Swal.fire('Éxito', 'Artículo creado correctamente', 'success');
    }
    await router.push('/administrador/articulos');
  } catch (error: any) {
    console.error('Error al guardar:', error);
    if (error?.response?.data?.errors) {
      errores.value = error.response.data.errors;
    }
    Swal.fire('Error', error?.response?.data?.message || 'Error al guardar artículo', 'error');
  } finally {
    cargando.value = false;
  }
};
</script>
