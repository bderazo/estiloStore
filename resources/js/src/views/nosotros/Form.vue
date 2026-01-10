<template>
    <form class="space-y-6" @submit.prevent="handleSubmit">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="block mb-2 font-medium" for="nosotros-titulo">Título</label>
                <input
                    id="nosotros-titulo"
                    v-model="form.titulo"
                    type="text"
                    class="form-input"
                    :disabled="saving || isDisabled"
                    required
                    placeholder="Ingresa el título principal"
                />
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 font-medium" for="nosotros-cuerpo-principal">Cuerpo principal</label>
                <div class="border border-gray-200 dark:border-gray-600 rounded-md overflow-hidden">
                    <quillEditor
                        id="nosotros-cuerpo-principal"
                        v-model:value="form.cuerpo_principal"
                        :options="editorOptions"
                        :disabled="saving || isDisabled"
                        style="min-height: 220px"
                    />
                </div>
                <p class="mt-1 text-xs text-gray-500">Editor con formato enriquecido para el contenido principal.</p>
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 font-medium" for="nosotros-cuerpo-secundario">Cuerpo secundario</label>
                <div class="border border-gray-200 dark:border-gray-600 rounded-md overflow-hidden">
                    <quillEditor
                        id="nosotros-cuerpo-secundario"
                        v-model:value="form.cuerpo_secundario"
                        :options="editorOptionsSecondary"
                        :disabled="saving || isDisabled"
                        style="min-height: 180px"
                    />
                </div>
                <p class="mt-1 text-xs text-gray-500">Contenido adicional con formato enriquecido.</p>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div v-for="field in imageFields" :key="field.key" class="space-y-3">
                <div>
                    <label class="block mb-2 font-medium" :for="field.key">{{ field.label }}</label>
                    <div class="space-y-2">
                        <div v-if="previews[field.key]" class="overflow-hidden rounded-md border border-dashed border-gray-300 dark:border-gray-600">
                            <img :src="previews[field.key] ?? undefined" :alt="field.label" class="w-full h-48 object-cover" />
                        </div>
                        <input
                            :id="field.key"
                            type="file"
                            accept="image/*"
                            class="form-input"
                            :disabled="saving || isDisabled"
                            @change="onFileChange(field.key, $event)"
                        />
                        <div class="flex items-center gap-2">
                            <button
                                v-if="previews[field.key]"
                                type="button"
                                class="btn btn-outline-danger btn-sm"
                                :disabled="saving || isDisabled"
                                @click="clearImage(field.key)"
                            >
                                Quitar imagen
                            </button>
                            <p class="text-xs text-gray-500">{{ field.hint }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit" class="btn btn-primary" :disabled="saving || isDisabled">
                <span v-if="saving" class="inline-flex items-center gap-2">
                    <span class="animate-spin h-4 w-4 border-2 border-current border-t-transparent rounded-full"></span>
                    Guardando...
                </span>
                <span v-else>
                    Guardar cambios
                </span>
            </button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { reactive, watch, onBeforeUnmount, computed } from 'vue';
import { quillEditor } from 'vue3-quill';
// @ts-ignore: estilos globales del editor
import 'vue3-quill/lib/vue3-quill.css';
import type { Nosotros, NosotrosFormState, NosotrosImageField } from '../../types/nosotros';

const props = defineProps<{
    initialData: Nosotros | null;
    saving: boolean;
    disabled?: boolean;
}>();

const emit = defineEmits<{ (e: 'submit', payload: FormData): void }>();

const isDisabled = computed(() => props.disabled ?? false);

const form = reactive<NosotrosFormState>({
    titulo: '',
    cuerpo_principal: '',
    cuerpo_secundario: '',
    imagen_portada_url: null,
    imagen_cuerpo_1_url: null,
    imagen_cuerpo_2_url: null,
});

const previews = reactive<Record<NosotrosImageField, string | null>>({
    imagen_portada_url: null,
    imagen_cuerpo_1_url: null,
    imagen_cuerpo_2_url: null,
});

const objectUrls: Record<NosotrosImageField, string | null> = {
    imagen_portada_url: null,
    imagen_cuerpo_1_url: null,
    imagen_cuerpo_2_url: null,
};

const imageFields: Array<{ key: NosotrosImageField; label: string; hint: string }> = [
    {
        key: 'imagen_portada_url',
        label: 'Imagen de portada',
        hint: '1920 × 400 px',
    },
    {
        key: 'imagen_cuerpo_1_url',
        label: 'Imagen cuerpo 1',
        hint: '290 × 450 px',
    },
    {
        key: 'imagen_cuerpo_2_url',
        label: 'Imagen cuerpo 2',
        hint: '290 × 450 px',
    },
];

const editorOptions = computed(() => ({
    theme: 'snow',
    readOnly: props.saving || isDisabled.value,
    modules: {
        toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link', 'blockquote'],
            ['clean'],
        ],
    },
    placeholder: 'Descripción principal de la sección Nosotros',
}));

const editorOptionsSecondary = computed(() => ({
    ...editorOptions.value,
    placeholder: 'Contenido adicional para reforzar la historia de la marca',
}));

const revokeObjectUrl = (field: NosotrosImageField) => {
    if (objectUrls[field]) {
        URL.revokeObjectURL(objectUrls[field] as string);
        objectUrls[field] = null;
    }
};

watch(
    () => props.initialData,
    value => {
        if (!value) {
            form.titulo = '';
            form.cuerpo_principal = '';
            form.cuerpo_secundario = '';
            imageFields.forEach(field => {
                form[field.key] = null;
                previews[field.key] = null;
                revokeObjectUrl(field.key);
            });
            return;
        }

        form.titulo = value.titulo ?? '';
        form.cuerpo_principal = value.cuerpo_principal ?? '';
        form.cuerpo_secundario = value.cuerpo_secundario ?? '';

        imageFields.forEach(field => {
            form[field.key] = value[field.key] ?? null;
            previews[field.key] = value[field.key] ?? null;
            revokeObjectUrl(field.key);
        });
    },
    { immediate: true }
);

const onFileChange = (field: NosotrosImageField, event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files ? target.files[0] : undefined;

    revokeObjectUrl(field);

    if (file) {
        form[field] = file;
        const previewUrl = URL.createObjectURL(file);
        objectUrls[field] = previewUrl;
        previews[field] = previewUrl;
    } else {
        previews[field] = typeof form[field] === 'string' ? (form[field] as string) : null;
    }
};

const clearImage = (field: NosotrosImageField) => {
    revokeObjectUrl(field);
    form[field] = null;
    previews[field] = null;
};

const handleSubmit = () => {
    if (isDisabled.value) {
        return;
    }

    const payload = new FormData();
    payload.append('titulo', form.titulo.trim());
    payload.append('cuerpo_principal', form.cuerpo_principal ?? '');
    payload.append('cuerpo_secundario', form.cuerpo_secundario ?? '');

    imageFields.forEach(field => {
        const value = form[field.key];
        if (value instanceof File) {
            payload.append(field.key, value);
        } else if (value === null) {
            payload.append(field.key, '');
        }
    });

    emit('submit', payload);
};

onBeforeUnmount(() => {
    imageFields.forEach(field => revokeObjectUrl(field.key));
});
</script>
