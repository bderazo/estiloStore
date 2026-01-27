<template>
    <div>
        <!-- Header -->
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Gestión de Pedidos</h5>
            <div class="flex items-center space-x-2">
                <router-link to="/administrador/pedidos/estadisticas" class="btn btn-outline-primary">
                    <icon-bar-chart class="w-4 h-4 mr-2" />
                    Estadísticas
                </router-link>
                <router-link to="/administrador/pedidos/pagos-pendientes" class="btn btn-outline-warning">
                    <icon-alert-circle class="w-4 h-4 mr-2" />
                    Pagos Pendientes
                    <span v-if="pagosPendientesCount > 0" class="ml-2 badge bg-danger">
                        {{ pagosPendientesCount }}
                    </span>
                </router-link>
            </div>
        </div>

        <!-- Filtros -->
        <div class="panel mb-5">
            <div class="mb-4">
                <h6 class="text-base font-semibold mb-4">Filtros de Búsqueda</h6>
                <form @submit.prevent="fetchPedidos" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input id="fecha_inicio" v-model="filters.fecha_inicio" type="date" class="form-input" />
                    </div>
                    <div>
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input id="fecha_fin" v-model="filters.fecha_fin" type="date" class="form-input" />
                    </div>
                    <div>
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" v-model="filters.estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option v-for="estado in estados" :key="estado" :value="estado">
                                {{ estado | capitalize }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="codigo" class="form-label">Código Reserva</label>
                        <input id="codigo" v-model="filters.codigo" type="text" 
                               placeholder="Buscar por código" class="form-input" />
                    </div>
                    <div class="md:col-span-4 flex space-x-2">
                        <button type="submit" class="btn btn-primary" :disabled="loading">
                            <icon-search class="w-4 h-4 mr-2" />
                            {{ loading ? 'Buscando...' : 'Filtrar' }}
                        </button>
                        <button type="button" @click="resetFilters" class="btn btn-outline-danger">
                            Limpiar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Pedidos -->
        <div class="panel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr class="!text-center">
                            <th class="!text-start">Código</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Envío</th>
                            <th>Estado</th>
                            <th>Pagos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody v-if="loading">
                        <tr>
                            <td colspan="8" class="text-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                                <p class="mt-2 text-gray-500">Cargando pedidos...</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else-if="pedidos.length === 0">
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                No se encontraron pedidos con los filtros aplicados.
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr v-for="pedido in pedidos" :key="pedido.id" class="!text-center">
                            <td class="!text-start">
                                <div class="font-semibold">{{ pedido.codigo_reserva }}</div>
                                <div class="text-xs text-gray-500">ID: {{ pedido.id }}</div>
                            </td>
                            <td>
                                <div>{{ pedido.user.name || 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ pedido.user.email || '' }}</div>
                            </td>
                            <td>
                                <div>{{ formatDate(pedido.created_at) }}</div>
                                <div class="text-xs text-gray-500">{{ formatTime(pedido.created_at) }}</div>
                            </td>
                            <td>
                                <div class="font-semibold">${{ formatCurrency(pedido.total) }}</div>
                                <div class="text-xs">+ envío: ${{ formatCurrency(pedido.costo_envio) }}</div>
                            </td>
                            <td>
                                <div v-if="pedido.transporte" class="text-sm">
                                    {{ pedido.transporte.cooperativa }}
                                </div>
                                <div v-else class="badge badge-outline-warning text-xs">Sin asignar</div>
                                <div v-if="pedido.transporte" class="text-xs text-gray-500">
                                    {{ pedido.transporte.ruta }}
                                </div>
                            </td>
                            <td>
                                <span :class="getEstadoBadgeClass(pedido.estado)">
                                    {{ pedido.estado | capitalize }}
                                </span>
                            </td>
                            <td>
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
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <router-link :to="`/administrador/pedidos/${pedido.id}`" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Ver detalles">
                                        <icon-eye class="w-4 h-4" />
                                    </router-link>
                                    <button v-if="countPagosPendientes(pedido) > 0" 
                                            @click="scrollToPagos(pedido.id)"
                                            class="btn btn-sm btn-outline-warning" 
                                            title="Ver pagos pendientes">
                                        <icon-dollar-sign class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div v-if="pedidos.length > 0 && pedidosPaginator" class="mt-4">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Mostrando {{ pedidosPaginator.from }} al {{ pedidosPaginator.to }} 
                        de {{ pedidosPaginator.total }} pedidos
                    </div>
                    <div class="flex space-x-1">
                        <button @click="changePage(pedidosPaginator.current_page - 1)" 
                                :disabled="!pedidosPaginator.prev_page_url"
                                class="btn btn-sm btn-outline-primary">
                            <icon-chevron-left class="w-4 h-4" />
                        </button>
                        <button v-for="page in pedidosPaginator.links" 
                                :key="page.label"
                                @click="page.url ? changePage(page.label) : null"
                                :class="[
                                    'btn btn-sm',
                                    page.active ? 'btn-primary' : 'btn-outline-primary',
                                    !page.url ? 'opacity-50 cursor-not-allowed' : ''
                                ]"
                                v-html="page.label">
                        </button>
                        <button @click="changePage(pedidosPaginator.current_page + 1)" 
                                :disabled="!pedidosPaginator.next_page_url"
                                class="btn btn-sm btn-outline-primary">
                            <icon-chevron-right class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import IconSearch from '../../components/icons/IconSearch.vue';
import IconEye from '../../components/icons/IconEye.vue';
import IconDollarSign from '../../components/icons/IconDollarSign.vue';
import IconBarChart from '../../components/icons/IconBarChart.vue';
import IconAlertCircle from '../../components/icons/IconAlertCircle.vue';
import IconChevronLeft from '../../components/icons/IconChevronLeft.vue';
import IconChevronRight from '../../components/icons/IconChevronRight.vue';

export default {
    components: {
        IconSearch,
        IconEye,
        IconDollarSign,
        IconBarChart,
        IconAlertCircle,
        IconChevronLeft,
        IconChevronRight
    },
    setup() {
        const router = useRouter();
        const loading = ref(false);
        const pedidos = ref([]);
        const pedidosPaginator = ref(null);
        const estados = ref([]);
        const pagosPendientesCount = ref(0);
        
        const filters = ref({
            fecha_inicio: '',
            fecha_fin: '',
            estado: '',
            codigo: '',
            page: 1
        });

        // Métodos
        const fetchPedidos = async () => {
            loading.value = true;
            try {
                const params = new URLSearchParams();
                Object.keys(filters.value).forEach(key => {
                    if (filters.value[key]) {
                        params.append(key, filters.value[key]);
                    }
                });

                const response = await axios.get(`/api/admin/pedidos?${params.toString()}`);
                pedidos.value = response.data.data;
                pedidosPaginator.value = {
                    current_page: response.data.current_page,
                    from: response.data.from,
                    to: response.data.to,
                    total: response.data.total,
                    per_page: response.data.per_page,
                    last_page: response.data.last_page,
                    prev_page_url: response.data.prev_page_url,
                    next_page_url: response.data.next_page_url,
                    links: response.data.links
                };
            } catch (error) {
                console.error('Error fetching pedidos:', error);
            } finally {
                loading.value = false;
            }
        };

        const fetchEstados = async () => {
            try {
                const response = await axios.get('/api/admin/pedidos/estados');
                estados.value = response.data;
            } catch (error) {
                console.error('Error fetching estados:', error);
            }
        };

        const fetchPagosPendientesCount = async () => {
            try {
                const response = await axios.get('/api/admin/pagos/pendientes/count');
                pagosPendientesCount.value = response.data.count;
            } catch (error) {
                console.error('Error fetching pagos pendientes count:', error);
            }
        };

        const resetFilters = () => {
            filters.value = {
                fecha_inicio: '',
                fecha_fin: '',
                estado: '',
                codigo: '',
                page: 1
            };
            fetchPedidos();
        };

        const changePage = (page) => {
            if (page > 0 && page <= pedidosPaginator.value.last_page) {
                filters.value.page = page;
                fetchPedidos();
            }
        };

        const scrollToPagos = (pedidoId) => {
            router.push(`/administrador/pedidos/${pedidoId}#pagos`);
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
            return parseFloat(amount).toFixed(2);
        };

        const totalPagado = (pedido) => {
            if (!pedido.pagos) return 0;
            return pedido.pagos
                .filter(p => p.estado === 'aprobado')
                .reduce((sum, pago) => sum + parseFloat(pago.monto), 0);
        };

        const getPorcentajePago = (pedido) => {
            const totalAPagar = parseFloat(pedido.total) + parseFloat(pedido.costo_envio);
            const pagado = totalPagado(pedido);
            return totalAPagar > 0 ? Math.min((pagado / totalAPagar) * 100, 100) : 0;
        };

        const countPagosPendientes = (pedido) => {
            if (!pedido.pagos) return 0;
            return pedido.pagos.filter(p => p.estado === 'pendiente').length;
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

        const getProgressBarClass = (pedido) => {
            const porcentaje = getPorcentajePago(pedido);
            return porcentaje >= 100 ? 'bg-success' : 'bg-primary';
        };

        // Ciclo de vida
        onMounted(() => {
            fetchPedidos();
            fetchEstados();
            fetchPagosPendientesCount();
        });

        return {
            loading,
            pedidos,
            pedidosPaginator,
            estados,
            pagosPendientesCount,
            filters,
            fetchPedidos,
            resetFilters,
            changePage,
            scrollToPagos,
            formatDate,
            formatTime,
            formatCurrency,
            totalPagado,
            getPorcentajePago,
            countPagosPendientes,
            getEstadoBadgeClass,
            getProgressBarClass
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