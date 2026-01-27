declare module 'vue-height-collapsible/vue3' {
    import { DefineComponent } from 'vue';
    
    const HeightCollapse: DefineComponent<{
        /** Duración de la animación en milisegundos */
        duration?: number;
        /** Si el componente está expandido inicialmente */
        open?: boolean;
        /** Altura máxima cuando está expandido */
        maxHeight?: string | number;
        /** Usar transición CSS nativa */
        useCss?: boolean;
        /** Clase CSS cuando está expandido */
        openClass?: string;
        /** Clase CSS cuando está colapsado */
        closeClass?: string;
        /** Emitir eventos de animación */
        emitEvents?: boolean;
    }, any, any>;
    
    export default HeightCollapse;
}
