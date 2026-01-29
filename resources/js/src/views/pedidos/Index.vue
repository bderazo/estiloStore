<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                <svg class="w-6 h-6 inline-block mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Gestión de Pedidos
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Gestiona todos los pedidos y aprobación de pagos
            </p>
        </div>

        <!-- Dashboard de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Pedidos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pedidos</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ store.totalPedidos }}</p>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pagos Pendientes</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ store.pagosPendientesCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Pagados -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pedidos Pagados</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ estadisticas?.pedidos_pagados || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Recaudado -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Recaudado</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">${{ formatCurrency(estadisticas?.total_recaudado || 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de Acciones y Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-4 border-b dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Botones de Acción -->
                    <div class="flex flex-wrap gap-2">
                        <router-link 
                            to="/administrador/pedidos/estadisticas"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Ver Estadísticas
                        </router-link>
                        <router-link 
                            to="/administrador/pedidos/pagos-pendientes"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pagos Pendientes
                            <span v-if="store.pagosPendientesCount > 0" class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                                {{ store.pagosPendientesCount }}
                            </span>
                        </router-link>
                    </div>

                    <!-- Filtros -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Filtro por Fecha -->
                        <div class="flex gap-2">
                            <div class="relative">
                                <input
                                    v-model="store.filtros.fecha_inicio"
                                    @change="onFiltroChange"
                                    type="date"
                                    class="pl-4 pr-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white w-full"
                                    placeholder="Fecha inicio"
                                >
                            </div>
                            <div class="relative">
                                <input
                                    v-model="store.filtros.fecha_fin"
                                    @change="onFiltroChange"
                                    type="date"
                                    class="pl-4 pr-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white w-full"
                                    placeholder="Fecha fin"
                                >
                            </div>
                        </div>

                        <!-- Filtro por Estado -->
                        <select
                            v-model="store.filtros.estado"
                            @change="onFiltroChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Todos los estados</option>
                            <option v-for="estado in store.estados" :key="estado" :value="estado">
                                {{ formatEstado(estado) }}
                            </option>
                        </select>

                        <!-- Búsqueda por código -->
                        <div class="relative">
                            <input
                                v-model="store.filtros.codigo"
                                @input="onFiltroChange"
                                type="text"
                                placeholder="Buscar por código..."
                                class="pl-10 pr-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white w-full"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Items por página -->
                        <select
                            v-model="store.filtros.per_page"
                            @change="onFiltroChange"
                            class="border dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="10">10 por página</option>
                            <option value="20">20 por página</option>
                            <option value="30">30 por página</option>
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

            <!-- Tabla de Pedidos -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Código
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Envío
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Pagos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Loading State -->
                        <tr v-if="store.loading">
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">Cargando pedidos...</p>
                            </td>
                        </tr>

                        <!-- Error State -->
                        <tr v-else-if="store.error">
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">Error al cargar</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ store.error }}
                                </p>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-else-if="store.pedidos.length === 0">
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay pedidos</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    No se encontraron pedidos con los filtros aplicados.
                                </p>
                            </td>
                        </tr>

                        <!-- Pedidos -->
                        <tr 
                            v-for="pedido in store.pedidos" 
                            :key="pedido.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white font-mono">
                                    {{ pedido.codigo_reserva }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    ID: {{ pedido.id }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ pedido.user?.name || 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ pedido.user?.email || '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ formatDate(pedido.created_at) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ formatTime(pedido.created_at) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${{ formatCurrency(pedido.total) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    + envío: ${{ formatCurrency(pedido.costo_envio) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="pedido.transporte" class="text-sm text-gray-900 dark:text-white">
                                    {{ pedido.transporte.cooperativa }}
                                </div>
                                <div v-else class="badge badge-warning text-xs">
                                    Sin asignar
                                </div>
                                <div v-if="pedido.transporte" class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ pedido.transporte.ruta }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getEstadoBadgeClass(pedido.estado)" class="badge">
                                    {{ formatEstado(pedido.estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm">
                                        ${{ formatCurrency(totalPagado(pedido)) }} / 
                                        ${{ formatCurrency(pedido.total + pedido.costo_envio) }}
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div :class="getProgressBarClass(pedido)" 
                                             class="h-2 rounded-full"
                                             :style="{ width: getPorcentajePago(pedido) + '%' }"></div>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ countPagosPendientes(pedido) }} pendiente(s)
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Ver Detalles -->
                                    <router-link
                                        :to="`/administrador/pedidos/${pedido.id}`"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        title="Ver detalles"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </router-link>

                                    <!-- Ver Pagos Pendientes -->
                                    <router-link
                                        v-if="countPagosPendientes(pedido) > 0"
                                        :to="`/administrador/pedidos/${pedido.id}#pagos`"
                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                        title="Ver pagos pendientes"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div v-if="store.pagination && store.pagination.last_page > 1" class="px-6 py-4 border-t dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-400">
                        Mostrando 
                        <span class="font-medium">{{ store.pagination.from || 0 }}</span>
                        a 
                        <span class="font-medium">{{ store.pagination.to || 0 }}</span>
                        de 
                        <span class="font-medium">{{ store.pagination.total || 0 }}</span>
                        resultados
                    </div>
                    <div class="flex space-x-2">
                        <button
                            @click="store.cambiarPagina(store.pagination.current_page - 1)"
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
                                v-for="page in store.paginasDisponibles"
                                :key="page"
                                @click="store.cambiarPagina(page)"
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
                            @click="store.cambiarPagina(store.pagination.current_page + 1)"
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
import { usePedidoStore } from '../../stores/pedidoStore';
import type { Pedido } from '../../types/pedido';

const store = usePedidoStore();

// Computed
const estadisticas = computed(() => store.estadisticas);

// Métodos
const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES');
};

const formatTime = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
};

const formatCurrency = (amount: number) => {
    return parseFloat(amount.toString()).toFixed(2);
};

const formatEstado = (estado: string) => {
    const estadosMap: Record<string, string> = {
        'pendiente': 'Pendiente',
        'en_proceso': 'En Proceso',
        'pagado': 'Pagado',
        'parcial': 'Parcial',
        'completado': 'Completado',
        'cancelado': 'Cancelado'
    };
    return estadosMap[estado] || estado;
};

const getEstadoBadgeClass = (estado: string) => {
    const classes: Record<string, string> = {
        'pendiente': 'badge-warning',
        'en_proceso': 'badge-info',
        'pagado': 'badge-success',
        'parcial': 'badge-primary',
        'completado': 'badge-success',
        'cancelado': 'badge-danger'
    };
    return classes[estado] || 'badge-secondary';
};

const totalPagado = (pedido: Pedido) => {
    if (!pedido.pagos) return 0;
    return pedido.pagos
        .filter(p => p.estado === 'aprobado')
        .reduce((sum, pago) => sum + parseFloat(pago.monto.toString()), 0);
};

const getPorcentajePago = (pedido: Pedido) => {
    const totalAPagar = parseFloat(pedido.total.toString()) + parseFloat(pedido.costo_envio.toString());
    const pagado = totalPagado(pedido);
    return totalAPagar > 0 ? Math.min((pagado / totalAPagar) * 100, 100) : 0;
};

const getProgressBarClass = (pedido: Pedido) => {
    const porcentaje = getPorcentajePago(pedido);
    return porcentaje >= 100 ? 'bg-success' : 'bg-primary';
};

const countPagosPendientes = (pedido: Pedido) => {
    if (!pedido.pagos) return 0;
    return pedido.pagos.filter(p => p.estado === 'pendiente').length;
};

const onFiltroChange = () => {
    store.fetchPedidos();
};

const resetFiltros = () => {
    store.resetFiltros();
    store.fetchPedidos();
};

// Lifecycle
onMounted(async () => {
    await store.fetchPedidos();
    await store.fetchEstados();
    await store.fetchPagosPendientesCount();
    await store.fetchEstadisticas();
});

// Watch for filter changes
watch(() => store.filtros, () => {
    store.fetchPedidos();
}, { deep: true });
</script>

<style scoped>
.badge {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
}

.badge-warning {
    @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200;
}

.badge-info {
    @apply bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200;
}

.badge-success {
    @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200;
}

.badge-primary {
    @apply bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200;
}

.badge-danger {
    @apply bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200;
}

.badge-secondary {
    @apply bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200;
}

.bg-success {
    @apply bg-green-500;
}

.bg-primary {
    @apply bg-blue-500;
}
</style>