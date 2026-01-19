import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { 
    EmpresaDato, 
    EmpresaDatoFormData, 
    EmpresaDatoFilter,
    ApiResponse,
    CLAVES_EMPRESA,
    ClaveEmpresa 
} from '../types/empresa-dato';
import api from '../services/api';

export const useEmpresaDatoStore = defineStore('empresaDato', () => {
    // State
    const empresaDatos = ref<EmpresaDato[]>([]);
    const empresaDato = ref<EmpresaDato | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);
    const clavesDisponibles = ref<Record<ClaveEmpresa, string>>(CLAVES_EMPRESA);
    const paginationMeta = ref({
        total: 0,
        current_page: 1,
        last_page: 1,
        per_page: 15
    });

    // Getters
    const getClavesOptions = computed(() => {
        return Object.entries(clavesDisponibles.value).map(([value, label]) => ({
            value,
            label
        }));
    });

    // Actions
    const fetchEmpresaDatos = async (filters: EmpresaDatoFilter = {}) => {
        loading.value = true;
        error.value = null;
        
        try {
            const params = new URLSearchParams();
            
            Object.entries(filters).forEach(([key, value]) => {
                if (value !== undefined && value !== null && value !== '') {
                    params.append(key, value.toString());
                }
            });

            const response = await api.get<ApiResponse<EmpresaDato[]>>(
                `/empresa-datos?${params.toString()}`
            );

            empresaDatos.value = response.data.data;
            paginationMeta.value = response.data.meta || paginationMeta.value;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar los datos de empresa';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const fetchEmpresaDato = async (id: number) => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await api.get<ApiResponse<EmpresaDato>>(
                `/empresa-datos/${id}`
            );
            
            empresaDato.value = response.data.data;
            return response.data.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar el dato de empresa';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const convertToBoolean = (value: any): boolean => {
        if (typeof value === 'boolean') return value;
        if (typeof value === 'string') return value.toLowerCase() === 'true';
        if (typeof value === 'number') return value === 1;
        return false;
    };

const createEmpresaDato = async (formData: EmpresaDatoFormData) => {
  loading.value = true
  error.value = null
  
  try {
    // DEBUG: Ver qué recibimos del formulario
    console.group('🔄 createEmpresaDato - Input data')
    console.log('formData recibido:', formData)
    console.log('activo value:', formData.activo)
    console.log('activo type:', typeof formData.activo)
    console.log('orden value:', formData.orden)
    console.log('orden type:', typeof formData.orden)
    console.groupEnd()
    
    const data = new FormData()
    
    // DEBUG: Ver qué estamos agregando al FormData
    console.group('📝 Creating FormData')
    
    // Campos como strings
    data.append('clave', formData.clave)
    console.log('clave:', formData.clave)
    
    data.append('titulo', formData.titulo || '')
    console.log('titulo:', formData.titulo || '')
    
    data.append('contenido', formData.contenido || '')
    console.log('contenido:', formData.contenido || '')
    
    // IMPORTANTE: activo como '1' o '0' explícitamente
    const activoString = formData.activo ? '1' : '0'
    data.append('activo', activoString)
    console.log('activo (sent):', activoString, 'type:', typeof activoString)
    
    // orden como string
    const ordenString = String(formData.orden || 0)
    data.append('orden', ordenString)
    console.log('orden (sent):', ordenString, 'type:', typeof ordenString)
    
    // Imagen si existe
    if (formData.imagen && formData.imagen instanceof File) {
      data.append('imagen', formData.imagen)
      console.log('imagen:', formData.imagen.name)
    } else {
      console.log('imagen: none')
    }
    
    console.groupEnd()
    
    // Mostrar el FormData final
    console.log('📦 Final FormData to send:')
    for (let pair of data.entries()) {
      const value = pair[1]
      if (value instanceof File) {
        console.log(`  ${pair[0]}: File "${value.name}"`)
      } else {
        console.log(`  ${pair[0]}: "${value}" (${typeof value})`)
      }
    }

    const response = await api.post<ApiResponse<EmpresaDato>>(
      '/empresa-datos',
      data,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
    )

    console.log('✅ Success response:', response.data)
    
    empresaDatos.value.unshift(response.data.data)
    return response.data.data
  } catch (err: any) {
    console.error('❌ Error in createEmpresaDato:', err)
    
    // DEBUG: Mostrar detalles del error
    if (err.response?.data?.errors) {
      console.error('Validation errors:', err.response.data.errors)
      console.error('Full error response:', err.response.data)
    }
    
    error.value = err.response?.data?.message || 'Error al crear el dato de empresa'
    throw err
  } finally {
    loading.value = false
  }
}

const updateEmpresaDato = async (id: number, formData: EmpresaDatoFormData) => {
  loading.value = true
  error.value = null
  
  try {
    // DEBUG
    console.group('🔄 updateEmpresaDato')
    console.log('ID:', id)
    console.log('formData:', formData)
    console.log('activo:', formData.activo, 'type:', typeof formData.activo)
    console.groupEnd()
    
    const data = new FormData()
    
    // Campos requeridos
    data.append('clave', formData.clave)
    data.append('titulo', formData.titulo || '')
    data.append('contenido', formData.contenido || '')
    data.append('activo', formData.activo ? '1' : '0')
    data.append('orden', String(formData.orden || 0))
    
    // Para Laravel cuando usamos POST para update
    data.append('_method', 'PUT')
    
    // Imagen si existe
    if (formData.imagen && formData.imagen instanceof File) {
      data.append('imagen', formData.imagen)
    }
    
    // Si se solicita eliminar imagen
    if (formData.remove_imagen) {
      data.append('remove_imagen', '1')
    }

    // Mostrar datos a enviar
    console.log('📦 Update FormData:')
    for (let pair of data.entries()) {
      const value = pair[1]
      if (value instanceof File) {
        console.log(`  ${pair[0]}: File`)
      } else {
        console.log(`  ${pair[0]}: "${value}"`)
      }
    }

    const response = await api.post<ApiResponse<EmpresaDato>>(
      `/empresa-datos/${id}`,
      data,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
    )

    // Actualizar en la lista
    const index = empresaDatos.value.findIndex(ed => ed.id === id)
    if (index !== -1) {
      empresaDatos.value[index] = response.data.data
    }

    // Actualizar en el detalle
    if (empresaDato.value?.id === id) {
      empresaDato.value = response.data.data
    }

    return response.data.data
  } catch (err: any) {
    console.error('❌ Error in updateEmpresaDato:', err)
    error.value = err.response?.data?.message || 'Error al actualizar el dato de empresa'
    throw err
  } finally {
    loading.value = false
  }
}

    const deleteEmpresaDato = async (id: number) => {
        loading.value = true;
        error.value = null;
        
        try {
            await api.delete(`/empresa-datos/${id}`);
            
            // Eliminar de la lista
            empresaDatos.value = empresaDatos.value.filter(ed => ed.id !== id);
            
            // Limpiar detalle si es el mismo
            if (empresaDato.value?.id === id) {
                empresaDato.value = null;
            }
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al eliminar el dato de empresa';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const toggleActivo = async (id: number) => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await api.patch<ApiResponse<EmpresaDato>>(
                `/empresa-datos/${id}/toggle-activo`
            );

            // Actualizar en la lista
            const index = empresaDatos.value.findIndex(ed => ed.id === id);
            if (index !== -1) {
                empresaDatos.value[index] = response.data.data;
            }

            // Actualizar en el detalle
            if (empresaDato.value?.id === id) {
                empresaDato.value = response.data.data;
            }

            return response.data.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cambiar el estado';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const fetchClavesDisponibles = async () => {
        try {
            const response = await api.get<ApiResponse<Record<ClaveEmpresa, string>>>(
                '/empresa-datos/claves'  // Cambiado de '/empresa-datos/claves/disponibles'
            );
            clavesDisponibles.value = response.data.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error al cargar las claves disponibles';
            throw err;
        }
    };

    // Reset
    const resetEmpresaDato = () => {
        empresaDato.value = null;
    };

    return {
        // State
        empresaDatos,
        empresaDato,
        loading,
        error,
        clavesDisponibles,
        paginationMeta,
        
        // Getters
        getClavesOptions,
        
        // Actions
        fetchEmpresaDatos,
        fetchEmpresaDato,
        createEmpresaDato,
        updateEmpresaDato,
        deleteEmpresaDato,
        toggleActivo,
        fetchClavesDisponibles,
        resetEmpresaDato
    };
});