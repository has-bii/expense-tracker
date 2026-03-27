import { queryOptions } from '@tanstack/vue-query'

import api from '@/lib/api'
import type { Response } from '@/types/response'
import { parseAxiosError } from '@/utils/parse-axios-error'
import type { Budget } from '../types'

const getBudgets = async (): Promise<Budget[]> => {
  try {
    const { data: responseData } = await api.get<Response<Budget[]>>('/budget')

    if (responseData.success) return responseData.data

    throw new Error(responseData.message)
  } catch (e) {
    if (e instanceof Error) throw e

    const { message } = parseAxiosError(e)
    throw new Error(message)
  }
}

export const getBudgetsQueryKey = () => ['budgets']

export const getBudgetsQueryOption = () => {
  return queryOptions({
    queryKey: getBudgetsQueryKey(),
    queryFn: getBudgets,
  })
}
