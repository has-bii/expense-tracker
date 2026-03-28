import type { Response } from '@/types/response'
import type { Expense } from '../types'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'

const deleteExpense = async (id: string): Promise<Response<Expense>> => {
  try {
    const { data: responseData } = await api.delete<Response<Expense>>(`/expense/${id}`)

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

export const useDeleteExpenseMutation = () => {
  const mutation = useMutation({
    mutationFn: deleteExpense,
    onError: (error) => {
      toast.error(error.message)
    },
    onSuccess: async (responseData, _v, _r, context) => {
      toast.success(responseData.message)
      await context.client.invalidateQueries({
        queryKey: ['expenses'],
      })
    },
  })

  return mutation
}
