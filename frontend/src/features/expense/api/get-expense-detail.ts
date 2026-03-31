import { queryOptions } from '@tanstack/vue-query'

import api from '@/lib/api'
import type { Response } from '@/types/response'
import { parseAxiosError } from '@/utils/parse-axios-error'
import type { ExpenseDetail } from '../types'

const getExpenseDetail = async (): Promise<ExpenseDetail> => {
  try {
    const { data: responseData } = await api.get<Response<ExpenseDetail>>('/expense/detail')

    if (responseData.success) return responseData.data

    throw new Error(responseData.message)
  } catch (e) {
    if (e instanceof Error) throw e

    const { message } = parseAxiosError(e)
    throw new Error(message)
  }
}

export const getExpenseDetailQueryKey = () => ['expenses', 'detail']

export const getExpenseDetailQueryOption = () => {
  return queryOptions({
    queryKey: getExpenseDetailQueryKey(),
    queryFn: getExpenseDetail,
  })
}
