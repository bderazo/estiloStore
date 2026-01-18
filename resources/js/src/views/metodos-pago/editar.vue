<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <router-link
                        to="/administrador/metodos-pago"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 transition mr-4"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </router-link>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Editar Método de Pago
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400" v-if="metodoPago">
                            Editando: {{ metodoPago.nombre_banco }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="max-w-7xl mx-auto">
            <!-- Mensajes de Error -->
            <div v-if="erroresGenerales" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-700 dark:text-red-400">
                            Hay errores en el formulario:
                        </p>
                        <ul class="mt-2 text-sm text-red-600 dark:text-red-300 list-disc list-inside">
                            <li v-for="(errorList, field) in erroresGenerales" :key="field">
                                {{ errorList[0] }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Formulario -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="p-6">
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
                                    <div v-if="errors.tipo_pago" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.tipo_pago[0] }}
                                    </div>
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
                                    />
                                    <div v-if="errors.nombre_banco" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.nombre_banco[0] }}
                                    </div>
                                </div>

                                <!-- Logo Banco -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Logo del Banco
                                    </label>
                                    
                                    <!-- Preview Logo -->
                                    <div v-if="logoPreviewUrl" class="mb-4">
                                        <div class="relative w-32 h-32 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600">
                                            <img
                                                :src="logoPreviewUrl"
                                                alt="Logo preview"
                                                class="w-full h-full object-cover"
                                            />
                                            <button
                                                type="button"
                                                @click="removeLogo"
                                                class="absolute top-1 right-1 p-1 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500"
                                                title="Eliminar logo"
                                            >
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ form.logo_removed ? 'Logo será eliminado al guardar' : 
                                               form.logo_banco ? 'Nuevo logo seleccionado' : 'Logo actual' }}
                                        </p>
                                    </div>

                                    <!-- Input de archivo -->
                                    <div class="flex items-center space-x-4">
                                        <input
                                            type="file"
                                            ref="logoInput"
                                            class="hidden"
                                            accept="image/*"
                                            @change="onLogoChange"
                                        />
                                        <button
                                            type="button"
                                            @click="triggerLogoInput"
                                            class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ logoPreviewUrl ? 'Cambiar Logo' : 'Seleccionar Logo' }}
                                        </button>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Dejar vacío para mantener el logo actual
                                        </p>
                                    </div>
                                    <div v-if="errors.logo_banco" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.logo_banco[0] }}
                                    </div>
                                </div>

                                <!-- Campos para Transferencias -->
                                <div v-if="form.tipo_pago === 'Transferencia'" class="mb-6 p-4 border border-blue-200 dark:border-blue-800 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Datos de Transferencia</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Nombre del Titular -->
                                        <div>
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
                                            />
                                            <div v-if="errors.nombre_titular" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                                {{ errors.nombre_titular[0] }}
                                            </div>
                                        </div>

                                        <!-- Identificación -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Identificación
                                            </label>
                                            <input
                                                v-model="form.identificacion"
                                                type="text"
                                                :class="[
                                                    'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                                    errors.identificacion ? 'border-red-300 dark:border-red-700' : ''
                                                ]"
                                                placeholder="Cédula, RUC, etc."
                                            />
                                            <div v-if="errors.identificacion" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                                {{ errors.identificacion[0] }}
                                            </div>
                                        </div>

                                        <!-- Tipo de Cuenta -->
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
                                            <div v-if="errors.tipo_cuenta" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                                {{ errors.tipo_cuenta[0] }}
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
                                            />
                                            <div v-if="errors.numero_cuenta" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                                {{ errors.numero_cuenta[0] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Image -->
                                <div v-if="form.tipo_pago === 'QR'" class="mb-6 p-4 border border-yellow-200 dark:border-yellow-800 rounded-lg bg-yellow-50 dark:bg-yellow-900/20">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Imagen QR
                                    </label>
                                    
                                    <!-- Preview QR -->
                                    <div v-if="qrPreviewUrl" class="mb-4">
                                        <div class="relative w-48 h-48 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600">
                                            <img
                                                :src="qrPreviewUrl"
                                                alt="QR preview"
                                                class="w-full h-full object-cover"
                                            />
                                            <button
                                                type="button"
                                                @click="removeQr"
                                                class="absolute top-1 right-1 p-1 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500"
                                                title="Eliminar QR"
                                            >
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ form.qr_removed ? 'QR será eliminado al guardar' : 
                                               form.imagen_qr ? 'Nuevo QR seleccionado' : 'QR actual' }}
                                        </p>
                                    </div>

                                    <!-- Input de archivo -->
                                    <div class="flex items-center space-x-4">
                                        <input
                                            type="file"
                                            ref="qrInput"
                                            class="hidden"
                                            accept="image/*"
                                            @change="onQrChange"
                                        />
                                        <button
                                            type="button"
                                            @click="triggerQrInput"
                                            class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ qrPreviewUrl ? 'Cambiar QR' : 'Seleccionar QR' }}
                                        </button>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Dejar vacío para mantener el QR actual
                                        </p>
                                    </div>
                                    <div v-if="errors.imagen_qr" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.imagen_qr[0] }}
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
                                            'w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition',
                                            errors.instrucciones ? 'border-red-300 dark:border-red-700' : ''
                                        ]"
                                        placeholder="Instrucciones detalladas para realizar el pago..."
                                    />
                                    <div v-if="errors.instrucciones" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.instrucciones[0] }}
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Estado
                                    </label>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ form.activo ? 'Activo - Los clientes pueden ver este método' : 'Inactivo - Oculto para los clientes' }}
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
                                </div>

                                <!-- Botones -->
                                <div class="flex items-center justify-between pt-6 border-t dark:border-gray-700">
                                    <div>
                                        <button
                                            type="button"
                                            @click="confirmDelete"
                                            :disabled="loading"
                                            class="px-6 py-3 border border-red-600 text-red-600 dark:text-red-400 dark:border-red-400 rounded-lg font-medium hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Eliminar
                                            </span>
                                        </button>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <router-link
                                            to="/administrador/metodos-pago"
                                            class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition"
                                        >
                                            Cancelar
                                        </router-link>
                                        <button
                                            type="submit"
                                            :disabled="loading"
                                            :class="[
                                                'px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition',
                                                loading ? 'opacity-50 cursor-not-allowed' : ''
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
                                                Actualizar
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel Lateral -->
                <div class="space-y-6">
                    <!-- Información -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Información del Método
                            </h3>
                            <div v-if="metodoPago" class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ metodoPago.id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Creado</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ formatDate(metodoPago.created_at) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Actualizado</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ formatDate(metodoPago.updated_at) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Vista Pública</p>
                                    <a
                                        :href="`/metodos-pago/${metodoPago.id}`"
                                        target="_blank"
                                        class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                    >
                                        Ver método
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vista Previa -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Vista Previa Actual
                            </h3>
                            <div v-if="metodoPago" class="space-y-4">
                                <!-- Logo y Nombre -->
                                <div class="flex items-center space-x-4">
                                    <div v-if="currentLogoUrl" class="w-12 h-12">
                                        <img :src="currentLogoUrl" alt="Logo" class="w-full h-full object-contain" />
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ metodoPago.nombre_banco }}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="currentBadgeClass">
                                            {{ metodoPago.tipo_pago_label }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado</span>
                                    <span :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        metodoPago.activo 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ]">
                                        {{ metodoPago.activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>

                                <!-- Datos adicionales -->
                                <div v-if="metodoPago.tipo_pago === 'Transferencia' && metodoPago.nombre_titular" class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Titular</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ metodoPago.nombre_titular }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ metodoPago.cuenta_formateada }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <div v-if="showToast" class="fixed bottom-4 right-4 z-50">
            <div 
                :class="[
                    'px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 transition-all duration-300',
                    toastType === 'success' 
                        ? 'bg-green-100 border border-green-400 text-green-700 dark:bg-green-900/30 dark:border-green-800 dark:text-green-200'
                        : 'bg-red-100 border border-red-400 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-200'
                ]"
            >
                <svg v-if="toastType === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ toastMessage }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useMetodoPagoStore } from '../../stores/metodoPagoStore';

const route = useRoute();
const router = useRouter();
const store = useMetodoPagoStore();

// Refs
const loading = ref(false);
const errors = ref<Record<string, string[]>>({});
const logoInput = ref<HTMLInputElement>();
const qrInput = ref<HTMLInputElement>();
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref<'success' | 'error'>('success');

// Form
interface FormData {
    nombre_banco: string;
    tipo_pago: string;
    nombre_titular: string;
    numero_cuenta: string;
    tipo_cuenta: string;
    identificacion: string;
    instrucciones: string;
    imagen_qr: File | null;
    logo_banco: File | null;
    activo: boolean;
    logo_removed: boolean;
    qr_removed: boolean;
}

const form = reactive<FormData>({
    nombre_banco: '',
    tipo_pago: '',
    nombre_titular: '',
    numero_cuenta: '',
    tipo_cuenta: '',
    identificacion: '',
    instrucciones: '',
    imagen_qr: null,
    logo_banco: null,
    activo: true,
    logo_removed: false,
    qr_removed: false,
});

// Computed
const metodoPago = computed(() => store.metodoPago);
const erroresGenerales = computed(() => {
    const generalErrors: Record<string, string[]> = {}
    Object.entries(errors.value).forEach(([key, value]) => {
        if (value && value.length > 0) {
            generalErrors[key] = value
        }
    })
    return Object.keys(generalErrors).length > 0 ? generalErrors : null
});

const logoPreviewUrl = computed(() => {
    // Si hay un nuevo logo seleccionado, mostrar preview
    if (form.logo_banco) {
        return URL.createObjectURL(form.logo_banco);
    }
    // Si se marcó para eliminar, no mostrar nada
    if (form.logo_removed) {
        return null;
    }
    // Si hay logo en el backend, mostrarlo
    if (metodoPago.value?.logo_banco) {
        return getImageUrl(metodoPago.value.logo_banco);
    }
    return null;
});

const qrPreviewUrl = computed(() => {
    // Si hay un nuevo QR seleccionado, mostrar preview
    if (form.imagen_qr) {
        return URL.createObjectURL(form.imagen_qr);
    }
    // Si se marcó para eliminar, no mostrar nada
    if (form.qr_removed) {
        return null;
    }
    // Si hay QR en el backend, mostrarlo
    if (metodoPago.value?.imagen_qr) {
        return getImageUrl(metodoPago.value.imagen_qr);
    }
    return null;
});

const currentLogoUrl = computed(() => {
    if (metodoPago.value?.logo_banco) {
        return getImageUrl(metodoPago.value.logo_banco);
    }
    return null;
});

const currentBadgeClass = computed(() => {
    const classes: Record<string, string> = {
        Transferencia: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        QR: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        Efectivo: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        Otro: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
    };
    return classes[metodoPago.value?.tipo_pago || ''] || classes.Otro;
});

// Methods
const getImageUrl = (path: string | null | undefined): string => {
    // Si no hay ruta, retorna un placeholder
    if (!path) return '/assets/images/placeholder.png';
    
    // Si ya es una URL completa, retórnala tal cual
    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
    }
    
    // Obtén la URL base desde las variables de entorno
    const APP_URL = import.meta.env.VITE_APP_URL || 'http://127.0.0.1/tienda/public';
    
    // Limpia la ruta
    let cleanPath = path;
    
    // Remueve "/storage/" si existe al inicio
    if (cleanPath.startsWith('/storage/')) {
        cleanPath = cleanPath.substring('/storage/'.length);
    } 
    // Remueve "storage/" si existe al inicio
    else if (cleanPath.startsWith('storage/')) {
        cleanPath = cleanPath.substring('storage/'.length);
    }
    
    // Construye y retorna la URL completa
    return `${APP_URL}/storage/${cleanPath}`;
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const triggerLogoInput = () => {
    logoInput.value?.click();
};

const triggerQrInput = () => {
    qrInput.value?.click();
};

const onTipoPagoChange = () => {
    // Reset campos específicos cuando cambia el tipo
    if (form.tipo_pago !== 'Transferencia') {
        form.nombre_titular = '';
        form.numero_cuenta = '';
        form.tipo_cuenta = '';
        form.identificacion = '';
    }
    if (form.tipo_pago !== 'QR') {
        form.imagen_qr = null;
        form.qr_removed = false;
        if (qrInput.value) qrInput.value.value = '';
    }
};

const onLogoChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        // Validar archivo
        if (!file.type.startsWith('image/')) {
            mostrarError('El archivo debe ser una imagen', 'logo_banco');
            return;
        }
        if (file.size > 5 * 1024 * 1024) { // 5MB
            mostrarError('La imagen no debe superar los 5MB', 'logo_banco');
            return;
        }
        
        form.logo_banco = file;
        form.logo_removed = false; // Si seleccionamos nuevo logo, ya no queremos eliminarlo
        
        // Limpiar error si existe
        if (errors.value.logo_banco) {
            errors.value.logo_banco = [];
        }
    }
};

const onQrChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        // Validar archivo
        if (!file.type.startsWith('image/')) {
            mostrarError('El archivo debe ser una imagen', 'imagen_qr');
            return;
        }
        if (file.size > 5 * 1024 * 1024) { // 5MB
            mostrarError('La imagen no debe superar los 5MB', 'imagen_qr');
            return;
        }
        
        form.imagen_qr = file;
        form.qr_removed = false; // Si seleccionamos nuevo QR, ya no queremos eliminarlo
        
        // Limpiar error si existe
        if (errors.value.imagen_qr) {
            errors.value.imagen_qr = [];
        }
    }
};

const removeLogo = () => {
    // Si hay un logo actual del backend, marcarlo para eliminación
    if (metodoPago.value?.logo_banco && !form.logo_banco) {
        form.logo_removed = true;
    } 
    // Si hay un nuevo logo seleccionado, eliminarlo
    else if (form.logo_banco) {
        form.logo_banco = null;
        form.logo_removed = false;
    }
    
    if (logoInput.value) logoInput.value.value = '';
};

const removeQr = () => {
    // Si hay un QR actual del backend, marcarlo para eliminación
    if (metodoPago.value?.imagen_qr && !form.imagen_qr) {
        form.qr_removed = true;
    } 
    // Si hay un nuevo QR seleccionado, eliminarlo
    else if (form.imagen_qr) {
        form.imagen_qr = null;
        form.qr_removed = false;
    }
    
    if (qrInput.value) qrInput.value.value = '';
};

const mostrarError = (mensaje: string, campo?: string) => {
    if (campo) {
        errors.value[campo] = [mensaje];
    }
    toastMessage.value = mensaje;
    toastType.value = 'error';
    showToast.value = true;
    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};

const mostrarExito = (mensaje: string) => {
    toastMessage.value = mensaje;
    toastType.value = 'success';
    showToast.value = true;
    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};

const loadMetodoPago = () => {
    if (metodoPago.value) {
        console.log('DEBUG: Cargando datos del método:', metodoPago.value);
        
        form.nombre_banco = metodoPago.value.nombre_banco || '';
        form.tipo_pago = metodoPago.value.tipo_pago || '';
        form.nombre_titular = metodoPago.value.nombre_titular || '';
        form.numero_cuenta = metodoPago.value.numero_cuenta || '';
        form.tipo_cuenta = metodoPago.value.tipo_cuenta || '';
        form.identificacion = metodoPago.value.identificacion || '';
        form.instrucciones = metodoPago.value.instrucciones || '';
        form.activo = metodoPago.value.activo ?? true;
        form.logo_removed = false;
        form.qr_removed = false;
        
        console.log('DEBUG: Formulario después de cargar:', form);
    } else {
        console.error('DEBUG: metodoPago.value es null');
    }
};

const submitForm = async () => {
    loading.value = true;
    errors.value = {};

    try {
        // Crear FormData
        const formData = new FormData();
        
        // Agregar campos al FormData
        formData.append('nombre_banco', form.nombre_banco);
        formData.append('tipo_pago', form.tipo_pago);
        formData.append('activo', form.activo ? '1' : '0');
        
        // Campos opcionales
        if (form.instrucciones) {
            formData.append('instrucciones', form.instrucciones);
        }
        
        // Campos específicos por tipo de pago
        if (form.tipo_pago === 'Transferencia') {
            formData.append('nombre_titular', form.nombre_titular || '');
            formData.append('numero_cuenta', form.numero_cuenta || '');
            formData.append('tipo_cuenta', form.tipo_cuenta || '');
            formData.append('identificacion', form.identificacion || '');
        } else {
            // Limpiar campos de transferencia si no es transferencia
            formData.append('nombre_titular', '');
            formData.append('numero_cuenta', '');
            formData.append('tipo_cuenta', '');
            formData.append('identificacion', '');
        }
        
        // Archivos - solo si se seleccionaron nuevos
        if (form.logo_banco instanceof File) {
            formData.append('logo_banco', form.logo_banco);
        }
        
        if (form.tipo_pago === 'QR' && form.imagen_qr instanceof File) {
            formData.append('imagen_qr', form.imagen_qr);
        }
        
        // Indicar si se deben eliminar imágenes existentes
        if (form.logo_removed) {
            formData.append('remove_logo', '1');
        }
        
        if (form.qr_removed) {
            formData.append('remove_qr', '1');
        }

        // IMPORTANTE: Para Laravel, agregar _method para usar PUT
        formData.append('_method', 'PUT');

        // DEBUG: Mostrar datos que se enviarán
        console.group('📋 Datos enviados al backend');
        for (const [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
        }
        console.groupEnd();

        await store.updateMetodoPago(Number(route.params.id), formData);
        
        mostrarExito('Método de pago actualizado exitosamente');
        
        // Redirigir después de un segundo
        setTimeout(() => {
            router.push('/administrador/metodos-pago');
        }, 1000);
        
    } catch (error: any) {
        console.error('🔥 Error al actualizar:', error);
        
        // Manejar errores de validación
        if (error.response?.data?.errors) {
            console.error('Errores de validación:', error.response.data.errors);
            errors.value = error.response.data.errors;
            mostrarError('Por favor, corrige los errores en el formulario');
        } else {
            const message = error.response?.data?.message || 
                          error.response?.data?.error || 
                          'Error al actualizar el método de pago';
            mostrarError(message);
        }
    } finally {
        loading.value = false;
    }
};

const confirmDelete = async () => {
    if (window.confirm(`¿Estás seguro de eliminar el método de pago "${metodoPago.value?.nombre_banco}"? Esta acción no se puede deshacer.`)) {
        try {
            await store.deleteMetodoPago(Number(route.params.id));
            mostrarExito('Método de pago eliminado exitosamente');
            setTimeout(() => {
                router.push('/administrador/metodos-pago');
            }, 1000);
        } catch (error: any) {
            mostrarError(error.response?.data?.message || 'Error al eliminar el método de pago');
        }
    }
};

// Lifecycle
onMounted(async () => {
    const id = Number(route.params.id);
    
    if (id) {
        loading.value = true;
        try {
            await store.fetchMetodoPago(id);
            loadMetodoPago();
        } catch (error: any) {
            console.error('Error al cargar método de pago:', error);
            mostrarError('No se pudo cargar el método de pago');
            setTimeout(() => {
                router.push('/administrador/metodos-pago');
            }, 2000);
        } finally {
            loading.value = false;
        }
    }
});

// Cleanup preview URLs
onUnmounted(() => {
    // Revocar URLs de blob para liberar memoria
    if (form.logo_banco) {
        const url = URL.createObjectURL(form.logo_banco);
        URL.revokeObjectURL(url);
    }
    if (form.imagen_qr) {
        const url = URL.createObjectURL(form.imagen_qr);
        URL.revokeObjectURL(url);
    }
});
</script>

<style scoped>
/* Estilos personalizados */
</style>