interface Carrusel {
    id: number
    titulo: string
    subtitulo: string | null
    activar_subtitulo: boolean
    activar_boton: boolean
    url_boton: string | null
    texto_boton: string | null          // NUEVO
    color_boton: string | null          // NUEVO
    redirigir_misma_pagina: boolean
    posicion_contenido: 'Izquierda' | 'Derecha'
    imagen: string | null
    imagen_url: string | null
    estado: boolean
    created_at: string
    updated_at: string
    // Campos formateados (opcionales)
    texto_boton_formateado?: string     // NUEVO (opcional)
    color_boton_formateado?: string     // NUEVO (opcional)
}

export interface CarruselFormData {
    id?: number
    titulo: string
    subtitulo: string
    activar_subtitulo: boolean
    activar_boton: boolean
    url_boton: string
    redirigir_misma_pagina: boolean
    posicion_contenido: string
    imagen: File | null
    imagen_url?: string
    estado: boolean
}

export interface CarruselFilters {
    search: string
    estado: string
    per_page: number
    page: number
}

export interface CarruselPagination {
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
}

export interface CarruselApiResponse {
    success: boolean
    message: string
    data: Carrusel | Carrusel[]
    pagination?: CarruselPagination
    errors: any
}

export interface CarruselListResponse {
    success: boolean
    message: string
    data: Carrusel[]
    pagination: CarruselPagination
    errors: any
}

export type PosicionContenido = 'Izquierda' | 'Derecha'

export interface CarruselPosiciones {
    [key: string]: string
}
