import { defineStore } from "pinia";
import { ref, computed } from "vue";
import type {
    Testimonial,
    TestimonialFormData,
    TestimonialConfig,
    FiltrosTestimonial,
} from "../types/testimonial";
import api from "../services/api";
import { useToast } from "vue-toastification";

export const useTestimonialStore = defineStore("testimonial", () => {
    const toast = useToast();

    // State
    const testimonials = ref<Testimonial[]>([]);
    const testimonial = ref<Testimonial | null>(null);
    const config = ref<TestimonialConfig>({
        titulo: "Testimonios de Clientes",
        subtitulo: "Lo que dicen nuestras clientas",
        autoplay: true,
        autoplay_speed: 5000,
        mostrar_controles: true,
        mostrar_indicadores: true,
    });
    const loading = ref(false);
    const error = ref<string | null>(null);
    const filtros = ref<FiltrosTestimonial>({
        search: "",
        activo: "",
        sort_by: "orden",
        sort_dir: "asc",
        per_page: 15,
    });

    // Paginación
    const pagination = ref({
        current_page: 1,
        per_page: 15,
        total: 0,
        last_page: 1,
        from: 0,
        to: 0,
    });

    // Getters
    const testimonialsActivos = computed(() =>
        testimonials.value.filter((t) => t.activo),
    );

    const testimonialsInactivos = computed(() =>
        testimonials.value.filter((t) => !t.activo),
    );

    const testimonialsOrdenados = computed(() =>
        [...testimonials.value].sort((a, b) => a.orden - b.orden),
    );

    const totales = computed(() => {
        return {
            total: testimonials.value.length,
            activos: testimonialsActivos.value.length,
            inactivos: testimonialsInactivos.value.length,
        };
    });

    // Actions
    const fetchTestimonials = async (page = 1) => {
        loading.value = true;
        error.value = null;

        try {
            console.log("=== INICIANDO fetchTestimonials ===");
            console.log("Filtros:", filtros.value);
            console.log("Página solicitada:", page);

            const queryParams = {
                ...filtros.value,
                page: page,
            };

            const response = await api.get("/testimonials", {
                params: queryParams,
            });

            console.log("Respuesta del backend:", response);

            // Manejar diferentes estructuras de respuesta
            let testimonialsData: Testimonial[] = [];
            let paginationData = {
                current_page: 1,
                per_page: filtros.value.per_page || 15,
                total: 0,
                last_page: 1,
                from: 0,
                to: 0,
            };

            // Opción 1: Respuesta con paginación Laravel estándar
            if (response.data.data && Array.isArray(response.data.data)) {
                testimonialsData = response.data.data;

                if (response.data.meta) {
                    paginationData = {
                        current_page: response.data.meta.current_page || page,
                        per_page:
                            response.data.meta.per_page ||
                            paginationData.per_page,
                        total: response.data.meta.total || 0,
                        last_page: response.data.meta.last_page || 1,
                        from: response.data.meta.from || 0,
                        to: response.data.meta.to || 0,
                    };
                }
            }
            // Opción 2: Array directo
            else if (Array.isArray(response.data)) {
                testimonialsData = response.data;
                paginationData = {
                    current_page: 1,
                    per_page: filtros.value.per_page || 15,
                    total: testimonialsData.length,
                    last_page: 1,
                    from: 1,
                    to: testimonialsData.length,
                };
            }
            // Opción 3: Estructura con datos anidados
            else if (
                response.data.data &&
                response.data.data.data &&
                Array.isArray(response.data.data.data)
            ) {
                testimonialsData = response.data.data.data;

                if (response.data.data.meta) {
                    paginationData = {
                        current_page:
                            response.data.data.meta.current_page || page,
                        per_page:
                            response.data.data.meta.per_page ||
                            paginationData.per_page,
                        total: response.data.data.meta.total || 0,
                        last_page: response.data.data.meta.last_page || 1,
                        from: response.data.data.meta.from || 0,
                        to: response.data.data.meta.to || 0,
                    };
                }
            }

            console.log("Datos extraídos:", testimonialsData);
            console.log("Paginación:", paginationData);

            if (testimonialsData.length > 0 || response.data) {
                testimonials.value = testimonialsData;
                pagination.value = paginationData;
            } else {
                testimonials.value = [];
            }
        } catch (err: any) {
            console.error("❌ ERROR en fetchTestimonials:", err);

            if (err.response) {
                if (err.response.status === 401) {
                    error.value = "No autorizado. Inicie sesión nuevamente.";
                    toast.error(error.value);
                } else if (err.response.status === 403) {
                    error.value = "No tiene permisos para ver testimonios";
                    toast.error(error.value);
                } else {
                    error.value =
                        err.response?.data?.message ||
                        "Error al cargar testimonios";
                    toast.error(error.value);
                }
            } else if (err.request) {
                error.value = "No se recibió respuesta del servidor";
                toast.error(error.value);
            } else {
                error.value = err.message || "Error al configurar la solicitud";
                toast.error(error.value);
            }
        } finally {
            loading.value = false;
            console.log("=== FINALIZANDO fetchTestimonials ===");
        }
    };

    const fetchTestimonial = async (id: number) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.get(`/testimonials/${id}`);
            testimonial.value = response.data.data || response.data;
        } catch (err: any) {
            error.value =
                err.response?.data?.message || "Error al cargar testimonio";
            toast.error(error.value);
        } finally {
            loading.value = false;
        }
    };

    const fetchConfig = async () => {
        loading.value = true;
        try {
            const response = await api.get("/testimonials/config");

            // Manejar diferentes estructuras de respuesta
            if (response.data.success && response.data.data) {
                config.value = response.data.data;
            } else if (response.data.data) {
                config.value = response.data.data;
            } else {
                config.value = response.data;
            }

            return config.value;
        } catch (err: any) {
            console.error("Error fetching config:", err);
            error.value =
                err.response?.data?.message || "Error al cargar configuración";
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const createTestimonial = async (formData: FormData) => {
        loading.value = true;
        error.value = null;

        try {
            console.log("FormData recibido en store:", formData);

            const token = localStorage.getItem("token");
            const config = {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "multipart/form-data",
                    Accept: "application/json",
                },
            };

            const response = await api.post("/testimonials", formData, config);
            console.log("Respuesta recibida:", response.data);

            const newTestimonial = response.data.data || response.data;
            testimonials.value.unshift(newTestimonial);

            // Actualizar total en paginación
            pagination.value.total += 1;

            toast.success(
                response.data.message || "Testimonio creado exitosamente",
            );
            return newTestimonial;
        } catch (err: any) {
            console.error("Error en createTestimonial:", err);

            if (err.response?.status === 422) {
                const validationErrors = err.response.data.errors;
                error.value = "Errores de validación";

                if (validationErrors) {
                    Object.entries(validationErrors).forEach(
                        ([field, messages]) => {
                            if (Array.isArray(messages)) {
                                messages.forEach((message) => {
                                    toast.error(`${field}: ${message}`);
                                });
                            }
                        },
                    );
                }
            } else if (err.response?.data?.message) {
                error.value = err.response.data.message;
                toast.error(error.value);
            } else if (err.message) {
                error.value = err.message;
                toast.error(error.value);
            } else {
                error.value = "Error desconocido al crear testimonio";
                toast.error(error.value);
            }

            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateTestimonial = async (id: number, formData: FormData) => {
        loading.value = true;
        error.value = null;

        try {
            // Para Laravel, usar POST con _method PUT para archivos
            formData.append("_method", "PUT");

            const token = localStorage.getItem("token");
            const config = {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "multipart/form-data",
                    Accept: "application/json",
                },
            };

            const response = await api.post(
                `/testimonials/${id}`,
                formData,
                config,
            );

            const index = testimonials.value.findIndex((t) => t.id === id);
            if (index !== -1) {
                testimonials.value[index] = response.data.data || response.data;
            }

            toast.success(
                response.data.message || "Testimonio actualizado exitosamente",
            );
            return response.data.data || response.data;
        } catch (err: any) {
            console.error("Error en updateTestimonial:", err);
            error.value =
                err.response?.data?.message || "Error al actualizar testimonio";
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteTestimonial = async (id: number) => {
        loading.value = true;
        error.value = null;

        try {
            await api.delete(`/testimonials/${id}`);

            testimonials.value = testimonials.value.filter((t) => t.id !== id);

            // Actualizar paginación
            pagination.value.total -= 1;

            toast.success("Testimonio eliminado exitosamente");
        } catch (err: any) {
            error.value =
                err.response?.data?.message || "Error al eliminar testimonio";
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const toggleStatus = async (id: number) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.patch(`/testimonials/${id}/toggle`);

            const index = testimonials.value.findIndex((t) => t.id === id);
            if (index !== -1) {
                testimonials.value[index] = response.data.data || response.data;
            }

            toast.success(
                response.data.message || "Estado cambiado exitosamente",
            );
        } catch (err: any) {
            error.value =
                err.response?.data?.message || "Error al cambiar estado";
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const saveConfig = async (configData: TestimonialConfig) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.post("/testimonials/config", configData);

            if (response.data.success && response.data.data) {
                config.value = response.data.data;
            } else if (response.data.data) {
                config.value = response.data.data;
            } else {
                config.value = response.data;
            }

            toast.success(
                response.data.message || "Configuración guardada exitosamente",
            );
            return true;
        } catch (err: any) {
            console.error("Error saving config:", err);
            error.value =
                err.response?.data?.message || "Error al guardar configuración";
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const resetFiltros = () => {
        filtros.value = {
            search: "",
            activo: "",
            sort_by: "orden",
            sort_dir: "asc",
            per_page: 15,
        };
    };

    const resetTestimonial = () => {
        testimonial.value = null;
    };

    const cambiarPagina = (page: number) => {
        if (page >= 1 && page <= pagination.value.last_page) {
            fetchTestimonials(page);
        }
    };

    // Getter para páginas disponibles
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
        testimonials,
        testimonial,
        config,
        loading,
        error,
        filtros,
        pagination,

        // Getters
        testimonialsActivos,
        testimonialsInactivos,
        testimonialsOrdenados,
        totales,
        paginasDisponibles,

        // Actions
        fetchTestimonials,
        fetchTestimonial,
        fetchConfig,
        createTestimonial,
        updateTestimonial,
        deleteTestimonial,
        toggleStatus,
        saveConfig,
        resetFiltros,
        resetTestimonial,
        cambiarPagina,
    };
});
