import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type {
    MetodoPago,
    MetodoPagoForm,
    FiltrosMetodoPago
} from '../types/metodo-pago';
import api from '../services/api';
import { useToast } from 'vue-toastification';

export const useMetodoPagoStore = defineStore('metodoPago', () => {
    const toast = useToast();

    // State
    const metodosPago = ref<MetodoPago[]>([]);
    const metodoPago = ref<MetodoPago | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);
    const filtros = ref<FiltrosMetodoPago>({
        search: '',
        tipo: '',
        activo: '',
        sort_by: 'created_at',
        sort_dir: 'desc',
        per_page: 15
    });
    
    // Nuevo: Estado para paginación
    const pagination = ref({
        current_page: 1,
        per_page: 15,
        total: 0,
        last_page: 1,
        from: 0,
        to: 0
    });

    // Getters
    const metodosActivos = computed(() =>
        metodosPago.value.filter(m => m.activo)
    );

    const metodosInactivos = computed(() =>
        metodosPago.value.filter(m => !m.activo)
    );

    const metodosPorTipo = computed(() => {
        return (tipo: string) =>
            metodosPago.value.filter(m => m.tipo_pago === tipo);
    });

    const totales = computed(() => {
        const tipos = ['Transferencia', 'QR', 'Efectivo', 'Otro'] as const;
        const porTipo: Record<string, number> = {};

        tipos.forEach(tipo => {
            porTipo[tipo] = metodosPago.value.filter(m => m.tipo_pago === tipo).length;
        });

        return {
            total: metodosPago.value.length,
            activos: metodosActivos.value.length,
            inactivos: metodosInactivos.value.length,
            por_tipo: porTipo
        };
    });

    const fetchMetodosPago = async (page = 1) => {
        loading.value = true;
        error.value = null;

        try {
            console.log('=== INICIANDO fetchMetodosPago ===');
            console.log('Filtros:', filtros.value);
            console.log('Página solicitada:', page);

            // Actualizar página en filtros
            const queryParams = {
                ...filtros.value,
                page: page
            };

            console.log('Query params a enviar:', queryParams);

            const response = await api.get('/metodos-pago', { params: queryParams });

            console.log('Respuesta completa del backend:', response);
            console.log('Respuesta data:', response.data);

            // DEPURACIÓN: Mostrar estructura completa
            console.log('Estructura de response.data:');
            console.log('- Es object?', typeof response.data === 'object');
            console.log('- Keys:', Object.keys(response.data));
            
            if (response.data.data) {
                console.log('- response.data.data:', response.data.data);
                console.log('- Es array?', Array.isArray(response.data.data));
            }
            
            if (response.data.meta) {
                console.log('- response.data.meta:', response.data.meta);
            }

            // Manejar diferentes estructuras de respuesta
            let metodosData: MetodoPago[] = [];
            let paginationData = {
                current_page: 1,
                per_page: filtros.value.per_page || 15,
                total: 0,
                last_page: 1,
                from: 0,
                to: 0
            };

            // Opción 1: Respuesta con paginación Laravel estándar
            if (response.data.data && Array.isArray(response.data.data)) {
                console.log('Estructura: Laravel paginated response');
                metodosData = response.data.data;
                
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
                metodosData = response.data;
                paginationData = {
                    current_page: 1,
                    per_page: filtros.value.per_page || 15,
                    total: metodosData.length,
                    last_page: 1,
                    from: 1,
                    to: metodosData.length
                };
            }
            // Opción 3: Estructura con datos anidados
            else if (response.data.data && response.data.data.data && Array.isArray(response.data.data.data)) {
                console.log('Estructura: Datos anidados');
                metodosData = response.data.data.data;
                
                if (response.data.data.meta) {
                    paginationData = {
                        current_page: response.data.data.meta.current_page || page,
                        per_page: response.data.data.meta.per_page || paginationData.per_page,
                        total: response.data.data.meta.total || 0,
                        last_page: response.data.data.meta.last_page || 1,
                        from: response.data.data.meta.from || 0,
                        to: response.data.data.meta.to || 0
                    };
                }
            }
            else {
                console.log('Estructura desconocida, intentando extraer datos...');
                // Buscar recursivamente un array
                const findArrayInObject = (obj: any): any[] => {
                    if (Array.isArray(obj)) return obj;
                    if (obj && typeof obj === 'object') {
                        for (const key in obj) {
                            const result = findArrayInObject(obj[key]);
                            if (result && result.length > 0) return result;
                        }
                    }
                    return [];
                };

                metodosData = findArrayInObject(response.data);
                console.log('Array encontrado recursivamente:', metodosData);
                
                // Si encontramos datos, configurar paginación básica
                if (metodosData.length > 0) {
                    paginationData = {
                        current_page: 1,
                        per_page: filtros.value.per_page || 15,
                        total: metodosData.length,
                        last_page: 1,
                        from: 1,
                        to: metodosData.length
                    };
                }
            }

            console.log('Datos extraídos para metodosPago:', metodosData);
            console.log('Datos de paginación:', paginationData);

            // Asignar los datos
            if (metodosData.length > 0 || response.data) {
                metodosPago.value = metodosData;
                pagination.value = paginationData;
                console.log('Métodos y paginación asignados exitosamente');
            } else {
                metodosPago.value = [];
                console.log('No hay métodos de pago en la respuesta');

                // Verificar si es un error de permisos
                if (response.data.message) {
                    console.log('Mensaje del servidor:', response.data.message);
                    toast.warning(response.data.message);
                }
            }

        } catch (err: any) {
            console.error('❌ ERROR en fetchMetodosPago:', err);

            if (err.response) {
                console.error('Status:', err.response.status);
                console.error('Data:', err.response.data);
                console.error('Headers:', err.response.headers);

                if (err.response.status === 401) {
                    error.value = 'No autorizado. Inicie sesión nuevamente.';
                    toast.error(error.value);
                } else if (err.response.status === 403) {
                    error.value = 'No tiene permisos para ver métodos de pago';
                    toast.error(error.value);
                } else if (err.response.status === 404) {
                    error.value = 'Endpoint no encontrado';
                    toast.error(error.value);
                } else {
                    error.value = err.response?.data?.message || 'Error al cargar métodos de pago';
                    toast.error(error.value);
                }
            } else if (err.request) {
                console.error('No hubo respuesta:', err.request);
                error.value = 'No se recibió respuesta del servidor';
                toast.error(error.value);
            } else {
                console.error('Error de configuración:', err.message);
                error.value = err.message || 'Error al configurar la solicitud';
                toast.error(error.value);
            }
        } finally {
            loading.value = false;
            console.log('=== FINALIZANDO fetchMetodosPago ===');
        }
    };

    const fetchMetodoPago = async (id: number) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.get(`/metodos-pago/${id}`);
            metodoPago.value = response.data.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar método de pago';
            toast.error(error.value);
        } finally {
            loading.value = false;
        }
    };

    const createMetodoPago = async (formData: MetodoPagoForm) => {
        loading.value = true;
        error.value = null;

        try {
            console.log('FormData recibido en store:', formData); // DEBUG

            const data = new FormData();

            // Agregar campos de texto con validaciones
            data.append('nombre_banco', formData.nombre_banco || '');
            data.append('tipo_pago', formData.tipo_pago || '');
            data.append('nombre_titular', formData.nombre_titular || '');
            data.append('numero_cuenta', formData.numero_cuenta || '');
            data.append('tipo_cuenta', formData.tipo_cuenta || '');
            data.append('identificacion', formData.identificacion || '');
            data.append('instrucciones', formData.instrucciones || '');

            // Convertir boolean a string (1/0)
            const activoValue = formData.activo !== undefined ? formData.activo : true;
            const activoString = activoValue ? '1' : '0';
            data.append('activo', activoString);

            // Agregar archivos si existen
            if (formData.imagen_qr instanceof File) {
                data.append('imagen_qr', formData.imagen_qr);
            }

            if (formData.logo_banco instanceof File) {
                data.append('logo_banco', formData.logo_banco);
            }

            // Debug: mostrar todos los datos del FormData
            console.log('Datos que se enviarán:');
            for (let pair of data.entries()) {
                console.log(pair[0] + ': ', pair[1]);
            }

            // Configuración
            const token = localStorage.getItem('token');
            const config = {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json'
                }
            };

            console.log('Enviando a /metodos-pago');
            const response = await api.post('/metodos-pago', data, config);
            console.log('Respuesta recibida:', response.data);

            const newMetodo = response.data.data || response.data;
            metodosPago.value.unshift(newMetodo);

            // Actualizar total en paginación
            pagination.value.total += 1;

            toast.success(response.data.message || 'Método de pago creado exitosamente');
            return newMetodo;

        } catch (err: any) {
            console.error('Error completo en createMetodoPago:', {
                error: err,
                message: err.message,
                response: err.response,
                data: err.response?.data
            });

            // Manejo de errores mejorado
            if (err.response?.status === 422) {
                // Errores de validación de Laravel
                const validationErrors = err.response.data.errors;
                error.value = 'Errores de validación';

                // Mostrar todos los errores
                if (validationErrors) {
                    Object.entries(validationErrors).forEach(([field, messages]) => {
                        if (Array.isArray(messages)) {
                            messages.forEach(message => {
                                toast.error(`${field}: ${message}`);
                            });
                        }
                    });
                }
            } else if (err.response?.data?.message) {
                error.value = err.response.data.message;
                toast.error(error.value);
            } else if (err.message) {
                error.value = err.message;
                toast.error(error.value);
            } else {
                error.value = 'Error desconocido al crear método de pago';
                toast.error(error.value);
            }

            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateMetodoPago = async (id: number, formData: FormData) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.post(`/metodos-pago/${id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            const index = metodosPago.value.findIndex(m => m.id === id);
            if (index !== -1) {
                metodosPago.value[index] = response.data.data;
            }

            toast.success(response.data.message);
            return response.data.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al actualizar método de pago';
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteMetodoPago = async (id: number) => {
        loading.value = true;
        error.value = null;

        try {
            await api.delete(`/metodos-pago/${id}`);

            metodosPago.value = metodosPago.value.filter(m => m.id !== id);
            
            // Actualizar paginación
            pagination.value.total -= 1;
            
            toast.success('Método de pago eliminado exitosamente');
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al eliminar método de pago';
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const toggleActivo = async (id: number) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.patch(`/metodos-pago/${id}/toggle-activo`);

            const index = metodosPago.value.findIndex(m => m.id === id);
            if (index !== -1) {
                metodosPago.value[index] = response.data.data;
            }

            toast.success(response.data.message);
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cambiar estado';
            toast.error(error.value);
        } finally {
            loading.value = false;
        }
    };

    const fetchMetodosActivos = async () => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.get('/metodos-pago-activos');
            return response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar métodos activos';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const resetFiltros = () => {
        filtros.value = {
            search: '',
            tipo: '',
            activo: '',
            sort_by: 'updated_at',
            sort_dir: 'desc',
            per_page: 15
        };
    };

    const resetMetodoPago = () => {
        metodoPago.value = null;
    };

    // Nueva función para cambiar página
    const cambiarPagina = (page: number) => {
        if (page >= 1 && page <= pagination.value.last_page) {
            fetchMetodosPago(page);
        }
    };

    // Getter para calcular páginas disponibles
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

    return {
        // State
        metodosPago,
        metodoPago,
        loading,
        error,
        filtros,
        pagination,
        
        // Getters
        metodosActivos,
        metodosInactivos,
        metodosPorTipo,
        totales,
        paginasDisponibles,

        // Actions
        fetchMetodosPago,
        fetchMetodoPago,
        createMetodoPago,
        updateMetodoPago,
        deleteMetodoPago,
        toggleActivo,
        fetchMetodosActivos,
        resetFiltros,
        resetMetodoPago,
        cambiarPagina
    };
});