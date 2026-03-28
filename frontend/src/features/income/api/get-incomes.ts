import { queryOptions } from '@tanstack/vue-query'

import api from '@/lib/api'
import type { Pagination, Response } from '@/types/response'
import { parseAxiosError } from '@/utils/parse-axios-error'
import type { Income } from '../types'

type QueryInput = {
  limit: number
  cursor?: string
}

const getIncomes = async (query: QueryInput): Promise<Pagination<Income[]>> => {
  try {
    const { data: responseData } = await api.get<Response<Pagination<Income[]>>>('/income', {
      params: query,
    })

    if (responseData.success) return responseData.data

    throw new Error(responseData.message)
  } catch (e) {
    if (e instanceof Error) throw e

    const { message } = parseAxiosError(e)
    throw new Error(message)
  }
}

export const getIncomesQueryKey = (query?: QueryInput) => ['incomes', query]

export const getIncomesQueryOption = (query: QueryInput) => {
  return queryOptions({
    queryKey: getIncomesQueryKey(query),
    queryFn: () => getIncomes(query),
  })
}
