// Tipos para el dashboard

export interface VentaMensual {
    mes: string;
    mes_nombre: string;
    total: number;
    pedidos: number;
    reservas: number;
}

export interface Emprendedora {
    categoria: string;
    cantidad: number;
    porcentaje: number;
    color: string;
}

export interface OrdenReciente {
    id: number;
    codigo_reserva: string;
    total: number;
    estado: string;
    created_at: string;
    cliente_nombre: string;
    cliente_email: string;
    fecha_formateada?: string;
    color_estado: string;
}

export interface TopProducto {
    articulo_id: number;
    producto_nombre: string;
    imagen_principal?: string;
    total_vendido: number;
    total_ventas: number;
    precio_promedio: number;
    pedidos_totales: number;
    categoria?: string;
    fuente?: string;
    color_fuente?: string;
}

export interface DashboardData {
    ventas_mensuales: VentaMensual[];
    ventas_totales: number;
    ventas_mes_actual: number;
    emprendedoras: Emprendedora[];
    total_emprendedoras: number;
    nuevas_emprendedoras: number;
    total_pedidos: number;
    pedidos_este_mes: number;
    pedidos_crecimiento: number;
    ordenes_recientes: OrdenReciente[];
    top_productos: TopProducto[];
    total_productos: number;
}