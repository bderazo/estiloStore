import { defineStore } from "pinia";
import { ref, computed } from "vue";
import type {
    VideoExito,
    VideoExitoFormData,
    VideoExitoConfig,
    FiltrosVideoExito,
} from "../types/videoExito";
import api from "../services/api";
import { useToast } from "vue-toastification";

export const useVideoExitoStore = defineStore("videoExito", () => {
    const toast = useToast();

    // State
    const videos = ref<VideoExito[]>([]);
    const video = ref<VideoExito | null>(null);
    const config = ref<VideoExitoConfig>({
        titulo: "Casos de Éxito",
        subtitulo: "Historias inspiradoras de nuestras clientas",
        videos_por_pagina: 8,
        mostrar_cargar_mas: true,
    });
    const loading = ref(false);
    const error = ref<string | null>(null);
    const filtros = ref<FiltrosVideoExito>({
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
    const videosActivos = computed(() => videos.value.filter((v) => v.activo));

    const videosInactivos = computed(() =>
        videos.value.filter((v) => !v.activo),
    );

    const videosOrdenados = computed(() =>
        [...videos.value].sort((a, b) => a.orden - b.orden),
    );

    const videosDestacados = computed(() =>
        videos.value.filter((v) => v.activo).slice(0, 3),
    );

    const totales = computed(() => {
        return {
            total: videos.value.length,
            activos: videosActivos.value.length,
            inactivos: videosInactivos.value.length,
        };
    });

    // Actions
    const fetchVideos = async (page = 1) => {
        loading.value = true;
        error.value = null;

        try {
            console.log("=== INICIANDO fetchVideos ===");
            console.log("Filtros:", filtros.value);
            console.log("Página solicitada:", page);

            const queryParams = {
                ...filtros.value,
                page: page,
            };

            const response = await api.get("/videos-exito", {
                params: queryParams,
            });

            console.log("Respuesta del backend:", response);

            // Manejar diferentes estructuras de respuesta
            let videosData: VideoExito[] = [];
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
                videosData = response.data.data;

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
                } else if (response.data.pagination) {
                    paginationData = response.data.pagination;
                }
            }
            // Opción 2: Array directo
            else if (Array.isArray(response.data)) {
                videosData = response.data;
                paginationData = {
                    current_page: 1,
                    per_page: filtros.value.per_page || 15,
                    total: videosData.length,
                    last_page: 1,
                    from: 1,
                    to: videosData.length,
                };
            }
            // Opción 3: Estructura con datos anidados
            else if (
                response.data.data &&
                response.data.data.data &&
                Array.isArray(response.data.data.data)
            ) {
                videosData = response.data.data.data;

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
            // Opción 4: Respuesta con success/data
            else if (response.data.success && response.data.data) {
                if (Array.isArray(response.data.data)) {
                    videosData = response.data.data;
                } else if (response.data.data.data) {
                    videosData = response.data.data.data;
                    paginationData =
                        response.data.data.meta ||
                        response.data.data.pagination ||
                        paginationData;
                }
            }

            console.log("Datos extraídos:", videosData);
            console.log("Paginación:", paginationData);

            if (videosData.length > 0 || response.data) {
                videos.value = videosData;
                pagination.value = paginationData;
            } else {
                videos.value = [];
            }
        } catch (err: any) {
            console.error("❌ ERROR en fetchVideos:", err);

            if (err.response) {
                if (err.response.status === 401) {
                    error.value = "No autorizado. Inicie sesión nuevamente.";
                    toast.error(error.value);
                } else if (err.response.status === 403) {
                    error.value = "No tiene permisos para ver videos";
                    toast.error(error.value);
                } else {
                    error.value =
                        err.response?.data?.message || "Error al cargar videos";
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
            console.log("=== FINALIZANDO fetchVideos ===");
        }
    };

    const fetchVideo = async (id: number) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.get(`/videos-exito/${id}`);
            video.value = response.data.data || response.data;
            return video.value;
        } catch (err: any) {
            error.value =
                err.response?.data?.message || "Error al cargar video";
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const fetchConfig = async () => {
        try {
            const response = await api.get("/videos-exito/config");

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
            toast.error("Error al cargar configuración");
            throw err;
        }
    };

    const createVideo = async (formData: FormData) => {
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

            const response = await api.post("/videos-exito", formData, config);
            console.log("Respuesta recibida:", response.data);

            const newVideo = response.data.data || response.data;
            videos.value.unshift(newVideo);

            // Actualizar total en paginación
            pagination.value.total += 1;

            toast.success(response.data.message || "Video creado exitosamente");
            return newVideo;
        } catch (err: any) {
            console.error("Error en createVideo:", err);

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
                error.value = "Error desconocido al crear video";
                toast.error(error.value);
            }

            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateVideo = async (id: number, formData: FormData) => {
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
                `/videos-exito/${id}`,
                formData,
                config,
            );

            const index = videos.value.findIndex((v) => v.id === id);
            if (index !== -1) {
                videos.value[index] = response.data.data || response.data;
            }

            toast.success(
                response.data.message || "Video actualizado exitosamente",
            );
            return response.data.data || response.data;
        } catch (err: any) {
            console.error("Error en updateVideo:", err);
            error.value =
                err.response?.data?.message || "Error al actualizar video";
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteVideo = async (id: number) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.delete(`/videos-exito/${id}`);

            videos.value = videos.value.filter((v) => v.id !== id);

            // Actualizar paginación
            pagination.value.total -= 1;

            toast.success(
                response.data.message || "Video eliminado exitosamente",
            );
            return true;
        } catch (err: any) {
            error.value =
                err.response?.data?.message || "Error al eliminar video";
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
            const response = await api.patch(`/videos-exito/${id}/toggle`);

            const index = videos.value.findIndex((v) => v.id === id);
            if (index !== -1) {
                videos.value[index] = {
                    ...videos.value[index],
                    activo: response.data.activo ?? !videos.value[index].activo,
                };
            }

            toast.success(
                response.data.message || "Estado cambiado exitosamente",
            );
            return response.data;
        } catch (err: any) {
            error.value =
                err.response?.data?.message || "Error al cambiar estado";
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const saveConfig = async (configData: VideoExitoConfig) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.post("/videos-exito/config", configData);

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
            error.value =
                err.response?.data?.message || "Error al guardar configuración";
            toast.error(error.value);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const reorderVideos = async (orders: { id: number; orden: number }[]) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.post("/videos-exito/reorder", {
                orders,
            });

            // Actualizar órdenes localmente
            orders.forEach((order) => {
                const video = videos.value.find((v) => v.id === order.id);
                if (video) {
                    video.orden = order.orden;
                }
            });

            // Reordenar el array
            videos.value.sort((a, b) => a.orden - b.orden);

            toast.success(
                response.data.message || "Orden actualizado exitosamente",
            );
            return true;
        } catch (err: any) {
            error.value =
                err.response?.data?.message || "Error al reordenar videos";
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

    const resetVideo = () => {
        video.value = null;
    };

    const cambiarPagina = (page: number) => {
        if (page >= 1 && page <= pagination.value.last_page) {
            fetchVideos(page);
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
        videos,
        video,
        config,
        loading,
        error,
        filtros,
        pagination,

        // Getters
        videosActivos,
        videosInactivos,
        videosOrdenados,
        videosDestacados,
        totales,
        paginasDisponibles,

        // Actions
        fetchVideos,
        fetchVideo,
        fetchConfig,
        createVideo,
        updateVideo,
        deleteVideo,
        toggleStatus,
        saveConfig,
        reorderVideos,
        resetFiltros,
        resetVideo,
        cambiarPagina,
    };
});
