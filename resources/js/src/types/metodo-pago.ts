export type TipoPago = 'Transferencia' | 'QR' | 'Efectivo' | 'Otro';

export interface MetodoPago {
    id: number;
    nombre_banco: string;
    tipo_pago: TipoPago;
    nombre_titular: string | null;
    numero_cuenta: string | null;
    tipo_cuenta: string | null;
    identificacion: string | null;
    instrucciones: string | null;
    imagen_qr: string | null;
    logo_banco: string | null;
    activo: boolean;
    created_at: string;
    updated_at: string;
    
    // Campos calculados
    imagen_qr_url?: string | null;
    logo_banco_url?: string | null;
    tipo_pago_label?: string;
    cuenta_formateada?: string | null;
}

export interface MetodoPagoForm {
    nombre_banco: string;
    tipo_pago: TipoPago;
    nombre_titular: string;
    numero_cuenta: string;
    tipo_cuenta: string;
    identificacion: string;
    instrucciones: string;
    imagen_qr: File | null;
    logo_banco: File | null;
    activo: boolean;
}

export interface FiltrosMetodoPago {
    search: string;
    tipo: TipoPago | '';
    activo: boolean | '';
    sort_by: string;
    sort_dir: 'asc' | 'desc';
    per_page: number;
}

export interface MetodoPagoStats {
    total: number;
    activos: number;
    inactivos: number;
    por_tipo: Record<TipoPago, number>;
}