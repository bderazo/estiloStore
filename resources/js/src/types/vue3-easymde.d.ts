declare module 'vue3-easymde' {
    import { DefineComponent } from 'vue';
    
    export interface EasyMDEOptions {
        /** Elementos del menú de herramientas */
        toolbar?: string[] | boolean;
        /** Editor de solo lectura */
        readonly?: boolean;
        /** Espacio inicial */
        initialValue?: string;
        /** Estilo automático */
        autoDownloadFontAwesome?: boolean;
        /** Espacio para nombre de clase */
        placeholder?: string;
        /** Habilita/Deshabilita resaltado de sintaxis */
        renderingConfig?: {
            codeSyntaxHighlighting?: boolean;
        };
        /** Atajos de teclado */
        shortcuts?: {
            [key: string]: string;
        };
        /** Configuración de subida de imágenes */
        uploadImage?: boolean;
        imageMaxSize?: number;
        imageAccept?: string;
        /** Otras opciones de EasyMDE */
        [key: string]: any;
    }
    
    export interface EasyMDEInstance {
        value(): string;
        codemirror: any;
        options: EasyMDEOptions;
        clearAutosavedValue(): void;
        toTextArea(): void;
        isPreviewActive(): boolean;
        isSideBySideActive(): boolean;
        isFullscreenActive(): boolean;
    }
    
    const EasyMDE: DefineComponent<{
        /** Opciones del editor */
        options?: EasyMDEOptions;
        /** Modelo de valor (v-model) */
        modelValue?: string;
        /** Eventos personalizados */
        onChange?: (value: string) => void;
        onBlur?: () => void;
        onFocus?: () => void;
        onSave?: () => void;
    }>;
    
    export default EasyMDE;
}
