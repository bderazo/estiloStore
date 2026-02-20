<template>
    <div class="categorias-index-container">
        <!-- Header -->
        <div class="panel-header">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div>
                    <h1
                        class="text-2xl font-bold text-gray-900 dark:text-white"
                    >
                        Gestión de Categorías
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Administra las categorías del sistema de forma
                        jerárquica
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <RouterLink
                        :to="{ name: 'categorias.crear' }"
                        class="btn btn-primary"
                    >
                        <IconPlus class="w-4 h-4 mr-2" />
                        Crear Categoría
                    </RouterLink>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="panel">
            <div class="space-y-4">
                <!-- Fila 1: Búsqueda y Estado -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Buscar</label>
                        <input
                            v-model="filters.search"
                            type="text"
                            class="form-input"
                            placeholder="Buscar por nombre, descripción o slug..."
                            @input="debouncedSearch"
                        />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <select
                            v-model="filters.activo"
                            class="form-select"
                            @change="applyFilters"
                        >
                            <option value="">Todos</option>
                            <option :value="true">Activo</option>
                            <option :value="false">Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Categoría Padre</label>
                        <select
                            v-model="filters.parent_id"
                            class="form-select"
                            @change="applyFilters"
                        >
                            <option value="">Todas</option>
                            <option value="null">Solo principales</option>
                            <option
                                v-for="option in parentOptions"
                                :key="option.id"
                                :value="option.id"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Fila 2: Opciones de vista y ordenamiento -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label class="form-label">Vista</label>
                        <select
                            v-model="filters.hierarchical"
                            class="form-select"
                            @change="applyFilters"
                        >
                            <option :value="false">Lista plana</option>
                            <option :value="true">Vista jerárquica</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ordenar por</label>
                        <select
                            v-model="filters.sort_by"
                            class="form-select"
                            @change="applyFilters"
                        >
                            <option value="orden">Orden</option>
                            <option value="nombre">Nombre</option>
                            <option value="created_at">Fecha creación</option>
                            <option value="updated_at">
                                Última actualización
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dirección</label>
                        <select
                            v-model="filters.sort_order"
                            class="form-select"
                            @change="applyFilters"
                        >
                            <option value="asc">Ascendente</option>
                            <option value="desc">Descendente</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Por página</label>
                        <select
                            v-model="filters.per_page"
                            class="form-select"
                            @change="applyFilters"
                        >
                            <option :value="10">10</option>
                            <option :value="15">15</option>
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                        </select>
                    </div>
                </div>

                <!-- Botones de acción para filtros -->
                <div class="flex items-center gap-2">
                    <button
                        @click="clearFilters"
                        class="btn btn-secondary btn-sm"
                    >
                        <IconRefresh class="w-4 h-4 mr-1" />
                        Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla/Lista de Categorías -->
        <div class="panel">
            <!-- Loading -->
            <div v-if="loading" class="flex justify-center items-center py-8">
                <div
                    class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"
                ></div>
                <span class="ml-2 text-gray-600 dark:text-gray-400"
                    >Cargando categorías...</span
                >
            </div>

            <!-- Sin resultados -->
            <div v-else-if="categorias.length === 0" class="text-center py-8">
                <IconFolder class="w-16 h-16 mx-auto text-gray-400 mb-4" />
                <h3
                    class="text-lg font-medium text-gray-900 dark:text-white mb-2"
                >
                    No se encontraron categorías
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    {{
                        hasActiveFilters
                            ? "No hay categorías que coincidan con los filtros aplicados"
                            : "Aún no hay categorías creadas"
                    }}
                </p>
                <RouterLink
                    v-if="!hasActiveFilters"
                    :to="{ name: 'categorias.crear' }"
                    class="btn btn-primary"
                >
                    <IconPlus class="w-4 h-4 mr-2" />
                    Crear Primera Categoría
                </RouterLink>
                <button v-else @click="clearFilters" class="btn btn-secondary">
                    <IconRefresh class="w-4 h-4 mr-2" />
                    Limpiar Filtros
                </button>
            </div>

            <!-- Tabla con datos -->
            <div v-else class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="table-th text-left">
                                <div class="flex items-center space-x-1">
                                    <span>Categoría</span>
                                    <button
                                        @click="toggleSort('nombre')"
                                        class="text-gray-400 hover:text-gray-600"
                                    >
                                        <IconArrowsUpDown class="w-4 h-4" />
                                    </button>
                                </div>
                            </th>
                            <th class="table-th text-left">Descripción</th>
                            <th class="table-th text-center">
                                <div
                                    class="flex items-center justify-center space-x-1"
                                >
                                    <span>Orden</span>
                                    <button
                                        @click="toggleSort('orden')"
                                        class="text-gray-400 hover:text-gray-600"
                                    >
                                        <IconArrowsUpDown class="w-4 h-4" />
                                    </button>
                                </div>
                            </th>
                            <th class="table-th text-center">Estado</th>
                            <th class="table-th text-center">Subcategorías</th>
                            <th class="table-th text-center">
                                <div
                                    class="flex items-center justify-center space-x-1"
                                >
                                    <span>Fecha</span>
                                    <button
                                        @click="toggleSort('created_at')"
                                        class="text-gray-400 hover:text-gray-600"
                                    >
                                        <IconArrowsUpDown class="w-4 h-4" />
                                    </button>
                                </div>
                            </th>
                            <th class="table-th text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="categoria in categorias"
                            :key="categoria.id"
                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800"
                        >
                            <!-- Nombre con jerarquía -->
                            <!-- Reemplaza el div de la imagen actual (línea ~150) -->
                            <td class="table-td">
                                <div class="flex items-center">
                                    <!-- Contenedor para logo/imagen -->
                                    <div class="flex items-center space-x-3">
                                        <!-- Mostrar logo si existe -->
                                        <div
                                            v-if="
                                                categoria.logo ||
                                                categoria.imagen
                                            "
                                            class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 relative"
                                        >
                                            <!-- Prioridad: logo primero -->
                                            <img
                                                v-if="categoria.logo"
                                                :src="
                                                    getImageUrl(categoria.logo)
                                                "
                                                :alt="categoria.nombre"
                                                class="h-8 w-8 rounded-full object-cover"
                                            />
                                            <!-- Si no hay logo pero sí imagen -->
                                            <img
                                                v-else-if="categoria.imagen"
                                                :src="
                                                    getImageUrl(
                                                        categoria.imagen,
                                                    )
                                                "
                                                :alt="categoria.nombre"
                                                class="h-8 w-8 rounded-full object-cover"
                                            />

                                            <!-- Badge para indicar que es logo (opcional) -->
                                            <div
                                                v-if="categoria.logo"
                                                class="absolute -top-1 -right-1"
                                            >
                                                <span
                                                    class="bg-blue-500 text-white text-xs px-1 py-0.5 rounded-full"
                                                >
                                                    L
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Icono por defecto si no hay logo ni imagen -->
                                        <div
                                            v-else
                                            class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700"
                                        >
                                            <IconFolder
                                                class="w-5 h-5 text-gray-500"
                                            />
                                        </div>

                                        <!-- Indentación para jerarquía -->
                                        <div
                                            v-if="categoria.level > 0"
                                            class="flex items-center mr-2"
                                            :style="{
                                                marginLeft: `${categoria.level * 20}px`,
                                            }"
                                        >
                                            <span class="text-gray-400"
                                                >└─</span
                                            >
                                        </div>

                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <!-- Icono según nivel (ahora más pequeño) -->
                                            <div class="flex-shrink-0">
                                                <IconFolder
                                                    v-if="categoria.level === 0"
                                                    class="w-4 h-4 text-yellow-500"
                                                />
                                                <IconFolderOpen
                                                    v-else
                                                    class="w-4 h-4 text-blue-500"
                                                />
                                            </div>

                                            <div>
                                                <div
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{ categoria.nombre }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ categoria.slug }}
                                                </div>
                                                <div
                                                    v-if="categoria.parent"
                                                    class="text-xs text-gray-400 mt-1"
                                                >
                                                    Padre:
                                                    {{
                                                        categoria.parent.nombre
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Descripción -->
                            <td class="table-td">
                                <div class="max-w-xs">
                                    <p
                                        v-if="categoria.descripcion"
                                        class="text-sm text-gray-600 dark:text-gray-400 truncate"
                                        :title="categoria.descripcion"
                                    >
                                        {{ categoria.descripcion }}
                                    </p>
                                    <span
                                        v-else
                                        class="text-xs text-gray-400 italic"
                                        >Sin descripción</span
                                    >
                                </div>
                            </td>

                            <!-- Orden -->
                            <td class="table-td text-center">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full text-sm font-medium"
                                >
                                    {{ categoria.orden }}
                                </span>
                            </td>

                            <!-- Estado -->
                            <td class="table-td text-center">
                                <button
                                    @click="toggleEstado(categoria)"
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors',
                                        categoria.activo
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800',
                                    ]"
                                    :title="
                                        categoria.activo
                                            ? 'Click para desactivar'
                                            : 'Click para activar'
                                    "
                                >
                                    <IconCheck
                                        v-if="categoria.activo"
                                        class="w-3 h-3 mr-1"
                                    />
                                    <IconX v-else class="w-3 h-3 mr-1" />
                                    {{
                                        categoria.activo ? "Activo" : "Inactivo"
                                    }}
                                </button>
                            </td>

                            <!-- Subcategorías -->
                            <td class="table-td text-center">
                                <div class="flex items-center justify-center">
                                    <span
                                        :class="[
                                            'inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-medium',
                                            categoria.children_count > 0
                                                ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                                : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                                        ]"
                                    >
                                        {{ categoria.children_count }}
                                    </span>
                                </div>
                            </td>

                            <!-- Fecha -->
                            <td class="table-td text-center">
                                <div
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                >
                                    {{ formatDate(categoria.created_at) }}
                                </div>
                            </td>

                            <!-- Acciones -->
                            <td class="table-td text-center">
                                <div
                                    class="flex items-center justify-center space-x-2"
                                >
                                    <!-- Ver -->
                                    <button
                                        @click="verCategoria(categoria)"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                                        title="Ver detalles"
                                    >
                                        <IconEye class="w-4 h-4" />
                                    </button>

                                    <!-- Editar -->
                                    <RouterLink
                                        :to="{
                                            name: 'categorias.editar',
                                            params: { id: categoria.id },
                                        }"
                                        class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200"
                                        title="Editar"
                                    >
                                        <IconEdit class="w-4 h-4" />
                                    </RouterLink>

                                    <!-- Eliminar -->
                                    <button
                                        @click="eliminarCategoria(categoria)"
                                        :disabled="categoria.children_count > 0"
                                        :class="[
                                            'transition-colors',
                                            categoria.children_count > 0
                                                ? 'text-gray-400 cursor-not-allowed'
                                                : 'text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200',
                                        ]"
                                        :title="
                                            categoria.children_count > 0
                                                ? 'No se puede eliminar una categoría con subcategorías'
                                                : 'Eliminar'
                                        "
                                    >
                                        <IconTrash class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div v-if="pagination && pagination.last_page > 1" class="mt-6">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                >
                    <!-- Info de paginación -->
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Mostrando
                        {{
                            (pagination.current_page - 1) *
                                pagination.per_page +
                            1
                        }}
                        a
                        {{
                            Math.min(
                                pagination.current_page * pagination.per_page,
                                pagination.total,
                            )
                        }}
                        de {{ pagination.total }} resultados
                    </div>

                    <!-- Controles de paginación -->
                    <div class="flex items-center space-x-2">
                        <!-- Primera página -->
                        <button
                            @click="changePage(1)"
                            :disabled="pagination.current_page === 1"
                            class="pagination-btn"
                            title="Primera página"
                        >
                            <IconChevronsLeft class="w-4 h-4" />
                        </button>

                        <!-- Página anterior -->
                        <button
                            @click="changePage(pagination.current_page - 1)"
                            :disabled="pagination.current_page === 1"
                            class="pagination-btn"
                            title="Página anterior"
                        >
                            <IconChevronLeft class="w-4 h-4" />
                        </button>

                        <!-- Números de página -->
                        <template v-for="page in visiblePages" :key="page">
                            <button
                                v-if="page !== '...'"
                                @click="changePage(page as number)"
                                :class="[
                                    'pagination-btn',
                                    page === pagination.current_page
                                        ? 'pagination-btn-active'
                                        : '',
                                ]"
                            >
                                {{ page }}
                            </button>
                            <span v-else class="px-2 text-gray-500">...</span>
                        </template>

                        <!-- Página siguiente -->
                        <button
                            @click="changePage(pagination.current_page + 1)"
                            :disabled="
                                pagination.current_page === pagination.last_page
                            "
                            class="pagination-btn"
                            title="Página siguiente"
                        >
                            <IconChevronRight class="w-4 h-4" />
                        </button>

                        <!-- Última página -->
                        <button
                            @click="changePage(pagination.last_page)"
                            :disabled="
                                pagination.current_page === pagination.last_page
                            "
                            class="pagination-btn"
                            title="Última página"
                        >
                            <IconChevronsRight class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de detalles -->
        <div
            v-if="selectedCategoria"
            class="fixed inset-0 z-50 overflow-y-auto"
            @click.self="selectedCategoria = null"
        >
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50"></div>
                <div
                    class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full"
                >
                    <!-- Header del modal -->
                    <div
                        class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white"
                        >
                            Detalles de Categoría
                        </h3>
                        <button
                            @click="selectedCategoria = null"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        >
                            <IconX class="w-6 h-6" />
                        </button>
                    </div>

                    <!-- Contenido del modal -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Información básica -->
                            <div>
                                <h4
                                    class="text-sm font-medium text-gray-900 dark:text-white mb-3"
                                >
                                    Información Básica
                                </h4>
                                <dl class="space-y-2">
                                    <!-- Agregar visualización del logo -->
                                    <div
                                        v-if="
                                            selectedCategoria.logo ||
                                            selectedCategoria.imagen
                                        "
                                        class="flex items-center space-x-2"
                                    >
                                        <img
                                            :src="
                                                selectedCategoria.logo
                                                    ? getImageUrl(
                                                          selectedCategoria.logo,
                                                      )
                                                    : getImageUrl(
                                                          selectedCategoria.imagen!,
                                                      )
                                            "
                                            :alt="selectedCategoria.nombre"
                                            class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-700"
                                        />
                                        <div class="text-xs text-gray-500">
                                            {{
                                                selectedCategoria.logo
                                                    ? "Logo"
                                                    : "Imagen"
                                            }}
                                            de la categoría
                                        </div>
                                    </div>
                                    <div
                                        v-else
                                        class="text-sm text-gray-400 italic"
                                    >
                                        Sin imagen ni logo
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Nombre:
                                        </dt>
                                        <dd
                                            class="text-sm font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ selectedCategoria.nombre }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Slug:
                                        </dt>
                                        <dd
                                            class="text-sm font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ selectedCategoria.slug }}
                                        </dd>
                                    </div>
                                    <div v-if="selectedCategoria.descripcion">
                                        <dt
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Descripción:
                                        </dt>
                                        <dd
                                            class="text-sm text-gray-900 dark:text-white"
                                        >
                                            {{ selectedCategoria.descripcion }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Jerarquía -->
                            <div>
                                <h4
                                    class="text-sm font-medium text-gray-900 dark:text-white mb-3"
                                >
                                    Jerarquía
                                </h4>
                                <dl class="space-y-2">
                                    <div>
                                        <dt
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Nivel:
                                        </dt>
                                        <dd
                                            class="text-sm font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ selectedCategoria.level }}
                                        </dd>
                                    </div>
                                    <div v-if="selectedCategoria.parent">
                                        <dt
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Categoría Padre:
                                        </dt>
                                        <dd
                                            class="text-sm font-medium text-gray-900 dark:text-white"
                                        >
                                            {{
                                                selectedCategoria.parent.nombre
                                            }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Subcategorías:
                                        </dt>
                                        <dd
                                            class="text-sm font-medium text-gray-900 dark:text-white"
                                        >
                                            {{
                                                selectedCategoria.children_count
                                            }}
                                            subcategorías
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Ruta:
                                        </dt>
                                        <dd
                                            class="text-sm text-gray-900 dark:text-white"
                                        >
                                            {{ selectedCategoria.path }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Estado y fechas -->
                            <div class="md:col-span-2">
                                <h4
                                    class="text-sm font-medium text-gray-900 dark:text-white mb-3"
                                >
                                    Estado y Fechas
                                </h4>
                                <dl
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4"
                                >
                                    <div>
                                        <dt
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Estado:
                                        </dt>
                                        <dd>
                                            <span
                                                :class="[
                                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                    selectedCategoria.activo
                                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                ]"
                                            >
                                                {{
                                                    selectedCategoria.activo
                                                        ? "Activo"
                                                        : "Inactivo"
                                                }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Creado:
                                        </dt>
                                        <dd
                                            class="text-sm text-gray-900 dark:text-white"
                                        >
                                            {{
                                                formatDate(
                                                    selectedCategoria.created_at,
                                                )
                                            }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            Actualizado:
                                        </dt>
                                        <dd
                                            class="text-sm text-gray-900 dark:text-white"
                                        >
                                            {{
                                                formatDate(
                                                    selectedCategoria.updated_at,
                                                )
                                            }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Footer del modal -->
                    <div
                        class="flex justify-end p-6 border-t border-gray-200 dark:border-gray-700 space-x-2"
                    >
                        <RouterLink
                            :to="{
                                name: 'categorias.editar',
                                params: { id: selectedCategoria.id },
                            }"
                            class="btn btn-primary"
                        >
                            <IconEdit class="w-4 h-4 mr-2" />
                            Editar
                        </RouterLink>
                        <button
                            @click="selectedCategoria = null"
                            class="btn btn-secondary"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { storeToRefs } from "pinia";
import { RouterLink } from "vue-router";
import { useCategoriaStore } from "../../stores/categoriaStore";
import Swal from "sweetalert2";

// Icons
import {
    IconPlus,
    IconRefresh,
    IconDownload,
    IconFolder,
    IconFolderOpen,
    IconArrowsUpDown,
    IconCheck,
    IconX,
    IconEye,
    IconEdit,
    IconTrash,
    IconChevronLeft,
    IconChevronRight,
    IconChevronsLeft,
    IconChevronsRight,
} from "@tabler/icons-vue";

import type { Categoria } from "../../types/categoria";

// Store
const categoriaStore = useCategoriaStore();

// Reactive data
const selectedCategoria = ref<Categoria | null>(null);

// Computeds
const { categorias, loading, filters, pagination, parentOptions } =
    storeToRefs(categoriaStore);

const hasActiveFilters = computed(() => {
    return (
        filters.value.search !== "" ||
        filters.value.activo !== null ||
        filters.value.parent_id !== null
    );
});

const visiblePages = computed(() => {
    if (!pagination.value) return [];

    const current = pagination.value.current_page;
    const last = pagination.value.last_page;
    const pages: (number | string)[] = [];

    if (last <= 7) {
        for (let i = 1; i <= last; i++) {
            pages.push(i);
        }
    } else {
        pages.push(1);

        if (current > 4) {
            pages.push("...");
        }

        const start = Math.max(2, current - 2);
        const end = Math.min(last - 1, current + 2);

        for (let i = start; i <= end; i++) {
            pages.push(i);
        }

        if (current < last - 3) {
            pages.push("...");
        }

        pages.push(last);
    }

    return pages;
});

// Methods
const debouncedSearch = () => {
    applyFilters();
};

const applyFilters = () => {
    categoriaStore.fetchCategorias();
};

const clearFilters = () => {
    categoriaStore.clearFilters();
    categoriaStore.fetchCategorias();
};

const toggleSort = (field: string) => {
    if (filters.value.sort_by === field) {
        filters.value.sort_order =
            filters.value.sort_order === "asc" ? "desc" : "asc";
    } else {
        filters.value.sort_by = field as any;
        filters.value.sort_order = "asc";
    }
    applyFilters();
};

const changePage = (page: number) => {
    categoriaStore.changePage(page);
};

const verCategoria = (categoria: Categoria) => {
    selectedCategoria.value = categoria;
};

const toggleEstado = async (categoria: Categoria) => {
    try {
        const result = await Swal.fire({
            title: "¿Cambiar estado?",
            text: `¿Estás seguro de ${categoria.activo ? "desactivar" : "activar"} esta categoría?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, cambiar",
            cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
            await categoriaStore.toggleActivo(categoria.id);

            Swal.fire({
                title: "¡Estado actualizado!",
                text: `La categoría ha sido ${categoria.activo ? "desactivada" : "activada"} correctamente.`,
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        Swal.fire({
            title: "Error",
            text: "No se pudo cambiar el estado de la categoría",
            icon: "error",
        });
    }
};

const eliminarCategoria = async (categoria: Categoria) => {
    if (categoria.children_count > 0) {
        Swal.fire({
            title: "No se puede eliminar",
            text: "Esta categoría tiene subcategorías asociadas. Elimina primero las subcategorías.",
            icon: "warning",
        });
        return;
    }

    try {
        const result = await Swal.fire({
            title: "¿Eliminar categoría?",
            text: `¿Estás seguro de eliminar "${categoria.nombre}"? Esta acción no se puede deshacer.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
            await categoriaStore.deleteCategoria(categoria.id);

            Swal.fire({
                title: "¡Eliminada!",
                text: "La categoría ha sido eliminada correctamente.",
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        Swal.fire({
            title: "Error",
            text: "No se pudo eliminar la categoría",
            icon: "error",
        });
    }
};

const getImageUrl = (path: string) => {
    if (!path) return "/assets/images/placeholder.png";
    if (path.startsWith("http")) return path;
    return `/storage/${path}`;
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString("es-ES", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Lifecycle
onMounted(async () => {
    await Promise.all([
        categoriaStore.fetchCategorias(),
        categoriaStore.fetchParentOptions(),
    ]);
});

// Watchers
watch(
    () => filters.value.hierarchical,
    () => {
        applyFilters();
    },
);
</script>

<style scoped>
.categorias-index-container {
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
}

.btn:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}

.btn-primary {
    color: white;
    background-color: #3b82f6;
    border-color: transparent;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    color: #374151;
    background-color: white;
    border-color: #d1d5db;
}

.btn-secondary:hover {
    background-color: #f9fafb;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
}

.table-th {
    padding: 0.75rem 1.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.table-td {
    padding: 1rem 1.5rem;
    white-space: nowrap;
}

.pagination-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    background-color: white;
    border: 1px solid #d1d5db;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
}

.pagination-btn:hover:not(:disabled) {
    background-color: #f9fafb;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-btn-active {
    color: #3b82f6;
    background-color: #eff6ff;
    border-color: #3b82f6;
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
:global(.dark) .form-select {
    background-color: #374151;
    border-color: #4b5563;
    color: white;
}

:global(.dark) .btn-secondary {
    background-color: #374151;
    border-color: #4b5563;
    color: #d1d5db;
}

:global(.dark) .btn-secondary:hover {
    background-color: #4b5563;
}

:global(.dark) .table-th {
    color: #9ca3af;
}

:global(.dark) .pagination-btn {
    background-color: #1f2937;
    border-color: #4b5563;
    color: #9ca3af;
}

:global(.dark) .pagination-btn:hover:not(:disabled) {
    background-color: #374151;
}

:global(.dark) .pagination-btn-active {
    background-color: #1e3a8a;
    color: #bfdbfe;
}
</style>
