import type { Response } from '@/types/response'
import type { Category } from '../types'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'
import { getCategoriesQueryKey } from './get-categories'

const deleteCategory = async (id: string): Promise<Response<Category>> => {
  try {
    const { data: responseData } = await api.delete<Response<Category>>(`/category/${id}`)

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

export const useDeleteCategoryMutation = () => {
  const mutation = useMutation({
    mutationFn: deleteCategory,
    onError: (error) => {
      toast.error(error.message)
    },
    onSuccess: async (responseData, _v, _r, context) => {
      toast.success(responseData.message)
      await context.client.invalidateQueries({
        queryKey: getCategoriesQueryKey(),
        exact: true,
      })
    },
    onMutate: () => {},
  })

  return mutation
}
