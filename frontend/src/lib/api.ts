import { token } from '@/utils/token'
import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
})

api.interceptors.request.use((config) => {
  const accessToken = token.getToken()
  if (accessToken) config.headers.Authorization = `Bearer ${accessToken}`
  return config
})

export default api
