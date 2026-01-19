<template>
    <div v-if="isVisible" class="modal show d-block" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i :class="titleIcon" class="me-2"></i>
                        {{ options?.title }}
                    </h5>
                    <button 
                        type="button" 
                        class="btn-close"
                        @click="cancel"
                        :disabled="loading"
                    ></button>
                </div>
                <div class="modal-body">
                    <p>{{ options?.message }}</p>
                </div>
                <div class="modal-footer">
                    <button 
                        type="button" 
                        class="btn btn-secondary"
                        @click="cancel"
                        :disabled="loading"
                    >
                        {{ options?.cancelText || 'Cancelar' }}
                    </button>
                    <button 
                        type="button" 
                        class="btn" 
                        :class="confirmButtonClass"
                        @click="confirm"
                        :disabled="loading"
                    >
                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                        <i v-else :class="confirmIcon" class="me-2"></i>
                        {{ options?.confirmText || 'Confirmar' }}
                    </button>
                </div>
            </div>
        </div>
        <div class="modal-backdrop show"></div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useConfirm } from '../../composables/useConfirm';

const { isVisible, options, loading, confirm, cancel } = useConfirm();

const titleIcon = computed(() => {
    const icons: Record<string, string> = {
        danger: 'fas fa-exclamation-triangle text-danger',
        warning: 'fas fa-exclamation-circle text-warning',
        info: 'fas fa-info-circle text-info',
        success: 'fas fa-check-circle text-success'
    };
    return icons[options.value?.type || 'danger'] || icons.danger;
});

const confirmButtonClass = computed(() => {
    const classes: Record<string, string> = {
        danger: 'btn-danger',
        warning: 'btn-warning',
        info: 'btn-info',
        success: 'btn-success'
    };
    return classes[options.value?.type || 'danger'] || 'btn-danger';
});

const confirmIcon = computed(() => {
    const icons: Record<string, string> = {
        danger: 'fas fa-trash',
        warning: 'fas fa-exclamation',
        info: 'fas fa-info',
        success: 'fas fa-check'
    };
    return icons[options.value?.type || 'danger'] || icons.danger;
});
</script>

<style scoped>
.modal {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>