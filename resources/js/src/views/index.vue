<template>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                <span>Ventas</span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="grid xl:grid-cols-3 gap-6 mb-6">
                <!-- Revenue Chart - Ventas por Mes -->
                <div class="panel h-full xl:col-span-2">
                    <div class="flex items-center justify-between dark:text-white-light mb-5">
                        <h5 class="font-semibold text-lg">Ventas por Mes</h5>
                        <div class="flex items-center">
                            <span class="text-lg">Total: {{ formatCurrency(ventasTotales) }}</span>
                        </div>
                    </div>
                    <p class="text-lg dark:text-white-light/90">
                        Ventas este mes <span class="text-primary ml-2">{{ formatCurrency(ventasMesActual) }}</span>
                    </p>
                    <div class="relative">
                        <apexchart 
                            v-if="!loadingRevenue && ventasMensualesData.length > 0"
                            height="325" 
                            :options="revenueChart" 
                            :series="revenueSeries" 
                            class="bg-white dark:bg-black rounded-lg overflow-hidden"
                        />
                        <!-- Loader -->
                        <div v-else class="min-h-[325px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                            <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                        </div>
                    </div>
                </div>

                <!-- Sales By Category - Emprendedoras -->
                <div class="panel h-full">
                    <div class="flex items-center mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Emprendedoras</h5>
                    </div>
                    <div>
                        <apexchart 
                            v-if="!loadingEmprendedoras && emprendedorasData.length > 0"
                            height="460" 
                            :options="salesByCategory" 
                            :series="salesByCategorySeries" 
                            class="bg-white dark:bg-black rounded-lg overflow-hidden"
                        />
                        <!-- Loader -->
                        <div v-else class="min-h-[460px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                            <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                <div class="panel">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Total Pedidos</h5>
                        <span :class="[
                            'badge',
                            pedidosCrecimiento >= 0 ? 'bg-success' : 'bg-danger'
                        ]">
                            {{ pedidosCrecimiento >= 0 ? '+' : '' }}{{ pedidosCrecimiento }}%
                        </span>
                    </div>
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h5 class="text-3xl font-bold">{{ totalPedidos }}</h5>
                            <p class="text-sm text-gray-500">Este mes: {{ pedidosEsteMes }}</p>
                        </div>
                        <div class="w-32">
                            <apexchart height="80" :options="totalOrders" :series="totalOrdersSeries" />
                        </div>
                    </div>
                </div>
                
                <!-- Otras métricas -->
                <div class="panel">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Total Clientes</h5>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="text-3xl font-bold">{{ totalEmprendedoras }}</h5>
                            <p class="text-sm text-gray-500">Nuevas este mes: {{ nuevasEmprendedoras }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-primary/10">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-8a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="panel">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Ventas Totales</h5>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="text-3xl font-bold">{{ formatCurrency(ventasTotales) }}</h5>
                            <p class="text-sm text-gray-500">Este mes: {{ formatCurrency(ventasMesActual) }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-success/10">
                            <svg class="w-8 h-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="panel">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Productos Activos</h5>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="text-3xl font-bold">{{ totalProductos }}</h5>
                            <p class="text-sm text-gray-500">En inventario</p>
                        </div>
                        <div class="p-3 rounded-full bg-warning/10">
                            <svg class="w-8 h-8 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="panel h-full w-full">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Órdenes Recientes</h5>
                        <router-link to="/pedidos" class="text-primary hover:underline">Ver todas</router-link>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="ltr:rounded-l-md rtl:rounded-r-md">Cliente</th>
                                    <th>Código</th>
                                    <th>Total</th>
                                    <th class="ltr:rounded-r-md rtl:rounded-l-md">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="orden in ordenesRecientes" :key="orden.id" class="text-white-dark hover:text-black dark:hover:text-white-light/90 group">
                                    <td class="min-w-[150px] text-black dark:text-white">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-md ltr:mr-3 rtl:ml-3 bg-gray-200 flex items-center justify-center">
                                                <span class="font-bold">{{ orden.cliente_nombre?.charAt(0) || 'C' }}</span>
                                            </div>
                                            <span class="whitespace-nowrap">{{ orden.cliente_nombre || 'Cliente' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-primary">{{ orden.codigo_reserva || 'N/A' }}</td>
                                    <td>{{ formatCurrency(orden.total || 0) }}</td>
                                    <td>
                                        <span :class="['badge', `bg-${orden.color_estado || 'secondary'}`, 'shadow-md dark:group-hover:bg-transparent']">
                                            {{ orden.estado || 'Desconocido' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="ordenesRecientes.length === 0">
                                    <td colspan="4" class="text-center py-4 text-gray-500">
                                        No hay órdenes recientes
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Selling Products -->
                <div class="panel h-full w-full">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Productos Más Vendidos</h5>
                        <router-link to="/productos" class="text-primary hover:underline">Ver todos</router-link>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr class="border-b-0">
                                    <th class="ltr:rounded-l-md rtl:rounded-r-md">Producto</th>
                                    <th>Precio Prom.</th>
                                    <th>Vendidos</th>
                                    <th class="ltr:rounded-r-md rtl:rounded-l-md">Ventas Totales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="producto in topProductos" :key="producto.articulo_id">
                                    <td class="min-w-[150px] text-black dark:text-white">
                                        <div class="flex">
                                            <div class="w-8 h-8 rounded-md ltr:mr-3 rtl:ml-3 bg-gray-200 flex items-center justify-center overflow-hidden">
                                               
                                            </div>
                                            <p class="whitespace-nowrap">
                                                {{ producto.producto_nombre || 'Producto' }}
                                                <span class="text-primary block text-xs">{{ producto.categoria || 'General' }}</span>
                                            </p>
                                        </div>
                                    </td>
                                  
                                    <td>
                                        {{ producto.total_vendido || 0 }}
                                        <div class="text-xs text-gray-500">
                                            {{ producto.pedidos_totales || 0 }} pedidos
                                        </div>
                                    </td>
                                    <td>
                                        <span class="flex items-center text-success font-semibold">
                                            {{ formatCurrency(producto.total_ventas || 0) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="topProductos.length === 0">
                                    <td colspan="4" class="text-center py-4 text-gray-500">
                                        No hay datos de productos vendidos
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted } from 'vue';
import apexchart from 'vue3-apexcharts';
import api from '../services/api'; // <-- Importa tu api.js
import type { VentaMensual, Emprendedora, OrdenReciente, TopProducto } from '../types/dashboard';

export default defineComponent({
    name: 'Dashboard',
    components: {
        apexchart
    },
    setup() {
        // Estados reactivos (igual que antes)
        const loadingRevenue = ref(true);
        const loadingEmprendedoras = ref(true);
        const ventasMensualesData = ref<VentaMensual[]>([]);
        const ventasTotales = ref(0);
        const ventasMesActual = ref(0);
        const emprendedorasData = ref<Emprendedora[]>([]);
        const totalEmprendedoras = ref(0);
        const nuevasEmprendedoras = ref(0);
        const totalPedidos = ref(0);
        const pedidosEsteMes = ref(0);
        const pedidosCrecimiento = ref(0);
        const ordenesRecientes = ref<OrdenReciente[]>([]);
        const topProductos = ref<TopProducto[]>([]);
        const totalProductos = ref(0);

        // Configuración de tema
        const themeConfig = {
            theme: 'light',
            isDarkMode: false,
            rtlClass: 'ltr'
        };

        // Función para formatear moneda
        const formatCurrency = (amount: number): string => {
            return new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN'
            }).format(amount);
        };

        // Función para cargar todos los datos del dashboard usando tu api.js
        const cargarDashboard = async () => {
            try {
                // Cargar datos en paralelo usando tu api.js
                const [
                    ventasResponse,
                    emprendedorasResponse,
                    pedidosResponse,
                    ordenesResponse,
                    productosResponse,
                    dashboardResponse
                ] = await Promise.all([
                    api.get('/reportes/ventas-mensuales'),
                    api.get('/reportes/emprendedoras'),
                    api.get('/reportes/estadisticas-pedidos-simple'),
                    api.get('/reportes/ordenes-recientes'),
                    api.get('/reportes/top-productos'),
                    api.get('/reportes/dashboard')
                ]);

                // Procesar ventas mensuales
                if (ventasResponse.data.success) {
                    ventasMensualesData.value = ventasResponse.data.data.ventas_mensuales || [];
                    ventasTotales.value = ventasResponse.data.data.totales?.ventas_totales || 0;
                    ventasMesActual.value = ventasResponse.data.data.totales?.ventas_mes_actual || 0;
                    loadingRevenue.value = false;
                }

                // Procesar emprendedoras
                if (emprendedorasResponse.data.success) {
                    emprendedorasData.value = emprendedorasResponse.data.data.emprendedoras || [];
                    totalEmprendedoras.value = emprendedorasResponse.data.data.totales?.total_emprendedoras || 0;
                    nuevasEmprendedoras.value = emprendedorasResponse.data.data.totales?.nuevas_este_mes || 0;
                    loadingEmprendedoras.value = false;
                }

                // Procesar pedidos
                if (pedidosResponse.data.success) {
                    totalPedidos.value = pedidosResponse.data.data.total_pedidos || 0;
                    pedidosEsteMes.value = pedidosResponse.data.data.pedidos_este_mes || 0;
                    pedidosCrecimiento.value = pedidosResponse.data.data.crecimiento_porcentual || 0;
                }

                // Procesar órdenes recientes
                if (ordenesResponse.data.success) {
                    // Transformar datos para agregar color_estado
                    ordenesRecientes.value = (ordenesResponse.data.data || []).map((orden: any) => {
                        const coloresEstado: Record<string, string> = {
                            'pendiente': 'warning',
                            'abonado': 'info',
                            'completado': 'success',
                            'rechazado': 'danger',
                            'cancelado': 'secondary',
                            'pendiente_abono': 'warning',
                            'pendiente_pago_total': 'warning',
                            'validando_pago': 'info'
                        };
                        
                        return {
                            ...orden,
                            color_estado: coloresEstado[orden.estado] || 'secondary'
                        };
                    });
                }

                // Procesar top productos
                if (productosResponse.data.success) {
                    topProductos.value = productosResponse.data.data || [];
                }

                // Procesar dashboard para total productos
                if (dashboardResponse.data.success) {
                    totalProductos.value = dashboardResponse.data.data.productos?.total || 0;
                }

            } catch (error: any) {
                console.error('Error cargando dashboard:', error);
                
                // Mostrar mensaje de error al usuario
                if (error.response?.status === 401) {
                    // El interceptor ya maneja la redirección a login
                    console.log('Sesión expirada, redirigiendo...');
                } else if (error.response?.data?.message) {
                    console.error('Error del servidor:', error.response.data.message);
                }
            }
        };

        // Función para refrescar datos manualmente
        const refrescarDatos = () => {
            loadingRevenue.value = true;
            loadingEmprendedoras.value = true;
            cargarDashboard();
        };

        // Cargar datos al montar el componente
        onMounted(() => {
            cargarDashboard();
        });

        // Computed properties para gráficas (igual que antes)
        const revenueChart = computed(() => {
            const isDark = themeConfig.theme === 'dark' || themeConfig.isDarkMode ? true : false;
            const isRtl = themeConfig.rtlClass === 'rtl' ? true : false;
            
            const labels = ventasMensualesData.value.map(item => item.mes_nombre);
            const data = ventasMensualesData.value.map(item => item.total);
            
            return {
                chart: {
                    height: 325,
                    type: 'area',
                    fontFamily: 'Nunito, sans-serif',
                    zoom: { enabled: false },
                    toolbar: { show: false },
                },
                dataLabels: { enabled: false },
                stroke: {
                    show: true,
                    curve: 'smooth',
                    width: 2,
                    lineCap: 'square',
                },
                dropShadow: {
                    enabled: true,
                    opacity: 0.2,
                    blur: 10,
                    left: -7,
                    top: 22,
                },
                colors: isDark ? ['#2196f3'] : ['#1b55e2'],
                markers: {
                    discrete: [{
                        seriesIndex: 0,
                        dataPointIndex: data.length - 1,
                        fillColor: '#1b55e2',
                        strokeColor: 'transparent',
                        size: 7,
                    }],
                },
                labels: labels,
                xaxis: {
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    crosshairs: { show: true },
                    labels: {
                        offsetX: isRtl ? 2 : 0,
                        offsetY: 5,
                        style: {
                            fontSize: '12px',
                            cssClass: 'apexcharts-xaxis-title',
                        },
                    },
                },
                yaxis: {
                    tickAmount: 5,
                    labels: {
                        formatter: (value: number) => {
                            return formatCurrency(value);
                        },
                        offsetX: isRtl ? -30 : -10,
                        offsetY: 0,
                        style: {
                            fontSize: '12px',
                            cssClass: 'apexcharts-yaxis-title',
                        },
                    },
                    opposite: isRtl ? true : false,
                },
                grid: {
                    borderColor: isDark ? '#191e3a' : '#e0e6ed',
                    strokeDashArray: 5,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: false } },
                    padding: { top: 0, right: 0, bottom: 0, left: 0 },
                },
                legend: { show: false },
                tooltip: {
                    y: {
                        formatter: (value: number) => {
                            return formatCurrency(value);
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: !1,
                        opacityFrom: isDark ? 0.19 : 0.28,
                        opacityTo: 0.05,
                        stops: isDark ? [100, 100] : [45, 100],
                    },
                },
            };
        });

        const revenueSeries = computed(() => [{
            name: 'Ventas',
            data: ventasMensualesData.value.map(item => item.total)
        }]);

        const salesByCategory = computed(() => {
            const isDark = themeConfig.theme === 'dark' || themeConfig.isDarkMode ? true : false;
            const labels = emprendedorasData.value.map(item => item.categoria);
            const colors = emprendedorasData.value.map(item => item.color);
            const series = emprendedorasData.value.map(item => item.cantidad);
            
            return {
                chart: {
                    type: 'donut',
                    height: 460,
                    fontFamily: 'Nunito, sans-serif',
                },
                dataLabels: { enabled: false },
                stroke: {
                    show: true,
                    width: 25,
                    colors: isDark ? '#0e1726' : '#fff',
                },
                colors: colors,
                labels: labels,
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: { width: 10, height: 10, offsetX: -2 },
                    height: 50,
                    offsetY: 20,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '22px', offsetY: -10 },
                                value: {
                                    show: true,
                                    fontSize: '20px',
                                    color: isDark ? '#bfc9d4' : undefined,
                                    offsetY: 16,
                                    formatter: (val: any) => val,
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: '#888ea8',
                                    fontSize: '22px',
                                    formatter: () => totalEmprendedoras.value,
                                },
                            },
                        },
                    },
                },
                states: {
                    hover: { filter: { type: 'none', value: 0.15 } },
                    active: { filter: { type: 'none', value: 0.15 } },
                },
            };
        });

        const salesByCategorySeries = computed(() => 
            emprendedorasData.value.map(item => item.cantidad)
        );

        const totalOrders = computed(() => ({
            chart: {
                height: 80,
                type: 'area',
                fontFamily: 'Nunito, sans-serif',
                sparkline: { enabled: true },
            },
            stroke: { curve: 'smooth', width: 2 },
            colors: ['#00ab55'],
            labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
            yaxis: { min: 0, show: false },
            grid: { padding: { top: 125, right: 0, bottom: 0, left: 0 } },
            fill: {
                opacity: 1,
                type: 'gradient',
                gradient: {
                    type: 'vertical',
                    shadeIntensity: 1,
                    inverseColors: !1,
                    opacityFrom: 0.3,
                    opacityTo: 0.05,
                    stops: [100, 100],
                },
            },
            tooltip: { x: { show: false } },
        }));

        const totalOrdersSeries = computed(() => [{
            name: 'Pedidos',
            data: [28, 40, 36, 52, 38, 60, 38, 52, 36, 40]
        }]);

        // Exponer todas las variables al template
        return {
            // Estados de carga
            loadingRevenue,
            loadingEmprendedoras,
            
            // Datos
            ventasMensualesData,
            emprendedorasData,
            ventasTotales,
            ventasMesActual,
            totalEmprendedoras,
            nuevasEmprendedoras,
            totalPedidos,
            pedidosEsteMes,
            pedidosCrecimiento,
            ordenesRecientes,
            topProductos,
            totalProductos,
            
            // Funciones
            formatCurrency,
            refrescarDatos,
            
            // Gráficas
            revenueChart,
            revenueSeries,
            salesByCategory,
            salesByCategorySeries,
            totalOrders,
            totalOrdersSeries
        };
    }
});
</script>