<template>
    <div class="editar-categoria-container">
        <!-- Header -->
        <div class="panel-header">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div>
                    <h1
                        class="text-2xl font-bold text-gray-900 dark:text-white"
                    >
                        Editar Categoría
                        <span
                            v-if="categoria"
                            class="text-base font-normal text-gray-600 dark:text-gray-400"
                        >
                            - {{ categoria.nombre }}
                        </span>
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Modificar los datos de una categoría existente
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <RouterLink
                        :to="{ name: 'categorias.index' }"
                        class="btn btn-secondary"
                    >
                        <IconArrowLeft class="w-4 h-4 mr-2" />
                        Volver al listado
                    </RouterLink>
                </div>
            </div>
        </div>

        <!-- Loading inicial -->
        <div v-if="initialLoading" class="panel">
            <div class="flex justify-center items-center py-8">
                <div
                    class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"
                ></div>
                <span class="ml-2 text-gray-600 dark:text-gray-400"
                    >Cargando categoría...</span
                >
            </div>
        </div>

        <!-- Error al cargar -->
        <div v-else-if="loadError" class="panel">
            <div class="text-center py-8">
                <IconAlertCircle class="w-16 h-16 mx-auto text-red-400 mb-4" />
                <h3
                    class="text-lg font-medium text-gray-900 dark:text-white mb-2"
                >
                    Error al cargar la categoría
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    {{ loadError }}
                </p>
                <div class="space-x-2">
                    <button @click="loadCategoria" class="btn btn-primary">
                        <IconRefresh class="w-4 h-4 mr-2" />
                        Reintentar
                    </button>
                    <RouterLink
                        :to="{ name: 'categorias.index' }"
                        class="btn btn-secondary"
                    >
                        Volver al listado
                    </RouterLink>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div v-else-if="categoria" class="panel">
            <form @submit.prevent="actualizarCategoria" class="space-y-6">
                <!-- Información básica -->
                <div>
                    <h3
                        class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                    >
                        Información Básica
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div class="form-group">
                            <label for="nombre" class="form-label required">
                                Nombre *
                            </label>
                            <input
                                id="nombre"
                                v-model="form.nombre"
                                type="text"
                                class="form-input"
                                :class="{ 'form-input-error': errors.nombre }"
                                placeholder="Ingrese el nombre de la categoría"
                                maxlength="255"
                                @input="generateSlug"
                            />
                            <div v-if="errors.nombre" class="form-error">
                                {{ errors.nombre[0] }}
                            </div>
                            <div class="form-help">
                                El nombre será usado como título visible de la
                                categoría
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label for="slug" class="form-label"> Slug </label>
                            <input
                                id="slug"
                                v-model="form.slug"
                                type="text"
                                class="form-input"
                                :class="{ 'form-input-error': errors.slug }"
                                placeholder="se-generara-automaticamente"
                                maxlength="255"
                            />
                            <div v-if="errors.slug" class="form-error">
                                {{ errors.slug[0] }}
                            </div>
                            <div class="form-help">
                                Se genera automáticamente desde el nombre. Usado
                                para URLs amigables.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label for="descripcion" class="form-label">
                        Descripción
                    </label>
                    <textarea
                        id="descripcion"
                        v-model="form.descripcion"
                        rows="4"
                        class="form-textarea"
                        :class="{ 'form-input-error': errors.descripcion }"
                        placeholder="Descripción opcional de la categoría"
                        maxlength="1000"
                    ></textarea>
                    <div v-if="errors.descripcion" class="form-error">
                        {{ errors.descripcion[0] }}
                    </div>
                    <div class="form-help">
                        Descripción opcional que ayuda a entender el propósito
                        de la categoría
                    </div>
                </div>

                <!-- Jerarquía -->
                <div>
                    <h3
                        class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                    >
                        Estructura Jerárquica
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Categoría Padre -->
                        <div class="form-group">
                            <label for="parent_id" class="form-label">
                                Categoría Padre
                            </label>
                            <select
                                id="parent_id"
                                v-model="form.parent_id"
                                class="form-select"
                                :class="{
                                    'form-input-error': errors.parent_id,
                                }"
                                :disabled="loading"
                            >
                                <option value="">
                                    Sin categoría padre (Categoría principal)
                                </option>
                                <option
                                    v-for="option in availableParentOptions"
                                    :key="option.id"
                                    :value="option.id"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                            <div v-if="errors.parent_id" class="form-error">
                                {{ errors.parent_id[0] }}
                            </div>
                            <div class="form-help">
                                Seleccione una categoría padre para crear una
                                subcategoría.
                                <span
                                    v-if="categoria.children_count > 0"
                                    class="text-amber-600 dark:text-amber-400"
                                >
                                    Esta categoría tiene
                                    {{ categoria.children_count }}
                                    subcategorías.
                                </span>
                            </div>
                        </div>

                        <!-- Orden -->
                        <div class="form-group">
                            <label for="orden" class="form-label">
                                Orden
                            </label>
                            <input
                                id="orden"
                                v-model.number="form.orden"
                                type="number"
                                min="0"
                                class="form-input"
                                :class="{ 'form-input-error': errors.orden }"
                                placeholder="0"
                            />
                            <div v-if="errors.orden" class="form-error">
                                {{ errors.orden[0] }}
                            </div>
                            <div class="form-help">
                                Orden de visualización dentro del mismo nivel
                                jerárquico
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <h3
                        class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                    >
                        Configuración
                    </h3>

                    <div class="form-group">
                        <div class="flex items-center">
                            <input
                                id="activo"
                                v-model="form.activo"
                                type="checkbox"
                                class="form-checkbox"
                            />
                            <label
                                for="activo"
                                class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Categoría activa
                            </label>
                        </div>
                        <div class="form-help ml-6">
                            Las categorías inactivas no serán visibles en el
                            sistema.
                            <span
                                v-if="
                                    categoria.children_count > 0 && !form.activo
                                "
                                class="text-amber-600 dark:text-amber-400"
                            >
                                Desactivar esta categoría también afectará a sus
                                subcategorías.
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Imagen -->
                <div class="mt-8">
                    <h3
                        class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                    >
                        Imagen de la categoría
                    </h3>

                    <div class="form-group">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Imagen (Opcional)
                        </label>
                        <div
                            @dragover="handleDragOverImagen"
                            @dragleave="handleDragLeaveImagen"
                            @drop="handleDropImagen"
                            :class="[
                                'border-2 border-dashed rounded-lg p-6 text-center transition-all duration-200',
                                isDraggingImagen
                                    ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
                                    : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500',
                                errors.imagen
                                    ? 'border-red-300 dark:border-red-700'
                                    : '',
                            ]"
                        >
                            <input
                                ref="imagenInput"
                                type="file"
                                accept="image/*"
                                @change="onImagenChange"
                                class="hidden"
                            />

                            <!-- Vista previa de imagen actual -->
                            <div v-if="categoria?.imagen" class="mb-4">
                                <p
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Imagen actual:
                                </p>
                                <div class="flex flex-col items-center">
                                    <img
                                        :src="getImageUrl(categoria.imagen)"
                                        :alt="categoria.nombre"
                                        class="h-32 w-auto object-contain rounded-lg"
                                    />
                                    <button
                                        type="button"
                                        @click="confirmRemoveImagen"
                                        class="mt-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                    >
                                        Eliminar imagen actual
                                    </button>
                                </div>
                            </div>

                            <!-- Zona de carga de nueva imagen -->
                            <div v-if="!preview.imagen && !categoria?.imagen">
                                <svg
                                    class="mx-auto h-12 w-12 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                                <p
                                    class="mt-2 text-sm text-gray-600 dark:text-gray-400"
                                >
                                    <span
                                        class="font-medium text-blue-600 dark:text-blue-400 cursor-pointer"
                                        @click="openFilePicker('imagen')"
                                    >
                                        Haz clic para subir imagen
                                    </span>
                                    o arrastra y suelta tu archivo
                                </p>
                                <p
                                    class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                >
                                    Formatos: JPG, PNG (máximo 2MB)
                                </p>
                            </div>

                            <!-- Previsualización de nueva imagen seleccionada -->
                            <div v-else-if="preview.imagen">
                                <div class="flex flex-col items-center">
                                    <p
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >
                                        Nueva imagen:
                                    </p>
                                    <img
                                        :src="preview.imagen"
                                        alt="Imagen preview"
                                        class="h-32 w-auto object-contain rounded-lg"
                                    />
                                    <div class="mt-4 text-center">
                                        <p
                                            class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-xs"
                                        >
                                            {{
                                                form.imagen?.name ||
                                                "Imagen seleccionada"
                                            }}
                                        </p>
                                        <div
                                            class="mt-4 flex items-center justify-center space-x-3"
                                        >
                                            <button
                                                type="button"
                                                @click="
                                                    openFilePicker('imagen')
                                                "
                                                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                            >
                                                Cambiar imagen
                                            </button>
                                            <button
                                                type="button"
                                                @click="removeImagen"
                                                class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                            >
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensaje si hay imagen existente -->
                            <div
                                v-else-if="categoria?.imagen"
                                class="text-center"
                            >
                                <p
                                    class="text-sm text-green-600 dark:text-green-400"
                                >
                                    ✓ La categoría ya tiene una imagen asignada
                                </p>
                            </div>
                        </div>
                        <div v-if="errors.imagen" class="form-error">
                            {{ errors.imagen[0] }}
                        </div>
                        <div class="form-help">
                            Imagen opcional para mostrar cuando no hay logo o
                            para fondos más grandes.
                        </div>
                    </div>
                </div>

                <!-- Logo -->
                <div>
                    <h3
                        class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                    >
                        Logo de la categoría
                    </h3>

                    <div class="form-group">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Logo (Prioridad alta)
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
                                errors.logo
                                    ? 'border-red-300 dark:border-red-700'
                                    : '',
                            ]"
                        >
                            <input
                                ref="logoInput"
                                type="file"
                                accept="image/*"
                                @change="onLogoChange"
                                class="hidden"
                            />

                            <!-- Vista previa de logo actual -->
                            <div v-if="categoria?.logo" class="mb-4">
                                <p
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Logo actual:
                                </p>
                                <div class="flex flex-col items-center">
                                    <img
                                        :src="getImageUrl(categoria.logo)"
                                        :alt="categoria.nombre"
                                        class="h-32 w-auto object-contain rounded-lg bg-white p-2"
                                    />
                                    <button
                                        type="button"
                                        @click="confirmRemoveLogo"
                                        class="mt-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                    >
                                        Eliminar logo actual
                                    </button>
                                </div>
                            </div>

                            <!-- Zona de carga de nuevo logo -->
                            <div v-if="!preview.logo && !categoria?.logo">
                                <svg
                                    class="mx-auto h-12 w-12 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    />
                                </svg>
                                <p
                                    class="mt-2 text-sm text-gray-600 dark:text-gray-400"
                                >
                                    <span
                                        class="font-medium text-blue-600 dark:text-blue-400 cursor-pointer"
                                        @click="openFilePicker('logo')"
                                    >
                                        Haz clic para subir logo
                                    </span>
                                    o arrastra y suelta tu archivo
                                </p>
                                <p
                                    class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                >
                                    Formatos: PNG, SVG recomendados (máximo 2MB)
                                </p>
                            </div>

                            <!-- Previsualización de nuevo logo seleccionado -->
                            <div v-else-if="preview.logo">
                                <div class="flex flex-col items-center">
                                    <p
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >
                                        Nuevo logo:
                                    </p>
                                    <img
                                        :src="preview.logo"
                                        alt="Logo preview"
                                        class="h-32 w-auto object-contain rounded-lg bg-white p-2"
                                    />
                                    <div class="mt-4 text-center">
                                        <p
                                            class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-xs"
                                        >
                                            {{
                                                form.logo?.name ||
                                                "Logo seleccionado"
                                            }}
                                        </p>
                                        <div
                                            class="mt-4 flex items-center justify-center space-x-3"
                                        >
                                            <button
                                                type="button"
                                                @click="openFilePicker('logo')"
                                                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                            >
                                                Cambiar logo
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

                            <!-- Mensaje si hay logo existente -->
                            <div
                                v-else-if="categoria?.logo"
                                class="text-center"
                            >
                                <p
                                    class="text-sm text-green-600 dark:text-green-400"
                                >
                                    ✓ La categoría ya tiene un logo asignado
                                </p>
                            </div>
                        </div>
                        <div v-if="errors.logo" class="form-error">
                            {{ errors.logo[0] }}
                        </div>
                        <div class="form-help">
                            El logo tiene prioridad sobre la imagen en
                            visualizaciones. Se recomienda usar íconos pequeños
                            con fondo transparente.
                        </div>
                    </div>
                </div>

                <!-- Vista previa de cambios -->
                <div
                    v-if="hasChanges"
                    class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-md p-4"
                >
                    <div class="flex items-start">
                        <IconAlertTriangle
                            class="w-5 h-5 text-amber-500 mr-2 mt-0.5 flex-shrink-0"
                        />
                        <div class="flex-1">
                            <h4
                                class="text-sm font-medium text-amber-900 dark:text-amber-200"
                            >
                                Cambios detectados
                            </h4>
                            <div
                                class="text-sm text-amber-700 dark:text-amber-300 mt-1 space-y-1"
                            >
                                <div v-if="changedFields.nombre">
                                    <span class="font-medium">Nombre:</span>
                                    "{{ categoria.nombre }}" → "{{
                                        form.nombre
                                    }}"
                                </div>
                                <div v-if="changedFields.parent_id">
                                    <span class="font-medium"
                                        >Categoría padre:</span
                                    >
                                    {{ getParentChangeName() }}
                                </div>
                                <div v-if="changedFields.activo">
                                    <span class="font-medium">Estado:</span>
                                    {{
                                        categoria.activo ? "Activo" : "Inactivo"
                                    }}
                                    → {{ form.activo ? "Activo" : "Inactivo" }}
                                </div>
                            </div>
                            <div
                                v-if="form.parent_id && selectedParent"
                                class="mt-2 pt-2 border-t border-amber-200 dark:border-amber-700"
                            >
                                <p
                                    class="text-sm text-amber-700 dark:text-amber-300"
                                >
                                    <span class="font-medium">Nueva ruta:</span>
                                    {{ previewPath }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advertencia de subcategorías -->
                <div
                    v-if="
                        categoria.children_count > 0 &&
                        form.parent_id !== categoria.parent_id
                    "
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4"
                >
                    <div class="flex items-start">
                        <IconAlertCircle
                            class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0"
                        />
                        <div>
                            <h4
                                class="text-sm font-medium text-red-900 dark:text-red-200"
                            >
                                Atención: Esta categoría tiene subcategorías
                            </h4>
                            <p
                                class="text-sm text-red-700 dark:text-red-300 mt-1"
                            >
                                Cambiar la categoría padre afectará la
                                estructura jerárquica de las
                                {{ categoria.children_count }} subcategorías.
                                Todas las subcategorías se mantendrán como hijas
                                de esta categoría.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Información actual -->
                <div
                    class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-4"
                >
                    <h4
                        class="text-sm font-medium text-gray-900 dark:text-white mb-3"
                    >
                        Información actual
                    </h4>
                    <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">
                                Nivel actual:
                            </dt>
                            <dd
                                class="font-medium text-gray-900 dark:text-white"
                            >
                                {{ categoria.level }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">
                                Subcategorías:
                            </dt>
                            <dd
                                class="font-medium text-gray-900 dark:text-white"
                            >
                                {{ categoria.children_count }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">
                                Ruta actual:
                            </dt>
                            <dd
                                class="font-medium text-gray-900 dark:text-white"
                            >
                                {{ categoria.path }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Botones -->
                <div
                    class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700"
                >
                    <RouterLink
                        :to="{ name: 'categorias.index' }"
                        class="btn btn-secondary"
                        :class="{ 'pointer-events-none opacity-50': loading }"
                    >
                        Cancelar
                    </RouterLink>

                    <button
                        type="button"
                        @click="resetForm"
                        class="btn btn-secondary"
                        :disabled="loading || !hasChanges"
                    >
                        <IconRotateClockwise class="w-4 h-4 mr-2" />
                        Deshacer Cambios
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        :disabled="loading || !isFormValid || !hasChanges"
                    >
                        <IconLoader
                            v-if="loading"
                            class="w-4 h-4 mr-2 animate-spin"
                        />
                        <IconDeviceFloppy v-else class="w-4 h-4 mr-2" />
                        {{ loading ? "Guardando..." : "Guardar Cambios" }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Ayuda -->
        <div v-if="categoria" class="panel">
            <div class="flex items-start space-x-3">
                <IconBulb
                    class="w-5 h-5 text-yellow-500 mt-0.5 flex-shrink-0"
                />
                <div>
                    <h3
                        class="text-sm font-medium text-gray-900 dark:text-white"
                    >
                        Consideraciones al editar categorías
                    </h3>
                    <ul
                        class="mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-1"
                    >
                        <li>
                            • Cambiar el nombre actualizará automáticamente el
                            slug
                        </li>
                        <li>
                            • No puedes seleccionar como padre a esta misma
                            categoría o sus subcategorías
                        </li>
                        <li>
                            • Cambiar la categoría padre reorganizará la
                            estructura jerárquica
                        </li>
                        <li>
                            • Las subcategorías seguirán perteneciendo a esta
                            categoría
                        </li>
                        <li>
                            • Desactivar una categoría la ocultará del sistema
                            pero mantendrá sus subcategorías
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch, reactive } from "vue";
import { RouterLink, useRouter, useRoute } from "vue-router";
import { useCategoriaStore } from "../../stores/categoriaStore";
import Swal from "sweetalert2";
import { useToast } from "vue-toastification";

const toast = useToast();

// Refs
const initialLoading = ref(true);
const loading = ref(false);
const loadError = ref<string | null>(null);
const errors = ref<Record<string, string[]>>({});

// Drag & drop refs
const isDraggingLogo = ref(false);
const isDraggingImagen = ref(false);
const logoInput = ref<HTMLInputElement>();
const imagenInput = ref<HTMLInputElement>();

// Icons
import {
    IconArrowLeft,
    IconDeviceFloppy,
    IconLoader,
    IconAlertCircle,
    IconAlertTriangle,
    IconRefresh,
    IconRotateClockwise,
    IconBulb,
} from "@tabler/icons-vue";

import type {
    UpdateCategoriaRequest,
    Categoria,
    CategoriaSelectOption,
} from "../../types/categoria";

// Router
const router = useRouter();
const route = useRoute();

// Store
const categoriaStore = useCategoriaStore();

// Reactive data
const categoria = ref<Categoria | null>(null);
const parentOptions = ref<CategoriaSelectOption[]>([]);

const form = ref<
    UpdateCategoriaRequest & {
        slug?: string;
        imagen?: File | null;
        imagen_eliminar?: boolean;
        logo?: File | null;
        logo_eliminar?: boolean;
    }
>({
    nombre: "",
    descripcion: "",
    parent_id: null,
    activo: true,
    orden: 0,
    imagen: null,
    imagen_eliminar: false,
    logo: null,
    logo_eliminar: false,
});

const preview = reactive({
    logo: null as string | null,
    imagen: null as string | null,
});

const originalForm = ref<UpdateCategoriaRequest & { 
    slug?: string;
    hasLogo?: boolean;
    hasImagen?: boolean;
}>({});

// Computeds
const categoriaId = computed(() => {
    return parseInt(route.params.id as string);
});

const availableParentOptions = computed(() => {
    // Excluir esta categoría y sus descendientes para evitar referencias circulares
    return parentOptions.value.filter((option) => {
        // No permitir seleccionar la misma categoría
        if (option.id === categoriaId.value) return false;

        // No permitir seleccionar subcategorías de esta categoría
        // Esto se podría mejorar con más lógica de jerarquía si es necesario
        return true;
    });
});

const selectedParent = computed(() => {
    if (!form.value.parent_id) return null;
    return (
        availableParentOptions.value.find(
            (option) => option.id === form.value.parent_id,
        ) || null
    );
});

const previewPath = computed(() => {
    if (!selectedParent.value || !form.value.nombre) return "";

    const parentName = selectedParent.value.nombre.replace(/^└─\s*/, "");
    return `${parentName} > ${form.value.nombre}`;
});

const hasChanges = computed(() => {
    if (!categoria.value) return false;

    return (
        form.value.nombre !== originalForm.value.nombre ||
        form.value.descripcion !== originalForm.value.descripcion ||
        form.value.parent_id !== originalForm.value.parent_id ||
        form.value.activo !== originalForm.value.activo ||
        form.value.orden !== originalForm.value.orden ||
        form.value.imagen !== null ||
        form.value.imagen_eliminar ||
        form.value.logo !== null ||
        form.value.logo_eliminar
    );
});

const changedFields = computed(() => {
    if (!categoria.value) return {};

    return {
        nombre: form.value.nombre !== originalForm.value.nombre,
        descripcion: form.value.descripcion !== originalForm.value.descripcion,
        parent_id: form.value.parent_id !== originalForm.value.parent_id,
        activo: form.value.activo !== originalForm.value.activo,
        orden: form.value.orden !== originalForm.value.orden,
        imagen: form.value.imagen !== null || form.value.imagen_eliminar,
        logo: form.value.logo !== null || form.value.logo_eliminar,
    };
});

const isFormValid = computed(() => {
    return (
        form.value.nombre &&
        form.value.nombre.trim().length > 0 &&
        Object.keys(errors.value).length === 0
    );
});

// Methods
const generateSlug = () => {
    if (!form.value.nombre) return;

    form.value.slug = form.value.nombre
        .toLowerCase()
        .trim()
        .replace(/[áàäâã]/g, "a")
        .replace(/[éèëê]/g, "e")
        .replace(/[íìïî]/g, "i")
        .replace(/[óòöôõ]/g, "o")
        .replace(/[úùüû]/g, "u")
        .replace(/[ñ]/g, "n")
        .replace(/[ç]/g, "c")
        .replace(/[^a-z0-9\s-]/g, "")
        .replace(/\s+/g, "-")
        .replace(/-+/g, "-")
        .replace(/^-|-$/g, "");
};

const getParentChangeName = () => {
    const originalParent = categoria.value?.parent?.nombre || "Sin padre";
    const newParent =
        selectedParent.value?.nombre.replace(/^└─\s*/, "") || "Sin padre";
    return `"${originalParent}" → "${newParent}"`;
};

// Métodos para manejar logos
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
        if (validateImageFile(file, 'logo')) {
            form.value.logo = file;
            preview.logo = URL.createObjectURL(file);
            form.value.logo_eliminar = false; // Si se sube nuevo logo, no eliminar
        }
    }
};

const onLogoChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file && validateImageFile(file, 'logo')) {
        // Limpiar preview anterior si existe
        if (preview.logo) URL.revokeObjectURL(preview.logo);
        form.value.logo = file;
        preview.logo = URL.createObjectURL(file);
        form.value.logo_eliminar = false;
    }
};

const removeLogo = () => {
    if (preview.logo) URL.revokeObjectURL(preview.logo);
    form.value.logo = null;
    preview.logo = null;
    if (logoInput.value) logoInput.value.value = "";

    // Si hay logo existente, marcar para eliminar
    if (categoria.value?.logo) {
        form.value.logo_eliminar = true;
    }
};

const confirmRemoveLogo = async () => {
    const result = await Swal.fire({
        title: "¿Eliminar logo?",
        text: "Esta acción eliminará el logo actual de la categoría",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#dc2626",
    });

    if (result.isConfirmed) {
        removeLogo();
    }
};

// Métodos para manejar imágenes
const handleDragOverImagen = (event: DragEvent) => {
    event.preventDefault();
    isDraggingImagen.value = true;
};

const handleDragLeaveImagen = (event: DragEvent) => {
    event.preventDefault();
    isDraggingImagen.value = false;
};

const handleDropImagen = (event: DragEvent) => {
    event.preventDefault();
    isDraggingImagen.value = false;

    if (event.dataTransfer?.files && event.dataTransfer.files[0]) {
        const file = event.dataTransfer.files[0];
        if (validateImageFile(file, 'imagen')) {
            form.value.imagen = file;
            preview.imagen = URL.createObjectURL(file);
            form.value.imagen_eliminar = false;
        }
    }
};

const onImagenChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file && validateImageFile(file, 'imagen')) {
        // Limpiar preview anterior si existe
        if (preview.imagen) URL.revokeObjectURL(preview.imagen);
        form.value.imagen = file;
        preview.imagen = URL.createObjectURL(file);
        form.value.imagen_eliminar = false;
    }
};

const removeImagen = () => {
    if (preview.imagen) URL.revokeObjectURL(preview.imagen);
    form.value.imagen = null;
    preview.imagen = null;
    if (imagenInput.value) imagenInput.value.value = "";

    // Si hay imagen existente, marcar para eliminar
    if (categoria.value?.imagen) {
        form.value.imagen_eliminar = true;
    }
};

const confirmRemoveImagen = async () => {
    const result = await Swal.fire({
        title: "¿Eliminar imagen?",
        text: "Esta acción eliminará la imagen actual de la categoría",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#dc2626",
    });

    if (result.isConfirmed) {
        removeImagen();
    }
};

// Función de validación de archivos
const validateImageFile = (file: File, type: 'logo' | 'imagen'): boolean => {
    // Validar tipo de archivo
    if (!file.type.startsWith("image/")) {
        toast.error("El archivo debe ser una imagen válida");
        return false;
    }

    // Validar tamaño (2MB)
    const maxSize = 2 * 1024 * 1024;
    if (file.size > maxSize) {
        toast.error("La imagen es demasiado grande. Tamaño máximo: 2MB");
        return false;
    }

    // Validaciones adicionales para logo
    if (type === 'logo') {
        const extension = file.name.split('.').pop()?.toLowerCase();
        const recommendedExtensions = ['png', 'svg', 'ico'];
        
        if (!recommendedExtensions.includes(extension || '')) {
            toast.warning("Para logos se recomienda usar PNG o SVG para mejor calidad");
            // No bloquear, solo mostrar advertencia
        }
    }

    return true;
};

const openFilePicker = (type: "logo" | "imagen") => {
    if (type === "logo" && logoInput.value) {
        logoInput.value.click();
    } else if (type === "imagen" && imagenInput.value) {
        imagenInput.value.click();
    }
};

// Función para obtener URL de imagen
const getImageUrl = (path: string | null): string => {
    if (!path) return '';
    if (path.startsWith('http')) return path;
    return `/storage/${path}`;
};

// Actualizar fillForm para incluir logo e imagen
const fillForm = (categoriaData: Categoria) => {
    form.value = {
        nombre: categoriaData.nombre,
        descripcion: categoriaData.descripcion || "",
        parent_id: categoriaData.parent_id,
        activo: categoriaData.activo,
        orden: categoriaData.orden,
        slug: categoriaData.slug,
        imagen: null, // No cargar File aquí
        imagen_eliminar: false,
        logo: null, // No cargar File aquí
        logo_eliminar: false,
    };

    // Guardar estado original para detectar cambios
    originalForm.value = { 
        ...form.value,
        hasLogo: !!categoriaData.logo,
        hasImagen: !!categoriaData.imagen
    };
    
    // Limpiar previews
    if (preview.logo) URL.revokeObjectURL(preview.logo);
    if (preview.imagen) URL.revokeObjectURL(preview.imagen);
    preview.logo = null;
    preview.imagen = null;
};

const loadCategoria = async () => {
    if (!categoriaId.value) {
        loadError.value = "ID de categoría no válido";
        return;
    }

    initialLoading.value = true;
    loadError.value = null;

    try {
        // Cargar categoría y opciones de padre en paralelo
        const [categoriaData] = await Promise.all([
            categoriaStore.fetchCategoria(categoriaId.value),
            categoriaStore.fetchParentOptions(categoriaId.value),
        ]);

        categoria.value = categoriaData;
        parentOptions.value = categoriaStore.parentOptions;

        // Llenar formulario con datos actuales
        if (categoriaData) {
            fillForm(categoriaData);
        }
    } catch (error: any) {
        console.error("Error al cargar categoría:", error);
        loadError.value =
            error.response?.data?.message || "No se pudo cargar la categoría";
    } finally {
        initialLoading.value = false;
    }
};

const resetForm = () => {
    if (categoria.value) {
        fillForm(categoria.value);
    }
    clearErrors();
};

const clearErrors = () => {
    errors.value = {};
};

const actualizarCategoria = async () => {
    if (
        loading.value ||
        !isFormValid.value ||
        !hasChanges.value ||
        !categoria.value
    )
        return;

    clearErrors();
    loading.value = true;

    try {
        // Crear FormData para enviar archivos
        const formData = new FormData();

        // Agregar datos básicos
        formData.append("nombre", form.value.nombre?.trim() || "");
        formData.append("activo", form.value.activo ? "1" : "0");
        formData.append("orden", (form.value.orden || 0).toString());

        // Agregar slug si existe
        if (form.value.slug) {
            formData.append("slug", form.value.slug);
        }

        // Agregar campos opcionales si tienen valor
        if (form.value.descripcion?.trim()) {
            formData.append("descripcion", form.value.descripcion.trim());
        }

        if (form.value.parent_id) {
            formData.append("parent_id", form.value.parent_id.toString());
        }

        // Manejar imagen
        if (form.value.imagen instanceof File) {
            formData.append("imagen", form.value.imagen);
        }

        // Si se marca para eliminar imagen existente
        if (form.value.imagen_eliminar && categoria.value.imagen) {
            formData.append("imagen_eliminar", "1");
        }

        // Manejar logo
        if (form.value.logo instanceof File) {
            formData.append("logo", form.value.logo);
        }

        // Si se marca para eliminar logo existente
        if (form.value.logo_eliminar && categoria.value.logo) {
            formData.append("logo_eliminar", "1");
        }

        // Agregar _method PUT para Laravel
        formData.append("_method", "PUT");

        const categoriaActualizada = await categoriaStore.updateCategoria(
            categoria.value.id,
            formData,
        );

        await Swal.fire({
            title: "¡Categoría actualizada!",
            text: `La categoría "${categoriaActualizada.nombre}" ha sido actualizada exitosamente.`,
            icon: "success",
            timer: 2000,
            showConfirmButton: false,
        });

        // Actualizar datos locales
        categoria.value = categoriaActualizada;
        fillForm(categoriaActualizada);

        // Limpiar previews si existían
        if (preview.logo) URL.revokeObjectURL(preview.logo);
        if (preview.imagen) URL.revokeObjectURL(preview.imagen);
        preview.logo = null;
        preview.imagen = null;
    } catch (error: any) {
        console.error("Error al actualizar categoría:", error);

        if (error.response?.status === 422) {
            // Errores de validación
            errors.value = error.response.data.errors || {};

            Swal.fire({
                title: "Errores de validación",
                text: "Por favor corrige los errores en el formulario.",
                icon: "error",
            });
        } else {
            // Error general
            Swal.fire({
                title: "Error",
                text:
                    error.response?.data?.message ||
                    "No se pudo actualizar la categoría",
                icon: "error",
            });
        }
    } finally {
        loading.value = false;
    }
};

// Lifecycle
onMounted(() => {
    loadCategoria();
});

// Watchers
watch(
    () => form.value.nombre,
    () => {
        if (errors.value.nombre) {
            delete errors.value.nombre;
        }
        if (errors.value.slug) {
            delete errors.value.slug;
        }
    },
);

watch(
    () => form.value.parent_id,
    () => {
        if (errors.value.parent_id) {
            delete errors.value.parent_id;
        }
    },
);

watch(
    () => form.value.descripcion,
    () => {
        if (errors.value.descripcion) {
            delete errors.value.descripcion;
        }
    },
);

watch(
    () => form.value.orden,
    () => {
        if (errors.value.orden) {
            delete errors.value.orden;
        }
    },
);

// Detectar cambios no guardados al salir
watch(hasChanges, (newValue) => {
    if (newValue) {
        window.addEventListener("beforeunload", handleBeforeUnload);
    } else {
        window.removeEventListener("beforeunload", handleBeforeUnload);
    }
});

const handleBeforeUnload = (e: BeforeUnloadEvent) => {
    e.preventDefault();
    e.returnValue = "";
};

// Limpiar previews al desmontar
onUnmounted(() => {
    if (preview.logo) URL.revokeObjectURL(preview.logo);
    if (preview.imagen) URL.revokeObjectURL(preview.imagen);
    window.removeEventListener("beforeunload", handleBeforeUnload);
});
</script>

<style scoped>
.editar-categoria-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.panel-header {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow:
        0 1px 3px 0 rgba(0, 0, 0, 0.1),
        0 1px 2px 0 rgba(0, 0, 0, 0.06);
    padding: 1.5rem;
}

.panel {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow:
        0 1px 3px 0 rgba(0, 0, 0, 0.1),
        0 1px 2px 0 rgba(0, 0, 0, 0.06);
    padding: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.form-label.required::after {
    content: " *";
    color: #ef4444;
}

.form-input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    outline: none;
    transition:
        border-color 0.15s ease-in-out,
        box-shadow 0.15s ease-in-out;
}

.form-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}

.form-input-error {
    border-color: #ef4444;
}

.form-input-error:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 1px #ef4444;
}

.form-textarea {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    outline: none;
    transition:
        border-color 0.15s ease-in-out,
        box-shadow 0.15s ease-in-out;
    resize: vertical;
    min-height: 100px;
}

.form-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}

.form-select {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    outline: none;
    transition:
        border-color 0.15s ease-in-out,
        box-shadow 0.15s ease-in-out;
}

.form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}

.form-checkbox {
    width: 1rem;
    height: 1rem;
    color: #3b82f6;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    outline: none;
}

.form-checkbox:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
}

.form-error {
    font-size: 0.875rem;
    color: #ef4444;
    margin-top: 0.25rem;
}

.form-help {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border: 1px solid transparent;
    font-weight: 500;
    border-radius: 0.375rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    font-size: 0.875rem;
    outline: none;
    transition: all 0.15s ease-in-out;
    cursor: pointer;
    text-decoration: none;
}

.btn:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-primary {
    color: white;
    background-color: #3b82f6;
    border-color: transparent;
}

.btn-primary:hover:not(:disabled) {
    background-color: #2563eb;
}

.btn-secondary {
    color: #374151;
    background-color: white;
    border-color: #d1d5db;
}

.btn-secondary:hover:not(:disabled) {
    background-color: #f9fafb;
}

/* Dark mode styles */
:global(.dark) .panel-header,
:global(.dark) .panel {
    background-color: #1f2937;
}

:global(.dark) .form-label {
    color: #d1d5db;
}

:global(.dark) .form-input,
:global(.dark) .form-textarea,
:global(.dark) .form-select {
    background-color: #374151;
    border-color: #4b5563;
    color: white;
}

:global(.dark) .form-help {
    color: #9ca3af;
}

:global(.dark) .btn-secondary {
    background-color: #374151;
    border-color: #4b5563;
    color: #d1d5db;
}

:global(.dark) .btn-secondary:hover:not(:disabled) {
    background-color: #4b5563;
}
</style>
