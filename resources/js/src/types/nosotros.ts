export interface Nosotros {
    id: number;
    titulo: string;
    cuerpo_principal: string | null;
    cuerpo_secundario: string | null;
    imagen_portada_url: string | null;
    imagen_cuerpo_1_url: string | null;
    imagen_cuerpo_2_url: string | null;
    created_at: string | null;
    updated_at: string | null;
    formatted_created_at: string | null;
    formatted_updated_at: string | null;
    slug: string | null;
}

export interface NosotrosResponse {
    data: Nosotros | null;
}

export interface UpdateNosotrosPayload {
    titulo: string;
    cuerpo_principal: string | null;
    cuerpo_secundario: string | null;
}

export type NosotrosImageField = 'imagen_portada_url' | 'imagen_cuerpo_1_url' | 'imagen_cuerpo_2_url';

export interface NosotrosFormState {
    titulo: string;
    cuerpo_principal: string;
    cuerpo_secundario: string;
    imagen_portada_url: File | string | null;
    imagen_cuerpo_1_url: File | string | null;
    imagen_cuerpo_2_url: File | string | null;
}

export interface NosotrosStoreState {
    record: Nosotros | null;
    loading: boolean;
    saving: boolean;
}
