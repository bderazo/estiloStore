declare module 'vue3-json-excel' {
    import { DefineComponent } from 'vue';
    
    export interface JsonExcelProps {
        /** Datos a exportar */
        data: any[];
        /** Nombre del archivo */
        filename?: string;
        /** Campos a exportar (si no se especifica, se exportan todos) */
        fields?: any;
        /** Tipo de archivo */
        type?: string;
        /** Encabezados personalizados */
        header?: string[] | any;
        /** Clase CSS */
        class?: string;
        /** Nombre del botón */
        name?: string;
        /** Función para procesar datos antes de exportar */
        fetch?: () => Promise<any[]>;
        /** Función para transformar datos */
        transform?: (data: any) => any;
        /** Método para obtener campos dinámicamente */
        meta?: any[];
        /** Opciones adicionales */
        worksheet?: string;
        /** Especificar qué campos exportar */
        exportFields?: any;
        /** Hora de espera para fetch */
        timeout?: number;
        /** Mostrar/ocultar botón */
        show?: boolean;
        /** Etiqueta del botón */
        label?: string;
        /** Icono del botón */
        icon?: string;
        /** Imagen del botón */
        img?: string;
        /** Atributos adicionales del botón */
        buttonOptions?: {
            [key: string]: any;
        };
    }
    
    const JsonExcel: DefineComponent<JsonExcelProps>;
    
    export default JsonExcel;
}
