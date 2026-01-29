<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <router-link to="/administrador/pedidos" 
                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </router-link>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Pedido: {{ pedido?.codigo_reserva || 'Cargando...' }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Detalles del pedido y gestión de pagos
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <!-- Estado del pedido -->
                    <span v-if="pedido" :class="getEstadoBadgeClass(pedido.estado)" class="badge text-lg px-4 py-2">
                        {{ formatEstado(pedido.estado) }}
                    </span>
                    
                    <!-- Fecha -->
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ formatFechaCompleta(pedido?.created_at) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div v-if="store.loadingDetalle" class="text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Cargando detalles del pedido...</p>
        </div>

        <div v-else-if="store.error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-300">Error</h3>
                    <p class="text-red-700 dark:text-red-400">{{ store.error }}</p>
                </div>
            </div>
            <div class="mt-4">
                <router-link to="/administrador/pedidos" 
                           class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver a la lista
                </router-link>
            </div>
        </div>

        <div v-else-if="pedido" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna izquierda: Información del pedido -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del cliente -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Información del Cliente
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white font-medium">
                                {{ pedido.user?.name || 'No disponible' }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ pedido.user?.email || 'No disponible' }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ pedido.user?.phone || 'No registrado' }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Cliente desde</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ pedido.user?.created_at ? formatDate(pedido.user.created_at) : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Productos del pedido -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Productos del Pedido
                    </h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Producto
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Precio Unitario
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="detalle in pedido.detalles || []" :key="detalle.id">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ detalle.variante?.articulo?.nombre || 'Producto' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <span v-if="detalle.variante?.color">{{ detalle.variante.color.nombre }}</span>
                                            <span v-if="detalle.variante?.color && detalle.variante?.talla"> | </span>
                                            <span v-if="detalle.variante?.talla">{{ detalle.variante.talla.nombre }}</span>
                                            <span v-if="!detalle.variante?.color && !detalle.variante?.talla">
                                                Sin variante
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ detalle.cantidad }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        ${{ formatCurrency(detalle.precio_unitario) }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium">
                                        ${{ formatCurrency(detalle.cantidad * detalle.precio_unitario) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Resumen de totales -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal Productos:</span>
                                <span class="font-medium">${{ formatCurrency(pedido.total) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Costo de Envío:</span>
                                <span class="font-medium">${{ formatCurrency(pedido.costo_envio) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-900 dark:text-white">Total a Pagar:</span>
                                <span class="text-blue-600 dark:text-blue-400">
                                    ${{ formatCurrency(pedido.total + pedido.costo_envio) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de envío -->
                <div v-if="pedido.transporte" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Información de Envío
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Cooperativa</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white font-medium">
                                {{ pedido.transporte.cooperativa }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Ruta</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ pedido.transporte.ruta }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Precio</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                ${{ formatCurrency(pedido.transporte.precio) }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tiempo Estimado</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ pedido.transporte.tiempo_estimado || 'No especificado' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: Gestión de pagos -->
            <div class="space-y-6">
                <!-- Resumen de pagos -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Resumen de Pagos
                    </h2>
                    
                    <div class="space-y-4">
                        <!-- Totales -->
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total a Pagar:</span>
                                <span class="font-bold text-lg">${{ formatCurrency(totalAPagar) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Pagado:</span>
                                <span class="font-bold text-green-600 dark:text-green-400">${{ formatCurrency(totalPagado) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 dark:border-gray-700 pt-2">
                                <span class="text-gray-900 dark:text-white font-bold">Saldo Pendiente:</span>
                                <span :class="saldoPendiente > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'" 
                                      class="font-bold text-lg">
                                    ${{ formatCurrency(saldoPendiente) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Barra de progreso -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Progreso de pago</span>
                                <span class="font-medium">{{ porcentajePagado.toFixed(1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div :class="porcentajePagado >= 100 ? 'bg-green-500' : 'bg-blue-500'" 
                                     class="h-3 rounded-full transition-all duration-300"
                                     :style="{ width: porcentajePagado + '%' }"></div>
                            </div>
                        </div>
                        
                        <!-- Contadores -->
                        <div class="grid grid-cols-3 gap-2 mt-4">
                            <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ totalPagos }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">Total Pagos</div>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ pagosPendientes }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">Pendientes</div>
                            </div>
                            <div class="text-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ pagosRechazados }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">Rechazados</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de pagos -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Historial de Pagos
                        </h2>
                        
                        <!-- Botón para recargar pagos -->
                        <button @click="recargarPagos" 
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                title="Recargar pagos">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Loading de pagos -->
                    <div v-if="store.loadingPagos" class="text-center py-4">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Cargando pagos...</p>
                    </div>
                    
                    <!-- Lista de pagos -->
                    <div v-else class="space-y-4">
                        <!-- Sin pagos -->
                        <div v-if="!pedido.pagos || pedido.pagos.length === 0" 
                             class="text-center py-6 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2">No hay pagos registrados</p>
                        </div>
                        
                        <!-- Con pagos -->
                        <div v-else>
                            <div v-for="pago in pedido.pagos" :key="pago.id" 
                                 class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <!-- Encabezado del pago -->
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="font-bold text-lg">${{ formatCurrency(pago.monto) }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ formatFechaHora(pago.created_at) }}
                                        </div>
                                    </div>
                                    <span :class="getEstadoPagoBadgeClass(pago.estado)" class="badge">
                                        {{ formatEstadoPago(pago.estado) }}
                                    </span>
                                </div>
                                
                                <!-- Comprobante -->
                                <div class="mb-3">
                                    <a :href="getComprobanteUrl(pago.comprobante_ruta)" 
                                       target="_blank"
                                       class="inline-flex items-center justify-center w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver Comprobante
                                    </a>
                                </div>
                                
                                <!-- Observación -->
                                <div v-if="pago.observacion" class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    <span class="font-medium">Observación:</span> {{ pago.observacion }}
                                </div>
                                
                                <!-- Acciones para pagos pendientes -->
                                <div v-if="pago.estado === 'pendiente'" class="flex space-x-2 mt-3">
                                    <button @click="aprobarPago(pago.id)" 
                                            :disabled="procesandoPago"
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span v-if="procesandoPago && pagoAprobando === pago.id" class="flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 mr-2 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Procesando...
                                        </span>
                                        <span v-else class="flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Aprobar
                                        </span>
                                    </button>
                                    
                                    <button @click="abrirModalRechazo(pago)" 
                                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition">
                                        <span class="flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Rechazar
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para rechazar pago -->
        <div v-if="showRechazoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rechazar Pago</h3>
                        <button @click="cerrarModalRechazo" 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                            ¿Estás seguro de rechazar el pago de <strong>${{ formatCurrency(pagoSeleccionado?.monto || 0) }}</strong>?
                        </p>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Motivo del rechazo *
                        </label>
                        <textarea v-model="motivoRechazo" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                  rows="4"
                                  placeholder="Describe el motivo del rechazo..."
                                  required></textarea>
                        <p v-if="motivoRechazoError" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ motivoRechazoError }}
                        </p>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button @click="cerrarModalRechazo" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancelar
                        </button>
                        <button @click="rechazarPagoConfirmado" 
                                :disabled="procesandoRechazo || !motivoRechazo.trim()"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span v-if="procesandoRechazo" class="flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Procesando...
                            </span>
                            <span v-else>Confirmar Rechazo</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { usePedidoStore } from '../../stores/pedidoStore';
import type { Pedido, PedidoPago } from '../../types/pedido';

const route = useRoute();
const router = useRouter();
const store = usePedidoStore();

// Estado local
const showRechazoModal = ref(false);
const pagoSeleccionado = ref<PedidoPago | null>(null);
const motivoRechazo = ref('');
const motivoRechazoError = ref('');
const procesandoPago = ref(false);
const pagoAprobando = ref<number | null>(null);
const procesandoRechazo = ref(false);

// Computed
const pedido = computed(() => store.pedidoDetalle);

const totalAPagar = computed(() => {
    if (!pedido.value) return 0;
    return parseFloat(pedido.value.total.toString()) + parseFloat(pedido.value.costo_envio.toString());
});

const totalPagado = computed(() => {
    if (!pedido.value?.pagos) return 0;
    return pedido.value.pagos
        .filter(p => p.estado === 'aprobado')
        .reduce((sum, pago) => sum + parseFloat(pago.monto.toString()), 0);
});

const saldoPendiente = computed(() => {
    return totalAPagar.value - totalPagado.value;
});

const porcentajePagado = computed(() => {
    const total = totalAPagar.value; 
    const pagado = totalPagado.value;
    
    if (total <= 0) return 0;
    
    return Math.min((pagado / total) * 100, 100);
});

const getProgresoClase = computed(() => {
    const porcentaje = porcentajePagado.value;
    
    if (porcentaje >= 100) return 'bg-green-500';
    if (porcentaje >= 50) return 'bg-yellow-500';
    return 'bg-blue-500';
});

const totalPagos = computed(() => {
    return pedido.value?.pagos?.length || 0;
});

const pagosPendientes = computed(() => {
    return pedido.value?.pagos?.filter(p => p.estado === 'pendiente').length || 0;
});

const pagosRechazados = computed(() => {
    return pedido.value?.pagos?.filter(p => p.estado === 'rechazado').length || 0;
});

// Métodos
const formatCurrency = (amount: number) => {
    return parseFloat(amount.toString()).toFixed(2);
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES');
};

const formatTime = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
};

const formatFechaHora = (dateString: string) => {
    const date = new Date(dateString);
    return `${formatDate(dateString)} ${formatTime(dateString)}`;
};

const formatFechaCompleta = (dateString?: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatEstado = (estado: string) => {
    const estadosMap: Record<string, string> = {
        'pendiente': 'Pendiente',
        'abonado': 'Abonado',
        'completado': 'Completado',
        'rechazado': 'Rechazado',
        'cancelado': 'Cancelado'
    };
    return estadosMap[estado] || estado;
};

const formatEstadoPago = (estado: string) => {
    const estadosMap: Record<string, string> = {
        'pendiente': 'Pendiente',
        'aprobado': 'Aprobado',
        'rechazado': 'Rechazado'
    };
    return estadosMap[estado] || estado;
};

const getEstadoBadgeClass = (estado: string) => {
    const classes: Record<string, string> = {
        'pendiente': 'badge-warning',
        'abonado': 'badge-info',
        'completado': 'badge-success',
        'rechazado': 'badge-danger',
        'cancelado': 'badge-danger'
    };
    return classes[estado] || 'badge-secondary';
};

const getEstadoPagoBadgeClass = (estado: string) => {
    const classes: Record<string, string> = {
        'pendiente': 'badge-warning',
        'aprobado': 'badge-success',
        'rechazado': 'badge-danger'
    };
    return classes[estado] || 'badge-secondary';
};

const getComprobanteUrl = (ruta: string) => {
    // Obtener la URL base del sitio
    const baseUrl = window.location.origin;
    
    // Si la ruta ya es una URL completa, retórnala
    if (ruta.startsWith('http://') || ruta.startsWith('https://')) {
        return ruta;
    }
    
    // Limpiar la ruta
    let cleanPath = ruta;
    
    // Remover "storage/" o "/storage/" si existe al inicio
    if (cleanPath.startsWith('/storage/')) {
        cleanPath = cleanPath.substring('/storage/'.length);
    } else if (cleanPath.startsWith('storage/')) {
        cleanPath = cleanPath.substring('storage/'.length);
    }
    
    // Construir la URL completa con la ruta base
    return `${baseUrl}/tienda/public/storage/${cleanPath}`;
};

const recargarPagos = async () => {
    if (pedido.value?.id) {
        await store.fetchPagosPedido(pedido.value.id);
    }
};

// En el método aprobarPago del componente Show.vue
const aprobarPago = async (pagoId: number) => {
    if (!pedido.value) return;
    
    // Validación: Verificar que el pedido no esté completado
    if (pedido.value.estado === 'completado') {
        alert('Este pedido ya está completado. No se pueden aprobar más pagos.');
        return;
    }
    
    if (!confirm('¿Estás seguro de aprobar este pago?')) {
        return;
    }

    procesandoPago.value = true;
    pagoAprobando.value = pagoId;

    try {
        await store.aprobarPago(pagoId, 'Pago aprobado por administrador');
        
        // Mostrar mensaje informativo
        toast.success('Pago aprobado correctamente');
        
        // Recargar datos actualizados
        await store.fetchPedidoDetalle(pedido.value.id);
        
        // Mostrar resumen
        const totalPagadoDespues = totalPagado.value;
        const totalAPagarDespues = totalAPagar.value;
        
        if (totalPagadoDespues >= totalAPagarDespues) {
            toast.info('¡Pedido completado! Todos los pagos han sido cubiertos.');
        } else if (totalPagadoDespues > 0) {
            toast.info('Pedido marcado como abonado. Pendiente por pagar: $' + 
                formatCurrency(totalAPagarDespues - totalPagadoDespues));
        }
        
    } catch (error: any) {
        console.error('Error al aprobar pago:', error);
        
        if (error.response?.data?.message) {
            toast.error(error.response.data.message);
        } else {
            toast.error('Error al aprobar el pago');
        }
    } finally {
        procesandoPago.value = false;
        pagoAprobando.value = null;
    }
};

const abrirModalRechazo = (pago: PedidoPago) => {
    pagoSeleccionado.value = pago;
    motivoRechazo.value = '';
    motivoRechazoError.value = '';
    showRechazoModal.value = true;
};

const cerrarModalRechazo = () => {
    showRechazoModal.value = false;
    pagoSeleccionado.value = null;
    motivoRechazo.value = '';
    motivoRechazoError.value = '';
};

const rechazarPagoConfirmado = async () => {
    if (!pagoSeleccionado.value) return;
    
    // Validar motivo
    if (!motivoRechazo.value.trim()) {
        motivoRechazoError.value = 'Por favor, ingresa un motivo para el rechazo';
        return;
    }
    
    if (motivoRechazo.value.trim().length < 5) {
        motivoRechazoError.value = 'El motivo debe tener al menos 5 caracteres';
        return;
    }

    procesandoRechazo.value = true;

    try {
        await store.rechazarPago(pagoSeleccionado.value.id, motivoRechazo.value.trim());
        
        // Recargar los datos del pedido
        if (pedido.value?.id) {
            await store.fetchPedidoDetalle(pedido.value.id);
        }
        
        cerrarModalRechazo();
    } catch (error) {
        console.error('Error al rechazar pago:', error);
    } finally {
        procesandoRechazo.value = false;
    }
};

// Lifecycle
onMounted(async () => {
    const pedidoId = parseInt(route.params.id as string);
    
    if (isNaN(pedidoId)) {
        router.push('/administrador/pedidos');
        return;
    }
    
    try {
        await store.fetchPedidoDetalle(pedidoId);
    } catch (error) {
        console.error('Error al cargar detalle del pedido:', error);
        router.push('/administrador/pedidos');
    }
});

// Cleanup
import { onUnmounted } from 'vue';
onUnmounted(() => {
    store.clearPedidoDetalle();
});
</script>

<style scoped>
.badge {
    @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium;
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
</style>