import type { LoginDto } from '@/features/auth/validation/login'
import api from '@/lib/api'
import type { Response, ResponseSuccess } from '@/types/response'
import type { User } from '@/types/user'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { token } from '@/utils/token'
import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'

type LoginResponseData = {
  user: User
  token: string
}

export const useAuthStore = defineStore('auth', () => {
  const router = useRouter()

  const user = ref<User | null>(null)
  const isInitialized = ref(false)
  const isLoggingOut = ref(false)

  const isAuthenticated = computed(() => !!user.value)

  const getUser = async () => {
    try {
      const { data: responseData } = await api.get<ResponseSuccess<User>>('/auth')

      user.value = responseData.data

      isInitialized.value = true

      return {
        success: true,
        data: null,
        message: 'ok',
      }
    } catch (error) {
      isInitialized.value = true

      const { message } = parseAxiosError(error)

      return {
        success: false,
        message,
        data: null,
      }
    }
  }

  const login = async (input: LoginDto): Promise<Response<LoginResponseData>> => {
    try {
      const { data: responseData } = await api.post<Response<LoginResponseData>>(
        '/auth/login',
        input,
      )

      if (!responseData.success) {
        return {
          success: false,
          message: responseData.message,
          data: null,
        }
      }

      token.setToken(responseData.data.token)

      user.value = responseData.data.user

      return responseData
    } catch (e) {
      const { message } = parseAxiosError(e)

      return {
        success: false,
        message: message,
        data: null,
      }
    }
  }

  const logout = async (): Promise<Response> => {
    try {
      isLoggingOut.value = true
      const { data: responseData } = await api.post<ResponseSuccess>('/auth/logout')

      token.removeToken()
      user.value = null

      router.push('/login')

      return responseData
    } catch (error) {
      const { message } = parseAxiosError(error)

      return {
        success: false,
        data: null,
        message,
      }
    } finally {
      isLoggingOut.value = false
    }
  }

  const refreshUser = async () => {
    isInitialized.value = false
    await getUser()
  }

  return { isAuthenticated, isInitialized, isLoggingOut, user, login, logout, getUser, refreshUser }
})
