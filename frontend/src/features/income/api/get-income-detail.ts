import { queryOptions } from '@tanstack/vue-query'

import api from '@/lib/api'
import type { Pagination, Response } from '@/types/response'
import { parseAxiosError } from '@/utils/parse-axios-error'
import type { Income, IncomeDetail } from '../types'

const getIncomeDetail = async (): Promise<IncomeDetail> => {
  try {
    const { data: responseData } = await api.get<Response<IncomeDetail>>('/income/detail')

    if (responseData.success) return responseData.data

    throw new Error(responseData.message)
  } catch (e) {
    if (e instanceof Error) throw e

    const { message } = parseAxiosError(e)
    throw new Error(message)
  }
}

export const getIncomeDetailQueryKey = () => ['incomes', 'detail']

export const getIncomeDetailQueryOption = () => {
  return queryOptions({
    queryKey: getIncomeDetailQueryKey(),
    queryFn: getIncomeDetail,
  })
}
