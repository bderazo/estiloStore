import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { useToast } from 'vue-toastification';
import api from '../services/api';
import type { Pedido, PedidoPago, PedidoDetalle } from '../types/pedido';

interface PedidosFiltros {
    fecha_inicio: string;
    fecha_fin: string;
    estado: string;
    codigo: string;
    sort_by?: string;
    sort_dir?: string;
    page: number;
    per_page: number;
}

interface Estadisticas {
    total_pedidos: number;
    pedidos_pendientes: number;
    pedidos_pagados: number;
    pedidos_en_proceso: number;
    total_recaudado: number;
    pedidos_por_estado: Record<string, number>;
    pagos_por_estado: Record<string, number>;
    ventas_por_mes: Array<{
        mes: string;
        total_pedidos: number;
        total_ventas: number;
    }>;
}

export const usePedidoStore = defineStore('pedidos', () => {
    const toast = useToast();

    // State
    const pedidos = ref<Pedido[]>([]);
    const pedidoDetalle = ref<Pedido | null>(null);
    const loading = ref(false);
    const loadingDetalle = ref(false);
    const loadingPagos = ref(false);
    const error = ref<string | null>(null);
    
    const filtros = ref<PedidosFiltros>({
        fecha_inicio: '',
        fecha_fin: '',
        estado: '',
        codigo: '',
        sort_by: 'created_at',
        sort_dir: 'desc',
        page: 1,
        per_page: 20
    });

    const pagination = ref({
        current_page: 1,
        per_page: 20,
        total: 0,
        last_page: 1,
        from: 0,
        to: 0
    });

    const estadisticas = ref<Estadisticas | null>(null);
    const estados = ref<string[]>([]);
    const pagosPendientesCount = ref(0);

    // Getters
    const pedidosFiltrados = computed(() => pedidos.value);
    const totalPedidos = computed(() => pagination.value.total);

    const paginasDisponibles = computed(() => {
        const pages = [];
        const totalPages = pagination.value.last_page;
        const currentPage = pagination.value.current_page;
        
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

    // Actions
    const fetchPedidos = async (page = 1) => {
        loading.value = true;
        error.value = null;
        filtros.value.page = page;

        try {
            console.log('=== INICIANDO fetchPedidos ===');
            console.log('Filtros:', filtros.value);
            console.log('Página solicitada:', page);

            const queryParams = {
                ...filtros.value,
                page: page
            };

            console.log('Query params a enviar:', queryParams);

            const response = await api.get('/pedidos', { params: queryParams });

            console.log('Respuesta completa del backend:', response);
            console.log('Respuesta data:', response.data);

            // Manejar diferentes estructuras de respuesta
            let pedidosData: Pedido[] = [];
            let paginationData = {
                current_page: page,
                per_page: filtros.value.per_page,
                total: 0,
                last_page: 1,
                from: 0,
                to: 0
            };

            // Opción 1: Respuesta con paginación Laravel estándar
            if (response.data.data && Array.isArray(response.data.data)) {
                console.log('Estructura: Laravel paginated response');
                pedidosData = response.data.data;
                
                if (response.data.meta) {
                    paginationData = {
                        current_page: response.data.meta.current_page || page,
                        per_page: response.data.meta.per_page || paginationData.per_page,
                        total: response.data.meta.total || 0,
                        last_page: response.data.meta.last_page || 1,
                        from: response.data.meta.from || 0,
                        to: response.data.meta.to || 0
                    };
                } else if (response.data.current_page) {
                    // Si está en la raíz de response.data
                    paginationData = {
                        current_page: response.data.current_page || page,
                        per_page: response.data.per_page || paginationData.per_page,
                        total: response.data.total || 0,
                        last_page: response.data.last_page || 1,
                        from: response.data.from || 0,
                        to: response.data.to || 0
                    };
                }
            }
            // Opción 2: Array directo (sin paginación)
            else if (Array.isArray(response.data)) {
                console.log('Estructura: Array directo (sin paginación)');
                pedidosData = response.data;
                paginationData = {
                    current_page: 1,
                    per_page: filtros.value.per_page,
                    total: pedidosData.length,
                    last_page: 1,
                    from: 1,
                    to: pedidosData.length
                };
            }

            console.log('Datos extraídos para pedidos:', pedidosData);
            console.log('Datos de paginación:', paginationData);

            // Asignar los datos
            pedidos.value = pedidosData;
            pagination.value = paginationData;

            console.log('Pedidos y paginación asignados exitosamente');

        } catch (err: any) {
            console.error('❌ ERROR en fetchPedidos:', err);
            error.value = 'Error al cargar los pedidos';
        } finally {
            loading.value = false;
            console.log('=== FINALIZANDO fetchPedidos ===');
        }
    };

    const fetchPedidoDetalle = async (id: number) => {
        loadingDetalle.value = true;
        error.value = null;

        try {
            console.log(`=== INICIANDO fetchPedidoDetalle para ID: ${id} ===`);

            const response = await api.get(`/pedidos/${id}`);

            console.log('Respuesta de detalle de pedido:', response.data);

            // Asegurar que los datos tengan la estructura correcta
            pedidoDetalle.value = response.data.data || response.data;

            // Si no hay pagos en la respuesta inicial, cargarlos por separado
            if (!pedidoDetalle.value?.pagos || pedidoDetalle.value.pagos.length === 0) {
                await fetchPagosPedido(id);
            }

        } catch (err: any) {
            console.error('❌ ERROR en fetchPedidoDetalle:', err);
            error.value = 'Error al cargar el detalle del pedido';
            toast.error(error.value);
        } finally {
            loadingDetalle.value = false;
            console.log('=== FINALIZANDO fetchPedidoDetalle ===');
        }
    };

    const fetchPagosPedido = async (pedidoId: number) => {
        loadingPagos.value = true;

        try {
            console.log(`=== INICIANDO fetchPagosPedido para pedido ID: ${pedidoId} ===`);

            const response = await api.get(`/pedidos/${pedidoId}/pagos`);

            console.log('Respuesta de pagos del pedido:', response.data);

            if (pedidoDetalle.value) {
                pedidoDetalle.value.pagos = response.data.data || response.data;
            }

        } catch (err: any) {
            console.error('❌ ERROR en fetchPagosPedido:', err);
            toast.error('Error al cargar los pagos del pedido');
        } finally {
            loadingPagos.value = false;
            console.log('=== FINALIZANDO fetchPagosPedido ===');
        }
    };

    const fetchEstados = async () => {
        try {
            console.log('=== INICIANDO fetchEstados ===');

            const response = await api.get('/pedidos/estados');

            console.log('Estados recibidos:', response.data);

            estados.value = Array.isArray(response.data) ? response.data : [];

        } catch (err: any) {
            console.error('❌ ERROR en fetchEstados:', err);
            toast.error('Error al cargar los estados');
        }
    };

    const fetchPagosPendientesCount = async () => {
        try {
            console.log('=== INICIANDO fetchPagosPendientesCount ===');

            const response = await api.get('/pagos/pendientes/count');

            console.log('Conteo de pagos pendientes:', response.data);

            pagosPendientesCount.value = response.data.count || 0;

        } catch (err: any) {
            console.error('❌ ERROR en fetchPagosPendientesCount:', err);
        }
    };

    const fetchEstadisticas = async () => {
        loading.value = true;

        try {
            console.log('=== INICIANDO fetchEstadisticas ===');

            const response = await api.get('/pedidos/estadisticas');

            console.log('Estadísticas recibidas:', response.data);

            estadisticas.value = response.data;

        } catch (err: any) {
            console.error('❌ ERROR en fetchEstadisticas:', err);
            toast.error('Error al cargar las estadísticas');
        } finally {
            loading.value = false;
            console.log('=== FINALIZANDO fetchEstadisticas ===');
        }
    };

    const aprobarPago = async (pagoId: number, observacion?: string) => {
        loadingPagos.value = true;

        try {
            console.log(`=== INICIANDO aprobarPago para pago ID: ${pagoId} ===`);

            const response = await api.post(`/pagos/${pagoId}/aprobar`, {
                observacion: observacion || 'Pago aprobado por administrador'
            });

            console.log('Respuesta de aprobación:', response.data);

            // Actualizar el estado del pago localmente
            if (pedidoDetalle.value?.pagos) {
                const pagoIndex = pedidoDetalle.value.pagos.findIndex(p => p.id === pagoId);
                if (pagoIndex !== -1) {
                    pedidoDetalle.value.pagos[pagoIndex].estado = 'aprobado';
                    pedidoDetalle.value.pagos[pagoIndex].observacion = observacion || 'Pago aprobado';
                }
            }

            toast.success('Pago aprobado correctamente');
            return true;

        } catch (err: any) {
            console.error('❌ ERROR en aprobarPago:', err);
            toast.error('Error al aprobar el pago');
            throw err;
        } finally {
            loadingPagos.value = false;
            console.log('=== FINALIZANDO aprobarPago ===');
        }
    };

    const rechazarPago = async (pagoId: number, motivo: string) => {
        loadingPagos.value = true;

        try {
            console.log(`=== INICIANDO rechazarPago para pago ID: ${pagoId} ===`);
            console.log('Motivo:', motivo);

            const response = await api.post(`/pagos/${pagoId}/rechazar`, {
                motivo: motivo
            });

            console.log('Respuesta de rechazo:', response.data);

            // Actualizar el estado del pago localmente
            if (pedidoDetalle.value?.pagos) {
                const pagoIndex = pedidoDetalle.value.pagos.findIndex(p => p.id === pagoId);
                if (pagoIndex !== -1) {
                    pedidoDetalle.value.pagos[pagoIndex].estado = 'rechazado';
                    pedidoDetalle.value.pagos[pagoIndex].observacion = motivo;
                }
            }

            toast.success('Pago rechazado correctamente');
            return true;

        } catch (err: any) {
            console.error('❌ ERROR en rechazarPago:', err);
            toast.error('Error al rechazar el pago');
            throw err;
        } finally {
            loadingPagos.value = false;
            console.log('=== FINALIZANDO rechazarPago ===');
        }
    };

    const cambiarEstadoPedido = async (pedidoId: number, estado: string, observacion?: string) => {
        loading.value = true;

        try {
            console.log(`=== INICIANDO cambiarEstadoPedido para pedido ID: ${pedidoId} ===`);
            console.log('Nuevo estado:', estado);

            const response = await api.post(`/pedidos/${pedidoId}/cambiar-estado`, {
                estado: estado,
                observacion: observacion
            });

            console.log('Respuesta de cambio de estado:', response.data);

            // Actualizar el estado del pedido localmente
            if (pedidoDetalle.value && pedidoDetalle.value.id === pedidoId) {
                pedidoDetalle.value.estado = estado as Pedido['estado'];
            }

            // Actualizar en la lista si existe
            const pedidoIndex = pedidos.value.findIndex(p => p.id === pedidoId);
            if (pedidoIndex !== -1) {
                pedidos.value[pedidoIndex].estado = estado as Pedido['estado'];
            }

            toast.success(`Estado del pedido cambiado a "${estado}"`);
            return true;

        } catch (err: any) {
            console.error('❌ ERROR en cambiarEstadoPedido:', err);
            toast.error('Error al cambiar el estado del pedido');
            throw err;
        } finally {
            loading.value = false;
            console.log('=== FINALIZANDO cambiarEstadoPedido ===');
        }
    };

    const resetFiltros = () => {
        filtros.value = {
            fecha_inicio: '',
            fecha_fin: '',
            estado: '',
            codigo: '',
            sort_by: 'created_at',
            sort_dir: 'desc',
            page: 1,
            per_page: 20
        };
    };

    const clearPedidoDetalle = () => {
        pedidoDetalle.value = null;
    };

    const cambiarPagina = (page: number) => {
        if (page >= 1 && page <= pagination.value.last_page) {
            fetchPedidos(page);
        }
    };

    // Métodos para actualizar paginación dinámicamente
    const setPerPage = (perPage: number) => {
        filtros.value.per_page = perPage;
        fetchPedidos(1); // Volver a la primera página con nuevo tamaño
    };

    const setSort = (sortBy: string, sortDir: string = 'desc') => {
        filtros.value.sort_by = sortBy;
        filtros.value.sort_dir = sortDir;
        fetchPedidos(1);
    };

    return {
        // State
        pedidos,
        pedidoDetalle,
        loading,
        loadingDetalle,
        loadingPagos,
        error,
        filtros,
        pagination,
        estadisticas,
        estados,
        pagosPendientesCount,

        // Getters
        pedidosFiltrados,
        totalPedidos,
        paginasDisponibles,

        // Actions
        fetchPedidos,
        fetchPedidoDetalle,
        fetchPagosPedido,
        fetchEstados,
        fetchPagosPendientesCount,
        fetchEstadisticas,
        aprobarPago,
        rechazarPago,
        cambiarEstadoPedido,
        resetFiltros,
        clearPedidoDetalle,
        cambiarPagina,
        setPerPage,
        setSort
    };
});