// resources/js/src/types/entrega.ts

export interface User {
    id: number;
    name: string;
    email: string;
}

export interface Sector {
    id: number;
    nombre: string;
    ubicacion_principal: string;
    descripcion?: string;
    activo: boolean;
}

export interface DireccionEntrega {
    id: number;
    user_id: number;
    pedido_id: number;
    ubicacion: string;
    sector_id: number;
    sector?: Sector;
    barrio: string;
    calle_principal: string;
    calle_secundaria?: string;
    color_casa?: string;
    referencia?: string;
    instrucciones_especiales?: string;
    latitud?: number;
    longitud?: number;
    es_principal: boolean;
    activo: boolean;
    direccion_completa: string;
    resumen: string;
    created_at: string;
    updated_at: string;
}

export interface EntregaSector {
    id: number;
    pedido_id: number;
    sector_id: number;
    direccion_entrega_id: number;
    estado_entrega:
        | "pendiente"
        | "asignado"
        | "en_ruta"
        | "entregado"
        | "no_entregado";
    fecha_programada: string;
    fecha_entregada?: string;
    observaciones?: string;
    foto_entrega?: string;
    firma?: string;
    created_at: string;
    updated_at: string;

    // Relaciones
    pedido?: any;
    sector?: Sector;
    direccion?: DireccionEntrega;
    user?: User;
    historial?: HistorialEntrega[];

    // Accessors
    estado_label: string;
    color_estado: string;
}

export interface HistorialEntrega {
    id: number;
    entrega_sector_id: number;
    user_id: number;
    accion: "asignado" | "en_ruta" | "entregado" | "reprogramado";
    comentario?: string;
    datos_previos?: any;
    datos_nuevos?: any;
    created_at: string;

    // Relaciones
    user?: User;
    accion_label: string;
}

export interface ReporteFiltros {
    ubicacion?: string;
    sector_id?: number | string;
    fecha_inicio?: string;
    fecha_fin?: string;
}

export interface EstadisticasSector {
    sector_id: number;
    sector_nombre: string;
    total_entregas: number;
    entregas_completadas: number;
    entregas_pendientes: number;
    clientes_unicos: number;
}
