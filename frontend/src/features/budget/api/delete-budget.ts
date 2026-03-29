import type { Response } from '@/types/response'
import type { Budget } from '../types'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'
import { getBudgetsQueryKey } from './get-budgets'

const deleteBudget = async (id: string): Promise<Response<Budget>> => {
  try {
    const { data: responseData } = await api.delete<Response<Budget>>(`/budget/${id}`)

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

export const useDeleteBudgetMutation = () => {
  const mutation = useMutation({
    mutationFn: deleteBudget,
    onError: (error) => {
      toast.error(error.message)
    },
    onSuccess: async (responseData, _v, _r, context) => {
      toast.success(responseData.message)
      await context.client.invalidateQueries({
        queryKey: getBudgetsQueryKey(),
      })
    },
  })

  return mutation
}
