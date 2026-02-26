// resources/js/src/stores/entregaStore.ts
import { defineStore } from "pinia";
import { ref } from "vue";
import axios from "axios";
import type {
    DireccionEntrega,
    Sector,
    EntregaSector,
    ReporteFiltros,
} from "../types/entrega";

export const useEntregaStore = defineStore("entrega", () => {
    const direcciones = ref<DireccionEntrega[]>([]);
    const entregas = ref<EntregaSector[]>([]);
    const sectores = ref<Sector[]>([]);
    const loading = ref<boolean>(false);
    const error = ref<string | null>(null);

    const fetchSectores = async (ubicacion?: string) => {
        loading.value = true;
        try {
            const params = ubicacion ? { ubicacion } : {};
            const { data } = await axios.get<Sector[]>("/api/sectores", {
                params,
            });
            sectores.value = data;
            error.value = null;
        } catch (err: any) {
            error.value = err.message;
            console.error("Error fetching sectores:", err);
        } finally {
            loading.value = false;
        }
    };

    const fetchDirecciones = async (userId?: number) => {
        loading.value = true;
        try {
            const params = userId ? { user_id: userId } : {};
            const { data } = await axios.get<DireccionEntrega[]>(
                "/api/direcciones-entrega",
                { params },
            );
            direcciones.value = data;
            error.value = null;
        } catch (err: any) {
            error.value = err.message;
            console.error("Error fetching direcciones:", err);
        } finally {
            loading.value = false;
        }
    };

    const fetchEntregas = async (filtros?: ReporteFiltros) => {
        loading.value = true;
        try {
            const { data } = await axios.get<EntregaSector[]>(
                "/api/entregas-sectores",
                { params: filtros },
            );
            entregas.value = data;
            error.value = null;
        } catch (err: any) {
            error.value = err.message;
            console.error("Error fetching entregas:", err);
        } finally {
            loading.value = false;
        }
    };

    const crearDireccion = async (direccion: Partial<DireccionEntrega>) => {
        loading.value = true;
        try {
            const { data } = await axios.post<DireccionEntrega>(
                "/api/direcciones-entrega",
                direccion,
            );
            direcciones.value.push(data);
            error.value = null;
            return data;
        } catch (err: any) {
            error.value = err.message;
            console.error("Error creating direccion:", err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const actualizarDireccion = async (
        id: number,
        direccion: Partial<DireccionEntrega>,
    ) => {
        loading.value = true;
        try {
            const { data } = await axios.put<DireccionEntrega>(
                `/api/direcciones-entrega/${id}`,
                direccion,
            );
            const index = direcciones.value.findIndex((d) => d.id === id);
            if (index !== -1) {
                direcciones.value[index] = data;
            }
            error.value = null;
            return data;
        } catch (err: any) {
            error.value = err.message;
            console.error("Error updating direccion:", err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return {
        direcciones,
        entregas,
        sectores,
        loading,
        error,
        fetchSectores,
        fetchDirecciones,
        fetchEntregas,
        crearDireccion,
        actualizarDireccion,
    };
});
