<!-- resources/js/src/views/ReporteEntregasSectores/index.vue -->
<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <!-- Header -->
        <div class="p-6 border-b dark:border-gray-700">
            <h2 class="text-xl font-semibold flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Reporte de Entregas por Sectores
            </h2>
        </div>

        <!-- Filtros -->
        <div class="p-6 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Ubicación
                    </label>
                    <select
                        v-model="filtros.ubicacion"
                        @change="cargarSectores"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                    >
                        <option value="">Todas</option>
                        <option value="Quevedo">Quevedo</option>
                        <option value="Buena Fe">Buena Fe</option>
                        <option value="Valencia">Valencia</option>
                        <option value="La Maná">La Maná</option>
                        <option value="El Empalme">El Empalme</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Sector
                    </label>
                    <select
                        v-model="filtros.sector_id"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                    >
                        <option value="">Todos</option>
                        <option v-for="sector in sectores" :key="sector.id" :value="sector.id">
                            {{ sector.nombre }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha Inicio
                    </label>
                    <input
                        v-model="filtros.fecha_inicio"
                        type="date"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha Fin
                    </label>
                    <input
                        v-model="filtros.fecha_fin"
                        type="date"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                    />
                </div>
            </div>

            <div class="flex justify-end mt-4 space-x-2">
                <button
                    @click="generarReporte"
                    :disabled="loading"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center disabled:opacity-50"
                >
                    <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Generar Reporte
                </button>
                <button
                    @click="exportarExcel"
                    :disabled="loading || entregas.length === 0"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center disabled:opacity-50"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exportar Excel
                </button>
            </div>
        </div>

        <!-- Tabla de resultados -->
        <div class="p-6">
            <div v-if="loading" class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-2 text-gray-600">Generando reporte...</p>
            </div>

            <div v-else-if="entregas.length === 0" class="text-center py-8 text-gray-500">
                No hay entregas para los filtros seleccionados
            </div>

            <div v-else>
                <!-- Resumen -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <p class="text-sm text-green-600 dark:text-green-400">Total Entregas</p>
                        <p class="text-2xl font-bold">{{ entregas.length }}</p>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <p class="text-sm text-blue-600 dark:text-blue-400">Sectores</p>
                        <p class="text-2xl font-bold">{{ sectoresUnicos }}</p>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                        <p class="text-sm text-purple-600 dark:text-purple-400">Clientes Únicos</p>
                        <p class="text-2xl font-bold">{{ clientesUnicos }}</p>
                    </div>
                </div>

                <!-- Tabla detallada -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sector</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dirección</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Referencia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            <tr v-for="entrega in entregas" :key="entrega.id">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium">{{ entrega.user_id }}</div>
                                    <!-- <div class="text-xs text-gray-500">{{ entrega.user?.email }}</div> -->
                                </td>
                                <td class="px-6 py-4">{{ entrega.ubicacion }}</td>
                                <td class="px-6 py-4">{{ entrega.sector?.nombre || '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        {{ entrega.calle_principal }} y {{ entrega.calle_secundaria }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ entrega.barrio }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">{{ entrega.referencia }}</div>
                                    <div class="text-xs text-gray-500">Casa {{ entrega.color_casa }}</div>
                                </td>
                                <td class="px-6 py-4">#{{ entrega.pedido_id }}</td>
                                <td class="px-6 py-4">{{ formatDate(entrega.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import axios from 'axios';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';
import type { DireccionEntrega, Sector, ReporteFiltros } from '../../types/entrega';

const loading = ref<boolean>(false);
const entregas = ref<DireccionEntrega[]>([]);
const sectores = ref<Sector[]>([]);

const filtros = ref<ReporteFiltros>({
    ubicacion: '',
    sector_id: '',
    fecha_inicio: '',
    fecha_fin: ''
});

// Computed properties con tipos explícitos
const sectoresUnicos = computed<number>(() => {
    return [...new Set(entregas.value.map(e => e.sector?.nombre).filter(Boolean))].length;
});

const clientesUnicos = computed<number>(() => {
    return [...new Set(entregas.value.map(e => e.user_id))].length;
});

// Métodos con tipos
const formatDate = (date: string): string => {
    return format(new Date(date), 'dd/MM/yyyy', { locale: es });
};

const cargarSectores = async (): Promise<void> => {
    try {
        const params = filtros.value.ubicacion ? { ubicacion: filtros.value.ubicacion } : {};
        const { data } = await axios.get<Sector[]>('/api/sectores', { params });
        sectores.value = data;
    } catch (error) {
        console.error('Error cargando sectores:', error);
    }
};

const generarReporte = async (): Promise<void> => {
    loading.value = true;
    try {
        const { data } = await axios.get<DireccionEntrega[]>('/api/reportes/entregas-sectores', {
            params: filtros.value
        });
        entregas.value = data;
    } catch (error) {
        console.error('Error generando reporte:', error);
    } finally {
        loading.value = false;
    }
};

const exportarExcel = async (): Promise<void> => {
    try {
        const response = await axios.get('/api/reportes/entregas-sectores/export', {
            params: filtros.value,
            responseType: 'blob'
        });
        
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `reporte_entregas_${format(new Date(), 'yyyyMMdd')}.xlsx`);
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (error) {
        console.error('Error exportando:', error);
    }
};

// Watch para cambios en ubicación
watch(() => filtros.value.ubicacion, () => {
    filtros.value.sector_id = ''; // Reset sector cuando cambia ubicación
    cargarSectores();
});

// Cargar sectores al montar
cargarSectores();
</script>

<style scoped>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>