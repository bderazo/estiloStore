export interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    created_at: string;
}

export interface Transporte {
    id: number;
    cooperativa: string;
    ruta: string;
    precio: number;
    tiempo_estimado?: string;
}

export interface PedidoPago {
    id: number;
    monto: number;
    comprobante_ruta: string;
    estado: 'pendiente' | 'aprobado' | 'rechazado';
    observacion?: string;
    created_at: string;
    updated_at: string;
}

export interface PedidoDetalle {
    id: number;
    cantidad: number;
    precio_unitario: number;
    variante?: {
        id: number;
        articulo?: {
            id: number;
            nombre: string;
        };
        color?: {
            id: number;
            nombre: string;
        };
        talla?: {
            id: number;
            nombre: string;
        };
    };
}

export interface Pedido {
    id: number;
    codigo_reserva: string;
    total: number;
    costo_envio: number;
    estado: 'pendiente' | 'abonado' | 'completado' | 'rechazado' | 'cancelado';
    created_at: string;
    updated_at: string;
    user?: User;
    transporte?: Transporte;
    pagos?: PedidoPago[];
    detalles?: PedidoDetalle[];
}