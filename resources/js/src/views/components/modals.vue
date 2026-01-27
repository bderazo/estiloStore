<!-- src/views/transportes/IndexView.vue -->

<template>
    <div class="transportes-container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Gestión de Transportes</h1>
                <p class="text-muted mb-0">Administre las rutas de transporte y cooperativas</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-secondary" @click="exportToExcel">
                    <i class="fas fa-file-export me-2"></i>Exportar
                </button>
                <router-link to="/administrador/transportes/crear" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Transporte
                </router-link>
            </div>
        </div>
        
        <!-- Dashboard -->
        <div v-if="estadisticas" class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Rutas</h6>
                        <h2 class="mb-0">{{ estadisticas.total }}</h2>
                        <small>Activas: {{ estadisticas.activos }} | Inactivas: {{ estadisticas.inactivos }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Precio Promedio</h6>
                        <h2 class="mb-0">{{ formatCurrency(estadisticas.precio_promedio) }}</h2>
                        <small>Por ruta</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Cooperativas</h6>
                        <h2 class="mb-0">{{ estadisticas.cooperativas_unicas }}</h2>
                        <small>Registradas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6 class="card-title">Tiempo Promedio</h6>
                        <h2 class="mb-0">{{ formatTime(estadisticas.tiempo_promedio) }}</h2>
                        <small>Por ruta</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form @submit.prevent="applyFilters" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Buscar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input 
                                type="text" 
                                id="search"
                                class="form-control"
                                placeholder="Ruta o cooperativa..."
                                v-model="filtros.search"
                                @input="debouncedSearch"
                            >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="cooperativa" class="form-label">Cooperativa</label>
                        <select 
                            id="cooperativa" 
                            class="form-select"
                            v-model="filtros.cooperativa"
                        >
                            <option value="">Todas</option>
                            <option v-for="coop in cooperativas" :value="coop" :key="coop">
                                {{ coop }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="estado" class="form-label">Estado</label>
                        <select 
                            id="estado" 
                            class="form-select"
                            v-model="filtros.estado"
                        >
                            <option value="">Todos</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="order_by" class="form-label">Ordenar por</label>
                        <select 
                            id="order_by" 
                            class="form-select"
                            v-model="filtros.order_by"
                            @change="applyFilters"
                        >
                            <option value="ruta">Ruta</option>
                            <option value="precio">Precio</option>
                            <option value="cooperativa">Cooperativa</option>
                            <option value="created_at">Fecha Creación</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="per_page" class="form-label">Items por página</label>
                        <select 
                            id="per_page" 
                            class="form-select"
                            v-model="filtros.per_page"
                            @change="applyFilters"
                        >
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button 
                            type="button" 
                            class="btn btn-outline-secondary w-100"
                            @click="resetFilters"
                            title="Limpiar filtros"
                        >
                            <i class="fas fa-filter-circle-xmark"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tabla -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="25%">Ruta</th>
                                <th width="20%">Cooperativa</th>
                                <th width="15%">Precio</th>
                                <th width="15%">Tiempo Estimado</th>
                                <th width="10%">Estado</th>
                                <th width="15%" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="loading">
                                <td colspan="6" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-else-if="transportes.length === 0">
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No se encontraron transportes
                                </td>
                            </tr>
                            <tr v-for="transporte in transportes" :key="transporte.id">
                                <td class="align-middle">
                                    <strong>{{ transporte.ruta }}</strong>
                                    <div class="text-muted small">
                                        ID: {{ transporte.id }}
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        {{ transporte.cooperativa }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span class="fw-bold text-success">
                                        {{ transporte.precio_formateado }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span v-if="transporte.tiempo_estimado_formateado" class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ transporte.tiempo_estimado_formateado }}
                                    </span>
                                    <span v-else class="text-muted">No especificado</span>
                                </td>
                                <td class="align-middle">
                                    <span 
                                        :class="['badge', transporte.estado ? 'bg-success' : 'bg-secondary']"
                                        @click="toggleEstado(transporte.id)"
                                        style="cursor: pointer;"
                                        title="Click para cambiar estado"
                                    >
                                        {{ transporte.estado_label }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <router-link 
                                            :to="`/administrador/transportes/${transporte.id}/editar`"
                                            class="btn btn-outline-primary"
                                            title="Editar"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                        <button 
                                            type="button" 
                                            class="btn btn-outline-danger"
                                            @click="confirmDelete(transporte)"
                                            title="Eliminar"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Paginación -->
            <div v-if="meta && meta.last_page > 1" class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Mostrando {{ meta.from }} a {{ meta.to }} de {{ meta.total }} registros
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item" :class="{ disabled: meta.current_page === 1 }">
                                <button 
                                    class="page-link" 
                                    @click="changePage(meta.current_page - 1)"
                                    :disabled="meta.current_page === 1"
                                >
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            </li>
                            
                            <li 
                                v-for="page in pageRange" 
                                :key="page"
                                class="page-item"
                                :class="{ active: page === meta.current_page }"
                            >
                                <button 
                                    class="page-link" 
                                    @click="changePage(page)"
                                >
                                    {{ page }}
                                </button>
                            </li>
                            
                            <li class="page-item" :class="{ disabled: meta.current_page === meta.last_page }">
                                <button 
                                    class="page-link" 
                                    @click="changePage(meta.current_page + 1)"
                                    :disabled="meta.current_page === meta.last_page"
                                >
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { useTransporteStore } from '../../stores/transporteStore';
import { useConfirm } from '../../composables/useConfirm';
import { debounce } from 'lodash';
import type { Transporte } from '../../types/transporte';

const transporteStore = useTransporteStore();
const { confirm } = useConfirm();

const transportes = computed(() => transporteStore.transportes);
const estadisticas = computed(() => transporteStore.estadisticas);
const cooperativas = computed(() => transporteStore.cooperativas);
const loading = computed(() => transporteStore.loading);
const filtros = computed(() => transporteStore.filtros);
const meta = computed(() => transporteStore.meta);

const pageRange = computed(() => {
    if (!meta.value) return [];
    
    const current = meta.value.current_page;
    const last = meta.value.last_page;
    const delta = 2;
    const range = [];
    
    for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
        range.push(i);
    }
    
    if (current - delta > 2) range.unshift('...');
    if (current + delta < last - 1) range.push('...');
    
    range.unshift(1);
    if (last > 1) range.push(last);
    
    return range;
});

onMounted(async () => {
    await Promise.all([
        transporteStore.fetchTransportes(),
        transporteStore.fetchCooperativas(),
        transporteStore.fetchEstadisticas()
    ]);
});

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    }).format(amount);
};

const formatTime = (hours: number | null) => {
    if (!hours) return 'N/A';
    
    const h = Math.floor(hours);
    const m = Math.round((hours - h) * 60);
    
    if (h > 0 && m > 0) return `${h}h ${m}m`;
    if (h > 0) return `${h}h`;
    return `${m}m`;
};

const applyFilters = async () => {
    await transporteStore.fetchTransportes();
};

const resetFilters = async () => {
    transporteStore.resetFiltros();
    await transporteStore.fetchTransportes();
};

const debouncedSearch = debounce(async () => {
    await transporteStore.fetchTransportes();
}, 500);

const changePage = async (page: number | string) => {
    if (typeof page === 'string' || page < 1 || page > meta.value!.last_page) return;
    
    await transporteStore.fetchTransportes({ page });
};

const toggleEstado = async (id: number) => {
    const confirmed = await confirm('¿Cambiar estado del transporte?', 'Esta acción cambiará el estado activo/inactivo del transporte seleccionado.');
    
    if (confirmed) {
        await transporteStore.toggleEstado(id);
    }
};

const confirmDelete = async (transporte: Transporte) => {
    const confirmed = await confirm(
        '¿Eliminar transporte?', 
        `¿Está seguro de eliminar la ruta "${transporte.ruta}" - ${transporte.cooperativa}? Esta acción no se puede deshacer.`
    );
    
    if (confirmed) {
        await transporteStore.deleteTransporte(transporte.id);
    }
};

const exportToExcel = async () => {
    await transporteStore.exportToExcel();
};

// Watch for filter changes
watch(() => [filtros.value.cooperativa, filtros.value.estado], () => {
    applyFilters();
});
</script>

<style scoped>
.transportes-container {
    padding: 20px;
}

.badge {
    cursor: pointer;
    transition: opacity 0.2s;
}

.badge:hover {
    opacity: 0.8;
}

.table td {
    vertical-align: middle;
}

.page-link {
    cursor: pointer;
}

.btn-group .btn {
    border-radius: 0.25rem !important;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card.bg-primary, 
.card.bg-success, 
.card.bg-info, 
.card.bg-warning {
    border: none;
}

.card.bg-primary:hover, 
.card.bg-success:hover, 
.card.bg-info:hover, 
.card.bg-warning:hover {
    transform: translateY(-2px);
    transition: transform 0.2s;
}
</style>