import axios from 'axios';
import type {
    Articulo,
    ArticuloForm,
    ArticuloListResponse,
    ArticuloVarianteForm,
} from '@/types/articulo';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';

const instance = axios.create({
    baseURL: API_BASE_URL,
});

// Interceptor to add JWT token to requests
instance.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export const articuloService = {
    async getArticulos(
        page: number = 1,
        perPage: number = 10,
        search: string = '',
        categoriaId: string = '',
        marcaId: string = '',
        activo: string = ''
    ): Promise<ArticuloListResponse> {
        const response = await instance.get<ArticuloListResponse>('/articulos', {
            params: {
                page,
                per_page: perPage,
                search,
                categoria_id: categoriaId,
                marca_id: marcaId,
                activo,
            },
        });
        return response.data;
    },

    async getArticulo(id: number): Promise<Articulo> {
        const response = await instance.get<Articulo>(`/articulos/${id}`);
        console.log('API Response for getArticulo:', response.data);
        console.log('Returning:', response.data.data || response.data);
        return response.data.data || response.data;
    },

    async createArticulo(data: ArticuloForm): Promise<Articulo> {
        // Si hay imágenes, usar FormData
        if (data.imagenes && data.imagenes.length > 0) {
            const formData = new FormData();

            // Agregar todos los campos del artículo
            Object.entries(data).forEach(([key, value]) => {
                if (key === 'imagenes') {
                    // Agregar cada imagen
                    data.imagenes.forEach((imagen: File, index: number) => {
                        formData.append(`imagenes[${index}]`, imagen);
                    });
                } else if (key === 'variantes') {
                    // Convertir variantes a JSON string
                    formData.append('variantes', JSON.stringify(value));
                } else if (Array.isArray(value)) {
                    // Otros arrays como imagenes_eliminar
                    formData.append(key, JSON.stringify(value));
                } else if (typeof value === 'boolean') {
                    // Convertir booleanos a números para FormData
                    formData.append(key, value ? '1' : '0');
                } else if (value !== null && value !== undefined) {
                    formData.append(key, String(value));
                }
            });

            const response = await instance.post<Articulo>('/articulos', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            return response.data.data;
        } else {
            const response = await instance.post<Articulo>('/articulos', data);
            return response.data.data;
        }
    },

    async updateArticulo(id: number, data: ArticuloForm): Promise<Articulo> {
        // Si hay imágenes, usar FormData
        if (data.imagenes && data.imagenes.length > 0) {
            const formData = new FormData();

            // Agregar método PUT para Laravel
            formData.append('_method', 'PUT');

            // Agregar todos los campos del artículo
            Object.entries(data).forEach(([key, value]) => {
                if (key === 'imagenes') {
                    // Agregar cada imagen
                    data.imagenes.forEach((imagen: File, index: number) => {
                        formData.append(`imagenes[${index}]`, imagen);
                    });
                } else if (key === 'variantes') {
                    // Convertir variantes a JSON string
                    formData.append('variantes', JSON.stringify(value));
                } else if (Array.isArray(value)) {
                    // Otros arrays como imagenes_eliminar, variantes_eliminar
                    formData.append(key, JSON.stringify(value));
                } else if (typeof value === 'boolean') {
                    // Convertir booleanos a números para FormData
                    formData.append(key, value ? '1' : '0');
                } else if (value !== null && value !== undefined) {
                    formData.append(key, String(value));
                }
            });

            const response = await instance.post<Articulo>(`/articulos/${id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            return response.data.data;
        } else {
            const response = await instance.put<Articulo>(`/articulos/${id}`, data);
            return response.data.data;
        }
    },

    async deleteArticulo(id: number): Promise<void> {
        await instance.delete(`/articulos/${id}`);
    },

    async toggleActivo(id: number): Promise<Articulo> {
        const response = await instance.patch<Articulo>(`/articulos/${id}/toggle-activo`);
        return response.data.data;
    },

    async toggleDestacado(id: number): Promise<Articulo> {
        const response = await instance.patch<Articulo>(`/articulos/${id}/toggle-destacado`);
        return response.data.data;
    },

    async uploadImage(articuloId: number, file: File, ordem: number, principal: boolean) {
        const formData = new FormData();
        formData.append('imagen', file);
        formData.append('ordem', ordem.toString());
        formData.append('principal', principal.toString());

        const response = await instance.post(
            `/articulos/${articuloId}/imagenes`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }
        );
        return response.data;
    },

    async deleteImage(articuloId: number, imagenId: number): Promise<void> {
        await instance.delete(`/articulos/${articuloId}/imagenes/${imagenId}`);
    },

    async createVariante(
        articuloId: number,
        data: ArticuloVarianteForm
    ): Promise<any> {
        const response = await instance.post(
            `/articulos/${articuloId}/variantes`,
            data
        );
        return response.data;
    },

    async updateVariante(
        articuloId: number,
        varianteId: number,
        data: ArticuloVarianteForm
    ): Promise<any> {
        const response = await instance.put(
            `/articulos/${articuloId}/variantes/${varianteId}`,
            data
        );
        return response.data;
    },

    async deleteVariante(articuloId: number, varianteId: number): Promise<void> {
        await instance.delete(`/articulos/${articuloId}/variantes/${varianteId}`);
    },

    async reservarStock(varianteId: number, cantidad: number): Promise<any> {
        const response = await instance.post(
            `/articulos/variantes/${varianteId}/reservar-stock`,
            { cantidad }
        );
        return response.data;
    },

    async liberarStock(varianteId: number, cantidad: number): Promise<any> {
        const response = await instance.post(
            `/articulos/variantes/${varianteId}/liberar-stock`,
            { cantidad }
        );
        return response.data;
    },

    async decrementarStock(varianteId: number, cantidad: number): Promise<any> {
        const response = await instance.post(
            `/articulos/variantes/${varianteId}/decrementar-stock`,
            { cantidad }
        );
        return response.data;
    },

    async getArticulosForSelect() {
        const response = await instance.get('/articulos/for-select');
        return response.data.data;
    },

    async getCategoriasForSelect() {
        const response = await instance.get('/categorias/for-select');
        return response.data.data;
    },

    async getMarcasForSelect() {
        const response = await instance.get('/marcas/for-select');
        return response.data.data;
    },

    async getColoresForSelect() {
        const response = await instance.get('/colores/for-select');
        return response.data.data;
    },

    async getTallasForSelect() {
        const response = await instance.get('/tallas/for-select');
        return response.data.data;
    },

    async getPlazasForSelect() {
        const response = await instance.get('/plazas/for-select');
        return response.data.data;
    },

    async getMedidasForSelect() {
        const response = await instance.get('/medidas/for-select');
        return response.data.data;
    },

    async getSaboresForSelect() {
        const response = await instance.get('/sabores/for-select');
        return response.data.data;
    },

    async getModelosForSelect() {
        const response = await instance.get('/modelos/for-select');
        return response.data.data;
    },

    async getTonosForSelect() {
        const response = await instance.get('/tonos/for-select');
        return response.data.data;
    }
};
