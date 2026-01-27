import { ref } from 'vue';
import { useToast } from 'vue-toastification';

export interface ConfirmOptions {
    title: string;
    message: string;
    type?: 'danger' | 'warning' | 'info' | 'success';
    confirmText?: string;
    cancelText?: string;
    onConfirm: () => void | Promise<void>;
    onCancel?: () => void;
}

export function useConfirm() {
    const toast = useToast();
    const isVisible = ref(false);
    const options = ref<ConfirmOptions | null>(null);
    const loading = ref(false);

    const show = (opts: ConfirmOptions) => {
        options.value = opts;
        isVisible.value = true;
    };

    const hide = () => {
        isVisible.value = false;
        options.value = null;
        loading.value = false;
    };

    const confirm = async () => {
        if (!options.value) return;

        loading.value = true;
        try {
            await Promise.resolve(options.value.onConfirm());
            toast.success('Operación completada exitosamente');
            hide();
        } catch (error: any) {
            toast.error(error.message || 'Error al realizar la operación');
        } finally {
            loading.value = false;
        }
    };

    const cancel = () => {
        if (options.value?.onCancel) {
            options.value.onCancel();
        }
        hide();
    };

    return {
        isVisible,
        options,
        loading,
        show,
        hide,
        confirm,
        cancel
    };
}