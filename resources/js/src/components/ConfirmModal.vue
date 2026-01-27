<template>
    <div v-if="isVisible" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i v-if="options?.type === 'danger'" class="fas fa-exclamation-triangle text-danger me-2"></i>
                    <i v-if="options?.type === 'warning'" class="fas fa-exclamation-circle text-warning me-2"></i>
                    <i v-if="options?.type === 'info'" class="fas fa-info-circle text-info me-2"></i>
                    <i v-if="options?.type === 'success'" class="fas fa-check-circle text-success me-2"></i>
                    {{ options?.title }}
                </h5>
                <button type="button" class="btn-close" @click="cancel" :disabled="loading"></button>
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
                    :class="{
                        'btn-danger': options?.type === 'danger',
                        'btn-warning': options?.type === 'warning',
                        'btn-info': options?.type === 'info',
                        'btn-success': options?.type === 'success',
                        'btn-primary': !options?.type
                    }"
                    @click="confirm"
                    :disabled="loading"
                >
                    <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                    {{ options?.confirmText || 'Confirmar' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import type { ConfirmOptions } from '../../src/composables/useConfirm';

defineProps<{
    isVisible: boolean;
    options: ConfirmOptions | null;
    loading: boolean;
}>();

const emit = defineEmits<{
    confirm: [];
    cancel: [];
}>();

const confirm = () => {
    emit('confirm');
};

const cancel = () => {
    emit('cancel');
};
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
}

.modal-content {
    background-color: white;
    border-radius: 0.5rem;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    opacity: 0.5;
    cursor: pointer;
}

.btn-close:hover {
    opacity: 1;
}

.btn-close:disabled {
    opacity: 0.25;
    cursor: not-allowed;
}

.btn {
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
}

.btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-primary {
    background-color: #0d6efd;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-warning {
    background-color: #ffc107;
    color: #000;
}

.btn-info {
    background-color: #0dcaf0;
    color: #000;
}

.btn-success {
    background-color: #198754;
    color: white;
}
</style>