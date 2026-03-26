import { queryOptions } from '@tanstack/vue-query'

import api from '@/lib/api'
import type { Response } from '@/types/response'
import type { Category } from '../types'
import { parseAxiosError } from '@/utils/parse-axios-error'

const getCategories = async (): Promise<Category[]> => {
  try {
    const { data: responseData } = await api.get<Response<Category[]>>('/category')

    if (responseData.success) return responseData.data

    throw new Error(responseData.message)
  } catch (e) {
    if (e instanceof Error) throw e

    const { message } = parseAxiosError(e)
    throw new Error(message)
  }
}

export const getCategoriesQueryKey = () => ['categories']

export const getCategoriesQueryOption = () => {
  return queryOptions({
    queryKey: getCategoriesQueryKey(),
    queryFn: getCategories,
  })
}
