import { isAxiosError } from 'axios'

interface ParsedError {
  message: string
  status: number | string | null
}

export const parseAxiosError = (error: unknown): ParsedError => {
  if (!isAxiosError(error)) {
    return {
      message: 'An unexpected error occurred',
      status: null,
    }
  }

  const isTimeout = error.code === 'ECONNABORTED'
  const isNetworkError = !error.response && !!error.request

  let message = error.response?.data.message ?? error.message ?? 'Unknown error'

  if (isTimeout) message = 'Request timed out. Try again.'

  if (isNetworkError) message = 'No internet connection'

  return {
    status: error.response?.status ?? null,
    message,
  }
}
