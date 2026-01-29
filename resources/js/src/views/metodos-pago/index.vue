<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                <svg class="w-6 h-6 inline-block mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Métodos de Pago
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Gestiona todos tus métodos de pago disponibles para transacciones
            </p>
        </div>

        <!-- Dashboard de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ totales.total }}</p>
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
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ totales.activos }}</p>
                    </div>
                </div>
            </div>

            <!-- Transferencias -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Transferencias</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ totales.por_tipo.Transferencia || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- QR -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">QR</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ totales.por_tipo.QR || 0 }}</p>
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
                        to="/administrador/metodos-pago/crear"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nuevo Método
                    </router-link>

                    <!-- Filtros -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Búsqueda -->
                        <div class="relative">
                            <input
                                v-model="filtros.search"
                                @input="onFiltroChange"
                                type="text"
                                placeholder="Buscar por banco, titular o cuenta..."
                                class="pl-10 pr-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white w-full"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Filtro por Tipo -->
                        <select
                            v-model="filtros.tipo"
                            @change="onFiltroChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Todos los tipos</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="QR">QR</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Otro">Otro</option>
                        </select>

                        <!-- Filtro por Estado -->
                        <select
                            v-model="filtros.activo"
                            @change="onFiltroChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Todos</option>
                            <option value="true">Activos</option>
                            <option value="false">Inactivos</option>
                        </select>

                        <!-- Items por página -->
                        <select
                            v-model="filtros.per_page"
                            @change="onFiltroChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="10">10 por página</option>
                            <option value="15">15 por página</option>
                            <option value="25">25 por página</option>
                            <option value="50">50 por página</option>
                        </select>

                        <!-- Botón Reset -->
                        <button 
                            @click="resetFiltros"
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

            <!-- Tabla de Métodos de Pago -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Banco / Método
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Titular / Cuenta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Instrucciones
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
                                <p class="mt-2 text-gray-600 dark:text-gray-400">Cargando métodos de pago...</p>
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
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-else-if="metodosPago.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay métodos de pago</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Comienza creando un nuevo método de pago.
                                </p>
                                <div class="mt-6">
                                    <router-link
                                        to="/administrador/metodos-pago/crear"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nuevo Método
                                    </router-link>
                                </div>
                            </td>
                        </tr>

                        <!-- Métodos de Pago -->
                        <tr 
                            v-for="metodo in metodosPago" 
                            :key="metodo.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                        <img 
                                            v-if="metodo.logo_banco"
                                            :src="getMetodoPagoImageUrl(metodo.logo_banco, metodo.tipo_pago, metodo.imagen_qr)"
                                            :alt="metodo.nombre_banco"
                                            class="h-8 w-8 rounded-full object-cover"
                                        >
                                        <svg v-else class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ metodo.nombre_banco }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            ID: {{ metodo.id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        getTipoBadgeClass(metodo.tipo_pago)
                                    ]"
                                >
                                    {{ metodo.tipo_pago_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="metodo.tipo_pago === 'Transferencia'">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ metodo.nombre_titular }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                            {{ metodo.cuenta_formateada }}
                                        </code>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ metodo.tipo_cuenta }}
                                    </div>
                                </div>
                                <div v-else-if="metodo.tipo_pago === 'QR'">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                        <span class="text-sm text-gray-900 dark:text-white">Código QR</span>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ metodo.nombre_banco }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white line-clamp-2 max-w-xs">
                                    {{ truncateText(metodo.instrucciones, 60) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        metodo.activo 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ]"
                                >
                                    <span class="w-2 h-2 rounded-full mr-1" :class="metodo.activo ? 'bg-green-500' : 'bg-red-500'"></span>
                                    {{ metodo.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Editar -->
                                    <router-link
                                        :to="`/administrador/metodos-pago/${metodo.id}/editar`"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </router-link>

                                    <!-- Cambiar Estado -->
                                    <button
                                        @click="toggleActivo(metodo.id)"
                                        :class="[
                                            'hover:text-yellow-900 dark:hover:text-yellow-300',
                                            metodo.activo 
                                                ? 'text-yellow-600 dark:text-yellow-400' 
                                                : 'text-gray-600 dark:text-gray-400'
                                        ]"
                                        :title="metodo.activo ? 'Desactivar' : 'Activar'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path v-if="metodo.activo" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>

                                    <!-- Eliminar -->
                                    <button
                                        @click="confirmDelete(metodo)"
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

            <!-- Paginación (si es necesario en tu store) -->
            <div v-if="store.pagination && store.pagination.last_page > 1" class="px-6 py-4 border-t dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-400">
                        Mostrando 
                        <span class="font-medium">{{ ((store.pagination.current_page - 1) * store.pagination.per_page) + 1 }}</span>
                        a 
                        <span class="font-medium">{{ Math.min(store.pagination.current_page * store.pagination.per_page, store.pagination.total) }}</span>
                        de 
                        <span class="font-medium">{{ store.pagination.total }}</span>
                        resultados
                    </div>
                    <div class="flex space-x-2">
                        <button
                            @click="cambiarPagina(store.pagination.current_page - 1)"
                            :disabled="store.pagination.current_page === 1"
                            :class="[
                                'px-3 py-1 rounded-md text-sm font-medium',
                                store.pagination.current_page === 1
                                    ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                            ]"
                        >
                            Anterior
                        </button>
                        
                        <div class="flex space-x-1">
                            <button
                                v-for="page in paginasDisponibles"
                                :key="page"
                                @click="cambiarPagina(page)"
                                :class="[
                                    'px-3 py-1 rounded-md text-sm font-medium',
                                    page === store.pagination.current_page
                                        ? 'bg-blue-600 text-white'
                                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                                ]"
                            >
                                {{ page }}
                            </button>
                        </div>
                        
                        <button
                            @click="cambiarPagina(store.pagination.current_page + 1)"
                            :disabled="store.pagination.current_page === store.pagination.last_page"
                            :class="[
                                'px-3 py-1 rounded-md text-sm font-medium',
                                store.pagination.current_page === store.pagination.last_page
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
</template>

<script setup lang="ts">
import { computed, onMounted, watch } from 'vue';
import { useMetodoPagoStore } from '../../stores/metodoPagoStore';
import { useConfirm } from '../../composables/useConfirm';
import type { MetodoPago } from '../../types/metodo-pago';

const store = useMetodoPagoStore();
const confirm = useConfirm();

// Computed
const metodosPago = computed(() => store.metodosPago);
const loading = computed(() => store.loading);
const error = computed(() => store.error);
const filtros = computed(() => store.filtros);
const totales = computed(() => store.totales);

// Métodos
const getTipoBadgeClass = (tipo: string) => {
    const classes: Record<string, string> = {
        Transferencia: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        QR: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        Efectivo: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        Otro: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
    };
    return classes[tipo] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};

const truncateText = (text: string | null, length: number) => {
    if (!text) return '-';
    return text.length > length ? text.substring(0, length) + '...' : text;
};

const onFiltroChange = () => {
    store.fetchMetodosPago();
};

const resetFiltros = () => {
    store.resetFiltros();
    store.fetchMetodosPago();
};

const toggleActivo = async (id: number) => {
    await store.toggleActivo(id);
};

const confirmDelete = (metodo: MetodoPago) => {
    confirm.show({
        title: 'Confirmar Eliminación',
        message: `¿Estás seguro de eliminar el método de pago "${metodo.nombre_banco}"?`,
        type: 'danger',
        onConfirm: () => store.deleteMetodoPago(metodo.id)
    });
};

const getMetodoPagoImageUrl = (
    path: string | null | undefined, 
    tipoPago: string,
    pathqr: string | null | undefined, 
): string => {
    
    // Determinar la ruta base según el tipo de pago
    let basePath = '';
    let cleanPath = '';
    
    switch(tipoPago) {
        case 'QR':
            if (!pathqr) return '/assets/images/placeholder.png';
            basePath = 'metodos_pago/qr';
            cleanPath = pathqr;
            break;
        case 'Transferencia':
            if (!path) return '/assets/images/placeholder.png';
            basePath = 'metodos_pago/logos';
            cleanPath = path;
            break;
        default:
            basePath = 'metodos_pago';
    }
    
    // Limpiar la ruta
    
    // Remover prefijos existentes
    const prefixes = ['/storage/', 'storage/', 'metodos_pago/logos/', 'metodos_pago/qr/', 'metodos_pago/'];
    
    for (const prefix of prefixes) {
        if (cleanPath.startsWith(prefix)) {
            cleanPath = cleanPath.substring(prefix.length);
            break;
        }
    }
    
    // Construir la nueva ruta
    return `/storage/${basePath}/${cleanPath}`;
};

// Lifecycle
onMounted(async () => {
    await store.fetchMetodosPago();
});

// Watch for filter changes
watch(() => filtros.value, () => {
    store.fetchMetodosPago();
}, { deep: true });

// Si necesitas paginación, puedes agregar estas funciones
const cambiarPagina = (page: number) => {
    if (store.pagination && page >= 1 && page <= store.pagination.last_page) {
        store.fetchMetodosPago(page);
    }
};

const paginasDisponibles = computed(() => {
    if (!store.pagination) return [];
    const pages = [];
    const totalPages = store.pagination.last_page;
    const currentPage = store.pagination.current_page;
    
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
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>