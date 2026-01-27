<template>
    <div>
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h5 class="text-lg font-semibold dark:text-white-light">Pedido: {{ pedido.codigo_reserva }}</h5>
                <div class="flex items-center space-x-2 mt-1">
                    <span :class="getEstadoBadgeClass(pedido.estado)" class="text-xs">
                        {{ pedido.estado | capitalize }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ formatDate(pedido.created_at) }} {{ formatTime(pedido.created_at) }}
                    </span>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <router-link to="/administrador/pedidos" class="btn btn-outline-primary">
                    <icon-arrow-left class="w-4 h-4 mr-2" />
                    Volver
                </router-link>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Columna izquierda: Información del pedido -->
            <div class="lg:col-span-2">
                <!-- Información del cliente -->
                <div class="panel mb-5">
                    <h6 class="font-semibold mb-3">Información del Cliente</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-500">Nombre</label>
                            <p class="font-medium">{{ pedido.user?.name || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Email</label>
                            <p class="font-medium">{{ pedido.user?.email || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Teléfono</label>
                            <p class="font-medium">{{ pedido.user?.phone || 'No registrado' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Registrado</label>
                            <p class="font-medium">{{ pedido.user ? formatDate(pedido.user.created_at) : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Productos del pedido -->
                <div class="panel mb-5">
                    <h6 class="font-semibold mb-3">Productos del Pedido</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio Unitario</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="detalle in pedido.detalles" :key="detalle.id">
                                    <td>
                                        <div class="font-medium">{{ detalle.variante?.articulo?.nombre }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ detalle.variante?.color?.nombre || 'N/A' }} | 
                                            {{ detalle.variante?.talla?.nombre || 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="text-center">{{ detalle.cantidad }}</td>
                                    <td class="text-end">${{ formatCurrency(detalle.precio_unitario) }}</td>
                                    <td class="text-end font-semibold">
                                        ${{ formatCurrency(detalle.cantidad * detalle.precio_unitario) }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="border-t">
                                <tr>
                                    <td colspan="3" class="text-end font-semibold">Subtotal Productos:</td>
                                    <td class="text-end font-semibold">${{ formatCurrency(pedido.total) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end">Costo de Envío:</td>
                                    <td class="text-end">${{ formatCurrency(pedido.costo_envio) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end text-lg font-bold">TOTAL:</td>
                                    <td class="text-end text-lg font-bold text-primary">
                                        ${{ formatCurrency(pedido.total + pedido.costo_envio) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Información de envío -->
                <div v-if="pedido.transporte" class="panel">
                    <h6 class="font-semibold mb-3">Información de Envío</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-500">Cooperativa</label>
                            <p class="font-medium">{{ pedido.transporte.cooperativa }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Ruta</label>
                            <p class="font-medium">{{ pedido.transporte.ruta }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Precio</label>
                            <p class="font-medium">${{ formatCurrency(pedido.transporte.precio) }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Tiempo Estimado</label>
                            <p class="font-medium">{{ pedido.transporte.tiempo_estimado || 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: Gestión de pagos -->
            <div class="lg:col-span-1">
                <div class="panel sticky top-5">
                    <h6 class="font-semibold text-lg mb-5" id="pagos">Gestión de Pagos</h6>
                    
                    <!-- Resumen de pagos -->
                    <div class="space-y-4 mb-5">
                        <div class="border border-[#ebedf2] rounded dark:border-[#191e3a] p-4">
                            <h6 class="font-semibold mb-2">Resumen de Pagos</h6>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Total a Pagar:</span>
                                    <span class="font-semibold">${{ formatCurrency(totalAPagar) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Pagado:</span>
                                    <span class="font-semibold text-success">${{ formatCurrency(totalPagado) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Saldo Pendiente:</span>
                                    <span :class="saldoPendiente > 0 ? 'text-danger' : 'text-success'" 
                                          class="font-semibold">
                                        ${{ formatCurrency(saldoPendiente) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div :class="porcentajePagado >= 100 ? 'bg-success' : 'bg-primary'" 
                                         class="h-2 rounded-full"
                                         :style="{ width: porcentajePagado + '%' }"></div>
                                </div>
                                <div class="text-xs text-gray-500 text-center mt-1">
                                    {{ porcentajePagado.toFixed(1) }}% pagado
                                </div>
                            </div>
                        </div>

                        <!-- Contadores -->
                        <div class="grid grid-cols-3 gap-2">
                            <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded">
                                <div class="text-2xl font-bold text-primary">{{ totalPagos }}</div>
                                <div class="text-xs">Total Pagos</div>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded">
                                <div class="text-2xl font-bold text-warning">{{ pagosPendientes }}</div>
                                <div class="text-xs">Pendientes</div>
                            </div>
                            <div class="text-center p-3 bg-red-50 dark:bg-red-900/20 rounded">
                                <div class="text-2xl font-bold text-danger">{{ pagosRechazados }}</div>
                                <div class="text-xs">Rechazados</div>
                            </div>
                        </div>
                    </div>

                    <!-- Historial de pagos -->
                    <div class="space-y-4">
                        <h6 class="font-semibold">Historial de Pagos</h6>
                        
                        <div v-if="loadingPagos" class="text-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                        </div>
                        
                        <div v-else-if="pedido.pagos?.length === 0" class="text-center py-4 text-gray-500">
                            No hay pagos registrados para este pedido.
                        </div>
                        
                        <div v-else class="space-y-3">
                            <div v-for="pago in pedido.pagos" :key="pago.id" 
                                 class="border border-[#ebedf2] rounded dark:border-[#191e3a] p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="font-semibold">${{ formatCurrency(pago.monto) }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ formatDate(pago.created_at) }} {{ formatTime(pago.created_at) }}
                                        </div>
                                    </div>
                                    <span :class="getPagoEstadoBadgeClass(pago.estado)" class="badge">
                                        {{ pago.estado | capitalize }}
                                    </span>
                                </div>
                                
                                <!-- Comprobante -->
                                <div class="mb-3">
                                    <a :href="`/storage/${pago.comprobante_ruta}`" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary w-full">
                                        <icon-eye class="w-4 h-4 mr-2" />
                                        Ver Comprobante
                                    </a>
                                </div>
                                
                                <!-- Observaciones -->
                                <div v-if="pago.observacion" class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Observación:</strong> {{ pago.observacion }}
                                </div>
                                
                                <!-- Acciones para pagos pendientes -->
                                <div v-if="pago.estado === 'pendiente'" class="flex space-x-2 mt-3">
                                    <button @click="aprobarPago(pago.id)" 
                                            class="btn btn-success btn-sm flex-1"
                                            :disabled="procesandoPago">
                                        <icon-check class="w-4 h-4 mr-1" />
                                        Aprobar
                                    </button>
                                    <button @click="abrirModalRechazo(pago.id)" 
                                            class="btn btn-danger btn-sm flex-1">
                                        <icon-x class="w-4 h-4 mr-1" />
                                        Rechazar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para rechazar pago -->
        <div v-if="showRechazoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-[#1b2e4b] rounded-lg w-full max-w-md p-6">
                <h3 class="text-lg font-semibold mb-4">Rechazar Pago</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Motivo del rechazo</label>
                    <textarea v-model="motivoRechazo" 
                              class="form-textarea min-h-[100px] w-full" 
                              placeholder="Describe el motivo del rechazo..."
                              required></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button @click="cerrarModalRechazo" class="btn btn-outline-danger">
                        Cancelar
                    </button>
                    <button @click="rechazarPago" class="btn btn-danger" :disabled="!motivoRechazo.trim()">
                        {{ procesandoRechazo ? 'Procesando...' : 'Confirmar Rechazo' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import IconArrowLeft from '../../components/icons/IconArrowLeft.vue';
import IconEye from '../../components/icons/IconEye.vue';
import IconCheck from '../../components/icons/IconCheck.vue';
import IconX from '../../components/icons/IconX.vue';

export default {
    components: {
        IconArrowLeft,
        IconEye,
        IconCheck,
        IconX
    },
    props: {
        id: {
            type: [String, Number],
            required: true
        }
    },
    setup(props) {
        const route = useRoute();
        const router = useRouter();
        
        const pedido = ref({});
        const loading = ref(true);
        const loadingPagos = ref(false);
        const showRechazoModal = ref(false);
        const motivoRechazo = ref('');
        const pagoSeleccionado = ref(null);
        const procesandoPago = ref(false);
        const procesandoRechazo = ref(false);

        // Computed
        const totalAPagar = computed(() => {
            return parseFloat(pedido.value.total || 0) + parseFloat(pedido.value.costo_envio || 0);
        });

        const totalPagado = computed(() => {
            if (!pedido.value.pagos) return 0;
            return pedido.value.pagos
                .filter(p => p.estado === 'aprobado')
                .reduce((sum, pago) => sum + parseFloat(pago.monto), 0);
        });

        const saldoPendiente = computed(() => {
            return totalAPagar.value - totalPagado.value;
        });

        const porcentajePagado = computed(() => {
            return totalAPagar.value > 0 
                ? Math.min((totalPagado.value / totalAPagar.value) * 100, 100)
                : 0;
        });

        const totalPagos = computed(() => {
            return pedido.value.pagos?.length || 0;
        });

        const pagosPendientes = computed(() => {
            return pedido.value.pagos?.filter(p => p.estado === 'pendiente').length || 0;
        });

        const pagosRechazados = computed(() => {
            return pedido.value.pagos?.filter(p => p.estado === 'rechazado').length || 0;
        });

        // Métodos
        const fetchPedido = async () => {
            loading.value = true;
            try {
                const response = await axios.get(`/api/admin/pedidos/${props.id}`);
                pedido.value = response.data;
            } catch (error) {
                console.error('Error fetching pedido:', error);
                router.push('/administrador/pedidos');
            } finally {
                loading.value = false;
            }
        };

        const fetchPagos = async () => {
            loadingPagos.value = true;
            try {
                const response = await axios.get(`/api/admin/pedidos/${props.id}/pagos`);
                pedido.value.pagos = response.data;
            } catch (error) {
                console.error('Error fetching pagos:', error);
            } finally {
                loadingPagos.value = false;
            }
        };

        const aprobarPago = async (pagoId) => {
            if (!confirm('¿Estás seguro de aprobar este pago?')) return;
            
            procesandoPago.value = true;
            try {
                await axios.post(`/api/admin/pagos/${pagoId}/aprobar`);
                await fetchPagos();
                await fetchPedido(); // Para actualizar estado del pedido
            } catch (error) {
                console.error('Error aprobando pago:', error);
                alert('Error al aprobar el pago');
            } finally {
                procesandoPago.value = false;
            }
        };

        const abrirModalRechazo = (pagoId) => {
            pagoSeleccionado.value = pagoId;
            motivoRechazo.value = '';
            showRechazoModal.value = true;
        };

        const cerrarModalRechazo = () => {
            showRechazoModal.value = false;
            pagoSeleccionado.value = null;
            motivoRechazo.value = '';
        };

        const rechazarPago = async () => {
            if (!motivoRechazo.value.trim()) {
                alert('Por favor, ingresa un motivo para el rechazo');
                return;
            }

            procesandoRechazo.value = true;
            try {
                await axios.post(`/api/admin/pagos/${pagoSeleccionado.value}/rechazar`, {
                    motivo: motivoRechazo.value
                });
                await fetchPagos();
                cerrarModalRechazo();
            } catch (error) {
                console.error('Error rechazando pago:', error);
                alert('Error al rechazar el pago');
            } finally {
                procesandoRechazo.value = false;
            }
        };

        // Métodos de utilidad
        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES');
        };

        const formatTime = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
        };

        const formatCurrency = (amount) => {
            return parseFloat(amount || 0).toFixed(2);
        };

        const getEstadoBadgeClass = (estado) => {
            const classes = {
                'pendiente': 'badge-outline-warning',
                'parcial': 'badge-outline-info',
                'pagado': 'badge-outline-success',
                'en_proceso': 'badge-outline-primary',
                'completado': 'badge-outline-success',
                'cancelado': 'badge-outline-danger'
            };
            return `badge ${classes[estado] || 'badge-outline-secondary'}`;
        };

        const getPagoEstadoBadgeClass = (estado) => {
            const classes = {
                'pendiente': 'badge-outline-warning',
                'aprobado': 'badge-outline-success',
                'rechazado': 'badge-outline-danger'
            };
            return classes[estado] || 'badge-outline-secondary';
        };

        // Ciclo de vida
        onMounted(() => {
            fetchPedido();
            fetchPagos();
        });

        return {
            pedido,
            loading,
            loadingPagos,
            showRechazoModal,
            motivoRechazo,
            procesandoPago,
            procesandoRechazo,
            totalAPagar,
            totalPagado,
            saldoPendiente,
            porcentajePagado,
            totalPagos,
            pagosPendientes,
            pagosRechazados,
            fetchPedido,
            fetchPagos,
            aprobarPago,
            abrirModalRechazo,
            cerrarModalRechazo,
            rechazarPago,
            formatDate,
            formatTime,
            formatCurrency,
            getEstadoBadgeClass,
            getPagoEstadoBadgeClass
        };
    },
    filters: {
        capitalize: function(value) {
            if (!value) return '';
            value = value.toString();
            return value.charAt(0).toUpperCase() + value.slice(1);
        }
    }
};
</script>