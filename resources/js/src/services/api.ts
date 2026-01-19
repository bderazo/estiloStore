import axios from 'axios'
import router from '../router/index'

// Configuración base de Axios
const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Interceptor para añadir el token de autorización
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    
    // DEBUG: Mostrar información de la petición
    if (process.env.NODE_ENV === 'development') {
      console.group('📤 API Request')
      console.log('URL:', config.url)
      console.log('Method:', config.method?.toUpperCase())
      console.log('Headers:', config.headers)
      
      // Si es FormData, mostrar sus contenidos
      if (config.data instanceof FormData) {
        console.log('📋 FormData contents:')
        for (let pair of config.data.entries()) {
          const value = pair[1]
          let displayValue = value
          let valueType = typeof value
          
          // Para archivos, mostrar info del archivo
          if (value instanceof File) {
            displayValue = `📎 File: ${value.name} (${value.type}, ${value.size} bytes)`
            valueType = 'object'
          }
          
          console.log(`  ${pair[0]}:`, displayValue, `(type: ${valueType})`)
        }
      } else {
        console.log('Data:', config.data)
      }
      console.groupEnd()
    }
    
    return config
  },
  (error) => {
    console.error('❌ Request error:', error)
    return Promise.reject(error)
  }
)

// Interceptor para manejar respuestas y errores
api.interceptors.response.use(
  (response) => {
    // DEBUG: Mostrar información de la respuesta exitosa
    if (process.env.NODE_ENV === 'development') {
      console.group('✅ API Response')
      console.log('URL:', response.config.url)
      console.log('Status:', response.status)
      console.log('Data:', response.data)
      console.groupEnd()
    }
    
    return response
  },
  (error) => {
    // DEBUG: Mostrar información del error
    if (process.env.NODE_ENV === 'development') {
      console.group('❌ API Error')
      console.log('URL:', error.config?.url)
      console.log('Method:', error.config?.method?.toUpperCase())
      console.log('Status:', error.response?.status)
      console.log('Error message:', error.response?.data?.message)
      console.log('Validation errors:', error.response?.data?.errors)
      
      // Mostrar los datos que se enviaron
      if (error.config?.data instanceof FormData) {
        console.log('📋 Sent FormData:')
        for (let pair of error.config.data.entries()) {
          const value = pair[1]
          let displayValue = value
          
          if (value instanceof File) {
            displayValue = `📎 File: ${value.name}`
          }
          
          console.log(`  ${pair[0]}:`, displayValue)
        }
      } else {
        console.log('Sent data:', error.config?.data)
      }
      console.groupEnd()
    }
    
    if (error.response?.status === 401) {
      // Token expirado o inválido
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      router.push('/login')
    }
    
    return Promise.reject(error)
  }
)

export default api