<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                <svg class="w-6 h-6 inline-block mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Datos de Empresa
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Administra la información de la empresa
            </p>
        </div>

        <!-- Dashboard de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Datos</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ store.paginationMeta.total }}</p>
                    </div>
                </div>
            </div>

            <!-- Activos -->
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
                            {{ empresaDatos.filter(d => d.activo).length }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Inactivos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Inactivos</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ empresaDatos.filter(d => !d.activo).length }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de Acciones y Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-4 border-b dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Botón Nuevo -->
                    <router-link 
                        to="/administrador/empresa/crear"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nuevo Dato
                    </router-link>

                    <!-- Filtros -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Búsqueda -->
                        <div class="relative">
                            <input
                                v-model="filters.search"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Buscar por título, contenido o clave..."
                                class="pl-10 pr-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white w-full"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Filtro por Estado -->
                        <select
                            v-model="filters.activo"
                            @change="onFilterChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option :value="null">Todos</option>
                            <option :value="true">Activos</option>
                            <option :value="false">Inactivos</option>
                        </select>

                        <!-- Ordenar por -->
                        <select
                            v-model="filters.sort_by"
                            @change="onFilterChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="orden">Orden</option>
                            <option value="titulo">Título</option>
                            <option value="clave">Clave</option>
                            <option value="created_at">Fecha creación</option>
                        </select>

                        <!-- Dirección -->
                        <select
                            v-model="filters.sort_order"
                            @change="onFilterChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="asc">Ascendente</option>
                            <option value="desc">Descendente</option>
                        </select>

                        <!-- Items por página -->
                        <select
                            v-model="filters.per_page"
                            @change="cambiarElementosPorPagina"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="10">10 por página</option>
                            <option value="15">15 por página</option>
                            <option value="25">25 por página</option>
                            <option value="50">50 por página</option>
                        </select>

                        <!-- Botón Limpiar -->
                        <button 
                            @click="limpiarFiltros"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 disabled:opacity-25 transition"
                            title="Limpiar filtros"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de Datos de Empresa -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Clave / Título
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Contenido
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Imagen
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Orden
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Loading State -->
                        <tr v-if="store.loading">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">Cargando datos de empresa...</p>
                            </td>
                        </tr>

                        <!-- Error State -->
                        <tr v-else-if="store.error">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">Error al cargar</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ store.error }}
                                </p>
                                <button
                                    @click="fetchData"
                                    class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Reintentar
                                </button>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-else-if="empresaDatos.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay datos de empresa</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Comienza creando un nuevo dato.
                                </p>
                                <div class="mt-6">
                                    <router-link
                                        to="/administrador/empresa/crear"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nuevo Dato
                                    </router-link>
                                </div>
                            </td>
                        </tr>

                        <!-- Datos de Empresa -->
                        <tr 
                            v-for="item in empresaDatos" 
                            :key="item.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                            <span>{{ item.titulo || 'Sin título' }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ item.clave }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            ID: {{ item.id }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ item.clave_label }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white line-clamp-2 max-w-xs">
                                    {{ truncateText(item.contenido || 'Sin contenido', 60) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="item.imagen_url" class="relative group">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                        <img
                                            :src="getImageUrl(item.imagen_url)"
                                            :alt="item.titulo || 'Imagen'"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <span v-else class="text-sm text-gray-400 italic">Sin imagen</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button
                                    @click="toggleEstado(item.id)"
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all',
                                        item.activo
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800'
                                    ]"
                                >
                                    <span class="w-2 h-2 rounded-full mr-1" :class="item.activo ? 'bg-green-500' : 'bg-red-500'"></span>
                                    {{ item.activo_label || (item.activo ? 'Activo' : 'Inactivo') }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ item.orden }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ formatDate(item.created_at) }}
                                <div v-if="item.updated_at !== item.created_at" class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    Editado: {{ formatDate(item.updated_at) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Editar -->
                                    <router-link
                                        :to="`/administrador/empresa/${item.id}/editar`"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </router-link>

                                    <!-- Cambiar Estado -->
                                    <button
                                        @click="toggleEstado(item.id)"
                                        :class="[
                                            'hover:text-yellow-900 dark:hover:text-yellow-300',
                                            item.activo 
                                                ? 'text-yellow-600 dark:text-yellow-400' 
                                                : 'text-gray-600 dark:text-gray-400'
                                        ]"
                                        :title="item.activo ? 'Desactivar' : 'Activar'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path v-if="item.activo" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>

                                    <!-- Copiar Clave -->
                                    <button
                                        @click="copiarClave(item.clave)"
                                        class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300"
                                        title="Copiar clave"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>

                                    <!-- Eliminar -->
                                    <button
                                        @click="mostrarModalEliminar(item)"
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
            <div v-if="store.paginationMeta.last_page > 1" class="px-6 py-4 border-t dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="text-sm text-gray-700 dark:text-gray-400">
                        Mostrando 
                        <span class="font-medium">{{ (store.paginationMeta.current_page - 1) * store.paginationMeta.per_page + 1 }}</span>
                        a 
                        <span class="font-medium">{{ Math.min(store.paginationMeta.current_page * store.paginationMeta.per_page, store.paginationMeta.total) }}</span>
                        de 
                        <span class="font-medium">{{ store.paginationMeta.total }}</span>
                        resultados
                    </div>
                    
                    <div class="flex space-x-2">
                        <button
                            @click="cambiarPagina(store.paginationMeta.current_page - 1)"
                            :disabled="store.paginationMeta.current_page === 1"
                            :class="[
                                'px-3 py-1 rounded-md text-sm font-medium',
                                store.paginationMeta.current_page === 1
                                    ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                            ]"
                        >
                            Anterior
                        </button>
                        
                        <div class="flex space-x-1">
                            <button
                                v-for="page in paginas"
                                :key="page"
                                @click="cambiarPagina(page)"
                                :class="[
                                    'px-3 py-1 rounded-md text-sm font-medium',
                                    typeof page === 'number' && page === store.paginationMeta.current_page
                                        ? 'bg-blue-600 text-white'
                                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                                ]"
                                :disabled="typeof page !== 'number'"
                            >
                                {{ page }}
                            </button>
                        </div>
                        
                        <button
                            @click="cambiarPagina(store.paginationMeta.current_page + 1)"
                            :disabled="store.paginationMeta.current_page === store.paginationMeta.last_page"
                            :class="[
                                'px-3 py-1 rounded-md text-sm font-medium',
                                store.paginationMeta.current_page === store.paginationMeta.last_page
                                    ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                            ]"
                        >
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">
                    ¿Eliminar dato?
                </h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ¿Estás seguro de que deseas eliminar "<span class="font-medium">{{ datoAEliminar?.titulo || datoAEliminar?.clave_label }}</span>"? 
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="items-center px-4 py-3 mt-4">
                    <button
                        @click="cerrarModalEliminar"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="confirmarEliminar"
                        :disabled="eliminando"
                        :class="[
                            'px-4 py-2 text-white text-base font-medium rounded-md w-24',
                            eliminando 
                                ? 'bg-red-400 cursor-not-allowed' 
                                : 'bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300'
                        ]"
                    >
                        <span v-if="eliminando" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Eliminando...
                        </span>
                        <span v-else>Eliminar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div v-if="showToast" class="fixed bottom-4 right-4 z-50">
        <div :class="[
            'px-4 py-3 rounded-lg shadow-lg animate-slide-up border',
            toastType === 'success' 
                ? 'bg-green-100 border-green-400 text-green-700' 
                : 'bg-red-100 border-red-400 text-red-700'
        ]">
            <div class="flex items-center gap-2">
                <svg v-if="toastType === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <span>{{ toastMessage }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useEmpresaDatoStore } from '../../stores/empresa-dato'
import type { EmpresaDato, EmpresaDatoFilter } from '../../types/empresa-dato'
import debounce from 'lodash/debounce'

// Store y Router
const store = useEmpresaDatoStore()
const router = useRouter()

// Estado
const filters = ref<EmpresaDatoFilter>({
    search: '',
    activo: null,
    sort_by: 'orden',
    sort_order: 'asc',
    page: 1,
    per_page: 15
})

const getImageUrl = (path: string) => {
    if (!path) return '/assets/images/placeholder.png';
    if (path.startsWith('http')) return path;
    return `/storage/${path}`;
};

const showDeleteModal = ref(false)
const datoAEliminar = ref<EmpresaDato | null>(null)
const eliminando = ref(false)
const showToast = ref(false)
const toastMessage = ref('')
const toastType = ref<'success' | 'error'>('success')

// Computed
const empresaDatos = computed(() => store.empresaDatos)
const paginas = computed(() => {
    const meta = store.paginationMeta
    const current = meta.current_page
    const last = meta.last_page
    const delta = 2
    const range = []
    const rangeWithDots = []
    
    for (let i = 1; i <= last; i++) {
        if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
            range.push(i)
        }
    }
    
    let prev = 0
    for (const i of range) {
        if (prev !== i - 1) {
            rangeWithDots.push('...')
        }
        rangeWithDots.push(i)
        prev = i
    }
    
    return rangeWithDots.filter(p => typeof p === 'number')
})

// Métodos
const fetchData = async () => {
    await store.fetchEmpresaDatos(filters.value)
}

const debouncedSearch = debounce(() => {
    filters.value.page = 1
    fetchData()
}, 500)

const onFilterChange = () => {
    filters.value.page = 1
    fetchData()
}

const cambiarPagina = (page: number) => {
    if (page >= 1 && page <= store.paginationMeta.last_page) {
        filters.value.page = page
        fetchData()
    }
}

const cambiarElementosPorPagina = () => {
    filters.value.page = 1
    fetchData()
}

const limpiarFiltros = () => {
    filters.value = {
        search: '',
        activo: null,
        sort_by: 'orden',
        sort_order: 'asc',
        page: 1,
        per_page: 15
    }
    fetchData()
}

const toggleEstado = async (id: number) => {
    try {
        await store.toggleActivo(id)
        mostrarToast('Estado actualizado correctamente', 'success')
    } catch (error) {
        mostrarToast('Error al cambiar el estado', 'error')
    }
}

const mostrarModalEliminar = (dato: EmpresaDato) => {
    datoAEliminar.value = dato
    showDeleteModal.value = true
}

const cerrarModalEliminar = () => {
    showDeleteModal.value = false
    setTimeout(() => {
        datoAEliminar.value = null
        eliminando.value = false
    }, 300)
}

const confirmarEliminar = async () => {
    if (!datoAEliminar.value) return
    
    eliminando.value = true
    try {
        await store.deleteEmpresaDato(datoAEliminar.value.id)
        cerrarModalEliminar()
        mostrarToast('Dato eliminado correctamente', 'success')
    } catch (error) {
        mostrarToast('Error al eliminar el dato', 'error')
        eliminando.value = false
    }
}

const copiarClave = (clave: string) => {
    navigator.clipboard.writeText(clave)
    mostrarToast('Clave copiada al portapapeles', 'success')
}

const mostrarToast = (mensaje: string, tipo: 'success' | 'error' = 'success') => {
    toastMessage.value = mensaje
    toastType.value = tipo
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}

const truncateText = (text: string, length: number) => {
    if (!text) return 'Sin contenido'
    if (text.length <= length) return text
    return text.substring(0, length) + '...'
}

const formatDate = (dateString: string) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}

// Lifecycle
onMounted(async () => {
    await fetchData()
})

// Watch para cambios en los filtros
watch(
    () => [filters.value.activo, filters.value.sort_by, filters.value.sort_order],
    () => {
        filters.value.page = 1
        fetchData()
    }
)
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.animate-slide-up {
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>