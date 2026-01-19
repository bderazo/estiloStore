<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        Estadísticas de Folletos
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Analiza el rendimiento y descargas de tus catálogos
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <button
                        @click="exportarEstadisticas"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 transition"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Exportar
                    </button>
                    <router-link
                        to="/administrador/folletos"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 transition"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </router-link>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="store.loading" class="flex justify-center items-center h-96">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Cargando estadísticas...</p>
            </div>
        </div>

        <!-- Estadísticas -->
        <div v-else-if="store.estadisticas" class="space-y-8">
            <!-- Resumen General -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Folletos</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ store.estadisticas.total_folletos }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Activos</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ store.estadisticas.total_activos }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Descargas</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ store.estadisticas.total_descargas }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.933 12.8a1 1 0 000-1.6L6.6 7.2A1 1 0 005 8v8a1 1 0 001.6.8l5.333-4zM19.933 12.8a1 1 0 000-1.6l-5.333-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.333-4z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Promedio/Folleto</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ store.estadisticas.promedio_descargas.toFixed(1) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Folleto más Popular -->
            <div v-if="store.estadisticas.folleto_popular" class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Folleto Más Popular
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ store.estadisticas.folleto_popular.nombre }}
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ store.estadisticas.folleto_popular.descripcion || 'Sin descripción' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                                {{ store.estadisticas.folleto_popular.descargas }}
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">descargas totales</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                            <span>Tamaño: {{ store.estadisticas.folleto_popular.tamano_archivo }}</span>
                            <span>Estado: 
                                <span :class="[
                                    'ml-1 px-2 py-1 text-xs rounded-full',
                                    store.estadisticas.folleto_popular.estado 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ]">
                                    {{ store.estadisticas.folleto_popular.estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </span>
                            <span>Creado: {{ formatFecha(store.estadisticas.folleto_popular.created_at) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Descargas por Mes -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Descargas por Mes (Últimos 6 meses)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Mes
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Total Descargas
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Porcentaje
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Barra
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="item in store.estadisticas.descargas_por_mes" :key="item.mes">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ formatMes(item.mes) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ item.total_descargas }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ calcularPorcentaje(item.total_descargas) }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                            <div 
                                                class="bg-gradient-to-r from-blue-500 to-purple-600 h-2.5 rounded-full" 
                                                :style="{ width: calcularPorcentaje(item.total_descargas) + '%' }"
                                            ></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top 10 Folletos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Top 10 Folletos Más Descargados
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div 
                            v-for="(folleto, index) in topFolletos" 
                            :key="folleto.id"
                            class="flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg" 
                                     :class="getRankColor(index)">
                                    <span class="font-bold text-white">
                                        {{ index + 1 }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ folleto.nombre }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-md">
                                        {{ folleto.descripcion || 'Sin descripción' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ folleto.descargas }}
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">descargas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div v-else class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay estadísticas disponibles</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                No se pudieron cargar las estadísticas de los folletos.
            </p>
            <div class="mt-6">
                <button
                    @click="reloadEstadisticas"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reintentar
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useFolletoStore } from '../../stores/folletoStore';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';

const store = useFolletoStore();

// Computed
const topFolletos = computed(() => {
    return [...store.folletos]
        .sort((a, b) => b.descargas - a.descargas)
        .slice(0, 10);
});

const maxDescargas = computed(() => {
    return store.estadisticas?.descargas_por_mes.reduce((max, item) => 
        Math.max(max, item.total_descargas), 0) || 0;
});

// Métodos
const formatFecha = (fecha: string) => {
    return format(new Date(fecha), 'dd/MM/yyyy', { locale: es });
};

const formatMes = (mes: string) => {
    const [year, month] = mes.split('-');
    const date = new Date(parseInt(year), parseInt(month) - 1, 1);
    return format(date, 'MMMM yyyy', { locale: es });
};

const calcularPorcentaje = (descargas: number) => {
    if (maxDescargas.value === 0) return 0;
    return Math.round((descargas / maxDescargas.value) * 100);
};

const getRankColor = (index: number): string => {
    const colors = [
        'bg-gradient-to-r from-yellow-500 to-yellow-600',
        'bg-gradient-to-r from-gray-400 to-gray-500',
        'bg-gradient-to-r from-orange-500 to-orange-600',
        'bg-gradient-to-r from-blue-500 to-blue-600',
        'bg-gradient-to-r from-blue-500 to-blue-600',
        'bg-gradient-to-r from-indigo-500 to-indigo-600',
        'bg-gradient-to-r from-indigo-500 to-indigo-600',
        'bg-gradient-to-r from-purple-500 to-purple-600',
        'bg-gradient-to-r from-purple-500 to-purple-600',
        'bg-gradient-to-r from-pink-500 to-pink-600'
    ];
    return colors[index] || 'bg-gray-500';
};

const exportarEstadisticas = () => {
    if (!store.estadisticas) return;
    
    const data = {
        ...store.estadisticas,
        fecha_exportacion: new Date().toISOString()
    };
    
    const dataStr = JSON.stringify(data, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
    
    const exportFileDefaultName = `estadisticas-folletos-${new Date().toISOString().split('T')[0]}.json`;
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
};

const reloadEstadisticas = async () => {
    await store.fetchEstadisticas();
};

// Lifecycle
onMounted(() => {
    store.fetchEstadisticas();
});
</script>