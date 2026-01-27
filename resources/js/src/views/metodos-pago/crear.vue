<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        <i class="fas fa-plus-circle mr-2"></i>Nuevo Método de Pago
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Configura un nuevo método de pago para que los clientes puedan realizar sus pagos
                    </p>
                </div>
                <router-link
                    to="/administrador/metodos-pago"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </router-link>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Formulario -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="p-6">
                            <!-- Mensajes de Error -->
                            <div v-if="Object.keys(errors).length > 0" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-red-700 dark:text-red-400">
                                            Por favor corrige los siguientes errores:
                                        </p>
                                        <ul class="mt-2 text-sm text-red-600 dark:text-red-300 list-disc list-inside">
                                            <li v-for="(errorList, field) in errors" :key="field">
                                                {{ errorList[0] }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <form @submit.prevent="submitForm">
                                <!-- Tipo de Pago -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tipo de Pago <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        v-model="form.tipo_pago"
                                        @change="onTipoPagoChange"
                                        :class="[
                                            'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                            errors.tipo_pago ? 'border-red-300 dark:border-red-700' : ''
                                        ]"
                                    >
                                        <option value="">Seleccione un tipo</option>
                                        <option value="Transferencia">Transferencia Bancaria</option>
                                        <option value="QR">Pago con QR</option>
                                        <option value="Efectivo">Pago en Efectivo</option>
                                        <option value="Otro">Otro Método</option>
                                    </select>
                                </div>

                                <!-- Nombre Banco -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nombre del Banco / Método <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.nombre_banco"
                                        type="text"
                                        :class="[
                                            'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                            errors.nombre_banco ? 'border-red-300 dark:border-red-700' : ''
                                        ]"
                                        placeholder="Ej: Banco Pichincha, PayPal, etc."
                                    >
                                </div>

                                <!-- Logo Banco -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Logo del Banco
                                    </label>
                                    <div
                                        @dragover="handleDragOverLogo"
                                        @dragleave="handleDragLeaveLogo"
                                        @drop="handleDropLogo"
                                        :class="[
                                            'border-2 border-dashed rounded-lg p-6 text-center transition-all duration-200',
                                            isDraggingLogo 
                                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' 
                                                : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500',
                                            errors.logo_banco ? 'border-red-300 dark:border-red-700' : ''
                                        ]"
                                    >
                                        <input
                                            ref="logoInput"
                                            type="file"
                                            accept="image/*"
                                            @change="onLogoChange"
                                            class="hidden"
                                        >
                                        
                                        <div v-if="!preview.logo">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                <span 
                                                    class="font-medium text-blue-600 dark:text-blue-400 cursor-pointer" 
                                                    @click="openFilePicker('logo')"
                                                >
                                                    Haz clic para subir
                                                </span>
                                                o arrastra y suelta tu archivo
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Formatos: PNG, JPG, SVG (máximo 2MB)
                                            </p>
                                        </div>
                                        
                                        <div v-else>
                                            <div class="flex flex-col items-center">
                                                <img :src="preview.logo" alt="Logo preview" class="h-32 w-auto object-contain rounded-lg">
                                                <div class="mt-4 text-center">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-xs">
                                                        {{ form.logo_banco?.name || 'Logo seleccionado' }}
                                                    </p>
                                                    <div class="mt-4 flex items-center justify-center space-x-3">
                                                        <button
                                                            type="button"
                                                            @click="openFilePicker('logo')"
                                                            class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                                        >
                                                            Cambiar imagen
                                                        </button>
                                                        <button
                                                            type="button"
                                                            @click="removeLogo"
                                                            class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                                        >
                                                            Eliminar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos para Transferencias -->
                                <div v-if="form.tipo_pago === 'Transferencia'" class="mb-6 p-4 border-l-4 border-blue-500 bg-blue-50/50 dark:bg-blue-900/10 rounded-r-lg">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                        <i class="fas fa-university mr-2"></i>Datos de Transferencia
                                    </h3>
                                    
                                    <!-- Nombre Titular -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Nombre del Titular <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            v-model="form.nombre_titular"
                                            type="text"
                                            :class="[
                                                'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                                errors.nombre_titular ? 'border-red-300 dark:border-red-700' : ''
                                            ]"
                                            placeholder="Nombre completo del titular"
                                        >
                                    </div>

                                    <!-- Identificación y Tipo de Cuenta -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Identificación
                                            </label>
                                            <input
                                                v-model="form.identificacion"
                                                type="text"
                                                v-maska="'###########'"
                                                :class="[
                                                    'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                                    errors.identificacion ? 'border-red-300 dark:border-red-700' : ''
                                                ]"
                                                placeholder="Cédula, RUC, etc."
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Tipo de Cuenta <span class="text-red-500">*</span>
                                            </label>
                                            <select 
                                                v-model="form.tipo_cuenta"
                                                :class="[
                                                    'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                                    errors.tipo_cuenta ? 'border-red-300 dark:border-red-700' : ''
                                                ]"
                                            >
                                                <option value="">Seleccione tipo</option>
                                                <option value="Ahorros">Ahorros</option>
                                                <option value="Corriente">Corriente</option>
                                                <option value="Vista">Vista</option>
                                                <option value="Jurídica">Jurídica</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Número de Cuenta -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Número de Cuenta <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            v-model="form.numero_cuenta"
                                            type="text"
                                            :class="[
                                                'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                                errors.numero_cuenta ? 'border-red-300 dark:border-red-700' : ''
                                            ]"
                                            placeholder="Número de cuenta bancaria"
                                        >
                                    </div>
                                </div>

                                <!-- QR Image -->
                                <div v-if="form.tipo_pago === 'QR'" class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Imagen QR <span class="text-red-500">*</span>
                                    </label>
                                    <div
                                        @dragover="handleDragOverQr"
                                        @dragleave="handleDragLeaveQr"
                                        @drop="handleDropQr"
                                        :class="[
                                            'border-2 border-dashed rounded-lg p-6 text-center transition-all duration-200',
                                            isDraggingQr 
                                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' 
                                                : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500',
                                            errors.imagen_qr ? 'border-red-300 dark:border-red-700' : ''
                                        ]"
                                    >
                                        <input
                                            ref="qrInput"
                                            type="file"
                                            accept="image/*"
                                            @change="onQrChange"
                                            class="hidden"
                                        >
                                        
                                        <div v-if="!preview.qr">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                <span 
                                                    class="font-medium text-blue-600 dark:text-blue-400 cursor-pointer" 
                                                    @click="openFilePicker('qr')"
                                                >
                                                    Haz clic para subir
                                                </span>
                                                o arrastra y suelta tu archivo
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Suba una imagen clara del código QR
                                            </p>
                                        </div>
                                        
                                        <div v-else>
                                            <div class="flex flex-col items-center">
                                                <img :src="preview.qr" alt="QR preview" class="h-48 w-48 object-contain rounded-lg border-4 border-white dark:border-gray-700">
                                                <div class="mt-4 text-center">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        Imagen QR seleccionada
                                                    </p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        Escanee para probar el código
                                                    </p>
                                                    <div class="mt-4 flex items-center justify-center space-x-3">
                                                        <button
                                                            type="button"
                                                            @click="openFilePicker('qr')"
                                                            class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                                        >
                                                            Cambiar imagen
                                                        </button>
                                                        <button
                                                            type="button"
                                                            @click="removeQr"
                                                            class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                                        >
                                                            Eliminar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Instrucciones -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Instrucciones para el Cliente
                                    </label>
                                    <textarea
                                        v-model="form.instrucciones"
                                        rows="3"
                                        :class="[
                                            'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition resize-none',
                                            errors.instrucciones ? 'border-red-300 dark:border-red-700' : ''
                                        ]"
                                        placeholder="Instrucciones detalladas para realizar el pago..."
                                    ></textarea>
                                    <div class="mt-1 flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                        <p>Estas instrucciones serán visibles para los clientes.</p>
                                        <span>{{ form.instrucciones.length }}/1000</span>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="mb-8">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Estado del Método de Pago
                                            </label>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Los métodos inactivos no estarán disponibles para los clientes.
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            @click="form.activo = !form.activo"
                                            :class="[
                                                'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                                                form.activo ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                                    form.activo ? 'translate-x-5' : 'translate-x-0'
                                                ]"
                                            />
                                        </button>
                                    </div>
                                    <div class="mt-2 flex items-center">
                                        <span 
                                            :class="[
                                                'px-2 py-1 text-xs font-medium rounded-full',
                                                form.activo 
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                            ]"
                                        >
                                            {{ form.activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                        <p class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            {{ form.activo ? 'Los clientes podrán seleccionar este método.' : 'Este método estará oculto para los clientes.' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="flex items-center justify-end space-x-4 pt-6 border-t dark:border-gray-700">
                                    <router-link
                                        to="/administrador/metodos-pago"
                                        class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition"
                                    >
                                        Cancelar
                                    </router-link>
                                    <button
                                        type="submit"
                                        :disabled="loading || !isFormValid"
                                        :class="[
                                            'px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition',
                                            (loading || !isFormValid) 
                                                ? 'opacity-50 cursor-not-allowed' 
                                                : ''
                                        ]"
                                    >
                                        <span v-if="loading" class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Guardando...
                                        </span>
                                        <span v-else class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Guardar Método
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel Derecho -->
                <div class="space-y-6">
                    <!-- Vista Previa -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                Vista Previa
                            </h3>
                            
                            <div class="metodo-pago-preview">
                                <div v-if="form.tipo_pago" class="space-y-4">
                                    <!-- Logo y Nombre -->
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div v-if="preview.logo" class="flex-shrink-0">
                                            <img :src="preview.logo" alt="Logo" class="h-10 w-10 object-contain">
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                {{ form.nombre_banco || 'Nombre no especificado' }}
                                            </h4>
                                            <span 
                                                class="inline-block px-2 py-1 text-xs font-medium rounded-full"
                                                :class="previewBadgeClass"
                                            >
                                                {{ tipoPagoLabel }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Datos de Transferencia -->
                                    <div v-if="form.tipo_pago === 'Transferencia'" class="space-y-3 p-3 bg-blue-50/50 dark:bg-blue-900/10 rounded-lg">
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">
                                            <i class="fas fa-university mr-1"></i> Datos Bancarios
                                        </h5>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Titular:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ form.nombre_titular || 'No especificado' }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Cuenta:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ form.numero_cuenta || 'No especificado' }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Tipo:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ form.tipo_cuenta || 'No especificado' }}
                                                </span>
                                            </div>
                                            <div v-if="form.identificacion" class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Identificación:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ form.identificacion }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- QR Preview -->
                                    <div v-if="form.tipo_pago === 'QR' && preview.qr" class="p-3 bg-yellow-50/50 dark:bg-yellow-900/10 rounded-lg">
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                            <i class="fas fa-qrcode mr-1"></i> Código QR
                                        </h5>
                                        <div class="flex flex-col items-center">
                                            <img :src="preview.qr" alt="QR" class="h-32 w-32 object-contain border-4 border-white dark:border-gray-700 rounded-lg">
                                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                Escanee para probar el pago
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Instrucciones -->
                                    <div v-if="form.instrucciones" class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                            <i class="fas fa-info-circle mr-1"></i> Instrucciones
                                        </h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">
                                            {{ form.instrucciones }}
                                        </p>
                                    </div>

                                    <!-- Estado -->
                                    <div class="pt-3 border-t dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Estado:</span>
                                            <span 
                                                class="px-2 py-1 text-xs font-medium rounded-full"
                                                :class="form.activo 
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'"
                                            >
                                                {{ form.activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Complete el formulario para ver la vista previa
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recomendaciones -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-lightbulb mr-2"></i>Recomendaciones
                            </h3>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Use logos en formato PNG con fondo transparente
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        QR debe ser claro y fácil de escanear
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Incluya instrucciones claras para el cliente
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Revise que los datos bancarios sean correctos
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useMetodoPagoStore } from '../../stores/metodoPagoStore';
import { vMaska } from 'maska/vue';
import type { MetodoPagoForm } from '../../types/metodo-pago';
import { useToast } from 'vue-toastification';

const router = useRouter();
const store = useMetodoPagoStore();
const toast = useToast();

// Refs
const logoInput = ref<HTMLInputElement>();
const qrInput = ref<HTMLInputElement>();
const loading = ref(false);
const errors = ref<Record<string, string[]>>({});
const isDraggingLogo = ref(false);
const isDraggingQr = ref(false);

// Form
const form = reactive<MetodoPagoForm & { tipo_pago: string }>({
    nombre_banco: '',
    tipo_pago: '' as any,
    nombre_titular: '',
    numero_cuenta: '',
    tipo_cuenta: '',
    identificacion: '',
    instrucciones: '',
    imagen_qr: null,
    logo_banco: null,
    activo: true,
});

// Preview
const preview = reactive({
    logo: null as string | null,
    qr: null as string | null
});

// Computed
const tipoPagoLabel = computed(() => {
    const labels: Record<string, string> = {
        Transferencia: 'Transferencia Bancaria',
        QR: 'Pago QR',
        Efectivo: 'Efectivo',
        Otro: 'Otro Método'
    };
    return labels[form.tipo_pago] || form.tipo_pago;
});

const previewBadgeClass = computed(() => {
    const classes: Record<string, string> = {
        Transferencia: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        QR: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        Efectivo: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        Otro: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
    };
    return classes[form.tipo_pago] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
});

const isFormValid = computed(() => {
    return form.nombre_banco.trim() !== '' && 
           (form.tipo_pago !== 'Transferencia' || (
               form.nombre_titular.trim() !== '' &&
               form.numero_cuenta.trim() !== '' &&
               form.tipo_cuenta.trim() !== ''
           )) &&
           (form.tipo_pago !== 'QR' || form.imagen_qr !== null);
});

// Methods
const onTipoPagoChange = () => {
    if (form.tipo_pago !== 'Transferencia') {
        form.nombre_titular = '';
        form.numero_cuenta = '';
        form.tipo_cuenta = '';
        form.identificacion = '';
    }
    if (form.tipo_pago !== 'QR') {
        form.imagen_qr = null;
        preview.qr = null;
        if (qrInput.value) qrInput.value.value = '';
    }
};

const openFilePicker = (type: 'logo' | 'qr') => {
    if (type === 'logo' && logoInput.value) {
        logoInput.value.click();
    } else if (type === 'qr' && qrInput.value) {
        qrInput.value.click();
    }
};

const handleDragOverLogo = (event: DragEvent) => {
    event.preventDefault();
    isDraggingLogo.value = true;
};

const handleDragLeaveLogo = (event: DragEvent) => {
    event.preventDefault();
    isDraggingLogo.value = false;
};

const handleDropLogo = (event: DragEvent) => {
    event.preventDefault();
    isDraggingLogo.value = false;
    
    if (event.dataTransfer?.files && event.dataTransfer.files[0]) {
        const file = event.dataTransfer.files[0];
        if (validateImageFile(file)) {
            form.logo_banco = file;
            preview.logo = URL.createObjectURL(file);
        }
    }
};

const handleDragOverQr = (event: DragEvent) => {
    event.preventDefault();
    isDraggingQr.value = true;
};

const handleDragLeaveQr = (event: DragEvent) => {
    event.preventDefault();
    isDraggingQr.value = false;
};

const handleDropQr = (event: DragEvent) => {
    event.preventDefault();
    isDraggingQr.value = false;
    
    if (event.dataTransfer?.files && event.dataTransfer.files[0]) {
        const file = event.dataTransfer.files[0];
        if (validateImageFile(file)) {
            form.imagen_qr = file;
            preview.qr = URL.createObjectURL(file);
        }
    }
};

const validateImageFile = (file: File): boolean => {
    // Validar tipo de archivo
    if (!file.type.startsWith('image/')) {
        toast.error('El archivo debe ser una imagen válida');
        return false;
    }
    
    // Validar tamaño (2MB)
    const maxSize = 2 * 1024 * 1024;
    if (file.size > maxSize) {
        toast.error('La imagen es demasiado grande. Tamaño máximo: 2MB');
        return false;
    }
    
    return true;
};

const onLogoChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file && validateImageFile(file)) {
        form.logo_banco = file;
        preview.logo = URL.createObjectURL(file);
    }
};

const onQrChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file && validateImageFile(file)) {
        form.imagen_qr = file;
        preview.qr = URL.createObjectURL(file);
    }
};

const removeLogo = () => {
    form.logo_banco = null;
    if (preview.logo) {
        URL.revokeObjectURL(preview.logo);
        preview.logo = null;
    }
    if (logoInput.value) logoInput.value.value = '';
};

const removeQr = () => {
    form.imagen_qr = null;
    if (preview.qr) {
        URL.revokeObjectURL(preview.qr);
        preview.qr = null;
    }
    if (qrInput.value) qrInput.value.value = '';
};

const submitForm = async () => {
    loading.value = true;
    errors.value = {};

    try {
        // Validaciones básicas
        if (!form.nombre_banco.trim()) {
            toast.error('El nombre del banco es requerido');
            loading.value = false;
            return;
        }
        
        if (!form.tipo_pago) {
            toast.error('El tipo de pago es requerido');
            loading.value = false;
            return;
        }
        
        // Validaciones condicionales
        if (form.tipo_pago === 'Transferencia') {
            if (!form.nombre_titular.trim()) {
                toast.error('El nombre del titular es requerido para transferencias');
                loading.value = false;
                return;
            }
            
            if (!form.numero_cuenta.trim()) {
                toast.error('El número de cuenta es requerido para transferencias');
                loading.value = false;
                return;
            }
            
            if (!form.tipo_cuenta.trim()) {
                toast.error('El tipo de cuenta es requerido para transferencias');
                loading.value = false;
                return;
            }
        }
        
        if (form.tipo_pago === 'QR' && !form.imagen_qr) {
            toast.error('La imagen QR es requerida para pagos QR');
            loading.value = false;
            return;
        }
        
        // Enviar datos
        await store.createMetodoPago(form);
        
        // Redirigir al listado
        router.push({ name: 'administrador.metodos-pago.index' });
        
    } catch (err: any) {
        console.error('Error en submitForm:', err);
    } finally {
        loading.value = false;
    }
};

// Cleanup preview URLs
onUnmounted(() => {
    if (preview.logo) URL.revokeObjectURL(preview.logo);
    if (preview.qr) URL.revokeObjectURL(preview.qr);
});
</script>

<style scoped>
.metodo-pago-preview {
    min-height: 300px;
}
</style>