import { defineStore } from 'pinia';
import nosotrosService from '../services/nosotrosService';
import { Nosotros, NosotrosStoreState } from '../types/nosotros';

export const useNosotrosStore = defineStore('nosotros', {
    state: (): NosotrosStoreState => ({
        record: null,
        loading: false,
        saving: false,
    }),

    getters: {
        hasRecord: (state): boolean => !!state.record,
    },

    actions: {
        async fetchNosotros(): Promise<Nosotros | null> {
            this.loading = true;
            try {
                const response = await nosotrosService.fetch();
                this.record = response.data;
                return this.record;
            } catch (error) {
                console.error('Error al obtener la información de Nosotros:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async saveNosotros(formData: FormData): Promise<Nosotros | null> {
            this.saving = true;
            try {
                const response = this.hasRecord
                    ? await nosotrosService.update(formData)
                    : await nosotrosService.create(formData);
                this.record = response.data;
                return this.record;
            } catch (error) {
                console.error('Error al guardar la información de Nosotros:', error);
                throw error;
            } finally {
                this.saving = false;
            }
        },
    },
});
