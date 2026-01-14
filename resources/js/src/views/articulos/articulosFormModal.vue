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
    // 1. Filtrar solo imágenes que son archivos File válidos
    const imagenesValidas = formulario.value.imagenes.filter(img => img instanceof File);
    console.log('Imágenes válidas a enviar:', imagenesValidas);

    // 2. Preparar variantes CORREGIDO - asegurar atributos siempre es objeto
    const variantesAEnviar = formulario.value.variantes.map(variante => {
      console.log('Variante original:', variante);
      
      // Crear atributos convertidos o objeto vacío
      const atributosConvertidos: Record<string, string> = {};
      
      // Si hay atributos en la variante, convertirlos
      if (variante.atributos && typeof variante.atributos === 'object') {
        for (const [tipo, id] of Object.entries(variante.atributos)) {
          // Solo procesar si el ID es válido
          if (id && id !== '') {
            let lista: Array<{ id: number; nombre: string }> | undefined;

            if (tipo === 'colores') lista = colores.value;
            else if (tipo === 'tallas') lista = tallas.value;
            else if (tipo === 'sabores') lista = sabores.value;
            else if (tipo === 'modelos') lista = modelos.value;
            else if (tipo === 'tonos') lista = tonos.value;
            else if (tipo === 'medidas') lista = medidas.value;
            else if (tipo === 'plazas') lista = plazas.value;

            if (lista && Array.isArray(lista)) {
              const item = lista.find(i => i.id === Number(id));
              if (item) {
                atributosConvertidos[tipo] = item.nombre;
              }
            }
          }
        }
      }
      
      // VARIANTE FINAL - Asegurar todos los campos requeridos
      const varianteFinal = {
        ...variante,
        atributos: Object.keys(atributosConvertidos).length > 0 
          ? atributosConvertidos 
          : {}, // ← OBJETO VACÍO SI NO HAY ATRIBUTOS
        stock: Number(variante.stock) || 0,
        activo: variante.activo !== undefined ? Boolean(variante.activo) : true,
        // Asegurar que no haya campos undefined
        sku: variante.sku || '',
        precio: variante.precio || 0
      };
      
      console.log('Variante procesada:', varianteFinal);
      return varianteFinal;
    });

    console.log('Todas las variantes a enviar:', variantesAEnviar);

    // 3. Preparar FormData para enviar
    const formData = new FormData();
    
    // Campos básicos
    formData.append('nombre', formulario.value.nombre);
    formData.append('slug', formulario.value.slug);
    formData.append('descripcion', formulario.value.descripcion || '');
    formData.append('especificaciones', formulario.value.especificaciones || '');
    formData.append('precio', String(formulario.value.precio || 0));
    formData.append('sku', formulario.value.sku || '');
    formData.append('activo', formulario.value.activo ? '1' : '0');
    formData.append('destacado', formulario.value.destacado ? '1' : '0');
    
    if (formulario.value.categoria_id) {
      formData.append('categoria_id', String(formulario.value.categoria_id));
    }
    if (formulario.value.marca_id) {
      formData.append('marca_id', String(formulario.value.marca_id));
    }

    // Imágenes
    imagenesValidas.forEach((imagen, index) => {
      formData.append(`imagenes[${index}]`, imagen);
    });

    // Variantes como JSON
    formData.append('variantes', JSON.stringify(variantesAEnviar));

    // Log para debug
    console.log('FormData contenido:');
    for (let [key, value] of formData.entries()) {
      console.log(key, value);
    }

    // 4. Enviar petición
    let url = '/tienda/public/api/articulos';
    let method = 'POST';
    
    if (isEditing.value) {
      url = `/tienda/public/api/articulos/${route.params.id}`;
      method = 'POST'; // o 'PUT' dependiendo de tu backend
    }

    const response = await fetch(url, {
      method: method,
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Accept': 'application/json',
        // NO incluir 'Content-Type': 'multipart/form-data' - fetch lo hace automáticamente
      },
      body: formData
    });

    const data = await response.json();
    console.log('Respuesta del servidor:', data);

    if (!response.ok) {
      throw new Error(data.message || 'Error en la petición');
    }

    if (data.success) {
      Swal.fire({
        icon: 'success',
        title: isEditing.value ? '¡Actualizado!' : '¡Creado!',
        text: data.message || 'Operación exitosa',
        timer: 2000
      });
      await router.push('/administrador/articulos');
    } else {
      throw new Error(data.message || 'Error desconocido');
    }

  } catch (error: any) {
    console.error('Error completo:', error);
    
    // Mostrar error detallado
    let mensajeError = 'Error al guardar artículo';
    if (error.response) {
      const data = await error.response.json();
      mensajeError = data.message || mensajeError;
      if (data.errors) {
        errores.value = data.errors;
        console.log('Errores de validación:', data.errors);
      }
    } else if (error.message) {
      mensajeError = error.message;
    }
    
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: mensajeError,
      footer: '<small>Revisa la consola para más detalles</small>'
    });
  } finally {
    cargando.value = false;
  }
};
</script>
