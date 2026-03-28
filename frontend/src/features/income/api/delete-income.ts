import type { Response } from '@/types/response'
import type { Income } from '../types'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'

const deleteIncome = async (id: string): Promise<Response<Income>> => {
  try {
    const { data: responseData } = await api.delete<Response<Income>>(`/income/${id}`)

    if (!responseData.success) {
      throw new Error(responseData.message)
    }

    return responseData
  } catch (error) {
    if (error instanceof Error) throw error

    const { message } = parseAxiosError(error)
    throw new Error(message)
  }
}

export const useDeleteIncomeMutation = () => {
  const mutation = useMutation({
    mutationFn: deleteIncome,
    onError: (error) => {
      toast.error(error.message)
    },
    onSuccess: async (responseData, _v, _r, context) => {
      toast.success(responseData.message)
      await context.client.invalidateQueries({
        queryKey: ['incomes'],
      })
    },
  })

  return mutation
}
