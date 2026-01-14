// src/types/banner.ts
export interface Banner {
    id: number;
    seccion: string;
    titulo: string | null;
    subtitulo: string | null;
    imagen_ruta: string;
    url_destino: string | null;
    estado: boolean;
    created_at: string | null;
    updated_at: string | null;
}

export interface BannerFormData {
    seccion: string;
    titulo?: string;
    subtitulo?: string;
    imagen?: File | null;
    url_destino?: string;
    estado: boolean;
}

// Opciones para las secciones
export const SECCIONES_BANNER = [
    { value: 'initial_header', label: 'Header Inicial (Home)' },
    { value: 'nosotros_header', label: 'Header Nosotros' },
    { value: 'publicidad_uno', label: 'Publicidad Uno' },
    { value: 'publicidad_dos', label: 'Publicidad Dos' },
    { value: 'panel_cuenta_uno', label: 'Panel Cuenta Uno' },
    { value: 'promocional_uno', label: 'Promocional Uno' },
    { value: 'promocional_dos', label: 'Promocional Dos' },
    { value: 'footer_banner', label: 'Banner Footer' },
] as const;