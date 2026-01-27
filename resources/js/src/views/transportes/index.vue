<!-- src/views/transportes/IndexView.vue -->
<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                <svg class="w-6 h-6 inline-block mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Gestión de Transportes
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Administre las rutas de transporte y cooperativas
            </p>
        </div>

        <!-- Dashboard de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Rutas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Rutas</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ estadisticas?.total || 0 }}</p>
                        <div class="flex gap-2 mt-1 text-xs">
                            <span class="text-green-600 dark:text-green-400">✓ {{ estadisticas?.activos || 0 }} act.</span>
                            <span class="text-gray-400">|</span>
                            <span class="text-gray-500">{{ estadisticas?.inactivos || 0 }} inac.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Precio Promedio -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Precio Promedio</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ formatCurrency(estadisticas?.precio_promedio || 0) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Por ruta</p>
                    </div>
                </div>
            </div>

            <!-- Cooperativas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Cooperativas</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ estadisticas?.cooperativas_unicas || 0 }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Registradas</p>
                    </div>
                </div>
            </div>

            <!-- Tiempo Promedio -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tiempo Promedio</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ formatTime(estadisticas?.tiempo_promedio || 0) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Por ruta</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de Acciones y Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-4 border-b dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Botones de Acción -->
                    <div class="flex gap-2">
                        <router-link 
                            to="/administrador/transportes/crear"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nuevo Transporte
                        </router-link>
                        
                        <button 
                            @click="exportToExcel"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Exportar
                        </button>
                    </div>

                    <!-- Filtros -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Búsqueda -->
                        <div class="relative">
                            <input
                                v-model="filtros.search"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Buscar ruta o cooperativa..."
                                class="pl-10 pr-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white w-full"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Filtro por Cooperativa -->
                        <select
                            v-model="filtros.cooperativa"
                            @change="applyFilters"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Todas las cooperativas</option>
                            <option v-for="coop in cooperativas" :value="coop" :key="coop">
                                {{ coop }}
                            </option>
                        </select>

                        <!-- Filtro por Estado -->
                        <select
                            v-model="filtros.estado"
                            @change="applyFilters"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>

                        <!-- Ordenar por -->
                        <select
                            v-model="filtros.order_by"
                            @change="applyFilters"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="ruta">Ruta</option>
                            <option value="precio">Precio</option>
                            <option value="cooperativa">Cooperativa</option>
                            <option value="created_at">Fecha Creación</option>
                        </select>

                        <!-- Items por página -->
                        <select
                            v-model="filtros.per_page"
                            @change="applyFilters"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="10">10 por página</option>
                            <option value="15">15 por página</option>
                            <option value="25">25 por página</option>
                            <option value="50">50 por página</option>
                        </select>

                        <!-- Botón Reset -->
                        <button 
                            @click="resetFilters"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 disabled:opacity-25 transition"
                            title="Restablecer filtros"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de Transportes -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Ruta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Cooperativa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Precio
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tiempo Estimado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Loading State -->
                        <tr v-if="loading">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">Cargando transportes...</p>
                            </td>
                        </tr>

                        <!-- Error State -->
                        <tr v-else-if="error">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">Error al cargar</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ error }}
                                </p>
                                <button 
                                    @click="loadData"
                                    class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                >
                                    Reintentar
                                </button>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-else-if="transportes.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay transportes</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Comienza creando un nuevo transporte.
                                </p>
                                <div class="mt-6">
                                    <router-link
                                        to="/administrador/transportes/crear"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nuevo Transporte
                                    </router-link>
                                </div>
                            </td>
                        </tr>

                        <!-- Transportes -->
                        <tr 
                            v-for="transporte in transportes" 
                            :key="transporte.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ transporte.ruta }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            ID: {{ transporte.id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    {{ transporte.cooperativa }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ transporte.precio_formateado || formatCurrency(0) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="transporte.tiempo_estimado_formateado" class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm">{{ transporte.tiempo_estimado_formateado }}</span>
                                </div>
                                <span v-else class="text-sm text-gray-400 italic">No especificado</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button 
                                    @click="toggleEstado(transporte)"
                                    :class="[
                                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium cursor-pointer transition-all hover:opacity-80',
                                        transporte.estado 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                                    ]"
                                    :title="`Click para ${transporte.estado ? 'desactivar' : 'activar'}`"
                                >
                                    <span class="w-2 h-2 rounded-full mr-1" :class="transporte.estado ? 'bg-green-500' : 'bg-gray-500'"></span>
                                    {{ transporte.estado_label }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Editar -->
                                    <router-link
                                        :to="`/administrador/transportes/${transporte.id}/editar`"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </router-link>

                                    <!-- Eliminar -->
                                    <button
                                        @click="confirmDelete(transporte)"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        title="Eliminar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div v-if="meta && meta.last_page > 1" class="px-6 py-4 border-t dark:border-gray-700">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-700 dark:text-gray-400">
                        Mostrando 
                        <span class="font-medium">{{ meta.from }}</span>
                        a 
                        <span class="font-medium">{{ meta.to }}</span>
                        de 
                        <span class="font-medium">{{ meta.total }}</span>
                        registros
                    </div>
                    <div class="flex items-center space-x-2">
                        <button
                            @click="changePage(meta.current_page - 1)"
                            :disabled="meta.current_page === 1"
                            :class="[
                                'px-3 py-1 rounded-md text-sm font-medium',
                                meta.current_page === 1
                                    ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                            ]"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        
                        <div class="flex space-x-1">
                            <button
                                v-for="page in paginasDisponibles"
                                :key="page"
                                @click="changePage(page)"
                                :class="[
                                    'px-3 py-1 rounded-md text-sm font-medium',
                                    page === meta.current_page
                                        ? 'bg-blue-600 text-white'
                                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                                ]"
                            >
                                {{ page }}
                            </button>
                        </div>
                        
                        <button
                            @click="changePage(meta.current_page + 1)"
                            :disabled="meta.current_page === meta.last_page"
                            :class="[
                                'px-3 py-1 rounded-md text-sm font-medium',
                                meta.current_page === meta.last_page
                                    ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                            ]"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import { useTransporteStore } from '../../stores/transporteStore';
import { useConfirm } from '../../composables/useConfirm';
import { debounce } from 'lodash-es';
import type { Transporte } from '../../types/transporte';

const transporteStore = useTransporteStore();
const confirm = useConfirm();
const error = ref<string | null>(null);

// Computed properties
const transportes = computed(() => transporteStore.transportes);
const estadisticas = computed(() => transporteStore.estadisticas);
const cooperativas = computed(() => transporteStore.cooperativas);
const loading = computed(() => transporteStore.loading);
const filtros = computed(() => transporteStore.filtros);
const meta = computed(() => transporteStore.meta);

const paginasDisponibles = computed(() => {
    if (!meta.value) return [];
    const pages = [];
    const totalPages = meta.value.last_page;
    const currentPage = meta.value.current_page;
    
    let start = Math.max(1, currentPage - 2);
    let end = Math.min(totalPages, currentPage + 2);
    
    if (currentPage <= 3) {
        end = Math.min(5, totalPages);
    }
    if (currentPage >= totalPages - 2) {
        start = Math.max(1, totalPages - 4);
    }
    
    for (let i = start; i <= end; i++) {
        pages.push(i);
    }
    
    return pages;
});

// Formateadores
const formatCurrency = (amount: number): string => {
    return new Intl.NumberFormat('es-EC', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    }).format(amount);
};

const formatTime = (hours: number | null): string => {
    if (!hours || hours === 0) return 'N/A';
    
    const h = Math.floor(hours);
    const m = Math.round((hours - h) * 60);
    
    if (h > 0 && m > 0) return `${h}h ${m}m`;
    if (h > 0) return `${h}h`;
    return `${m}m`;
};

// Funciones de filtrado
const applyFilters = async (): Promise<void> => {
    try {
        error.value = null;
        await transporteStore.fetchTransportes();
    } catch (err: any) {
        error.value = err.message || 'Error al cargar transportes';
    }
};

const resetFilters = async (): Promise<void> => {
    transporteStore.resetFiltros();
    await applyFilters();
};

const debouncedSearch = debounce(async () => {
    await applyFilters();
}, 500);

// Paginación
const changePage = async (page: number): Promise<void> => {
    if (page < 1 || page > meta.value!.last_page) return;
    await transporteStore.fetchTransportes({ page });
};

// Funciones de estado
const toggleEstado = (transporte: Transporte): void => {
    confirm.show({
        title: 'Cambiar estado',
        message: `¿Está seguro de cambiar el estado de la ruta "${transporte.ruta}" - ${transporte.cooperativa}?`,
        type: 'warning',
        confirmText: 'Sí, cambiar',
        cancelText: 'Cancelar',
        onConfirm: async () => {
            try {
                await transporteStore.toggleEstado(transporte.id);
                // Podrías mostrar un toast aquí
            } catch (err: any) {
                error.value = err.message || 'Error al cambiar estado';
            }
        }
    });
};

const confirmDelete = (transporte: Transporte): void => {
    confirm.show({
        title: 'Eliminar transporte',
        message: `¿Está seguro de eliminar la ruta "${transporte.ruta}" - ${transporte.cooperativa}? Esta acción no se puede deshacer.`,
        type: 'danger',
        confirmText: 'Sí, eliminar',
        cancelText: 'Cancelar',
        onConfirm: async () => {
            try {
                await transporteStore.deleteTransporte(transporte.id);
                // Podrías mostrar un toast aquí
            } catch (err: any) {
                error.value = err.message || 'Error al eliminar transporte';
            }
        }
    });
};

const exportToExcel = async (): Promise<void> => {
    try {
        await transporteStore.exportToExcel();
        // Podrías mostrar un toast de éxito aquí
    } catch (err: any) {
        error.value = err.message || 'Error al exportar';
    }
};

const loadData = async (): Promise<void> => {
    try {
        error.value = null;
        await Promise.all([
            transporteStore.fetchTransportes(),
            transporteStore.fetchCooperativas(),
            transporteStore.fetchEstadisticas()
        ]);
    } catch (err: any) {
        error.value = err.message || 'Error al cargar datos';
    }
};

// Watch for filter changes
watch(() => [filtros.value.cooperativa, filtros.value.estado], () => {
    applyFilters();
});

// Lifecycle
onMounted(async () => {
    await loadData();
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

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

/* Mejoras de accesibilidad */
button:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

a:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}
</style>