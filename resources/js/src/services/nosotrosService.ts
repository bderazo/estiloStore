import api from './api';
import type { NosotrosResponse } from '../types/nosotros';

class NosotrosService {
    private readonly baseUrl = '/nosotros';

    async fetch(): Promise<NosotrosResponse> {
        const response = await api.get(this.baseUrl);
        return response.data;
    }

    async create(formData: FormData): Promise<NosotrosResponse> {
        const response = await api.post(this.baseUrl, formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        return response.data;
    }

    async update(formData: FormData): Promise<NosotrosResponse> {
        formData.append('_method', 'PUT');

        const response = await api.post(this.baseUrl, formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        return response.data;
    }
}

export default new NosotrosService();
