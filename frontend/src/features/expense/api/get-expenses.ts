import { queryOptions } from '@tanstack/vue-query'

import api from '@/lib/api'
import type { Pagination, Response } from '@/types/response'
import { parseAxiosError } from '@/utils/parse-axios-error'
import type { Expense } from '../types'

type QueryInput = {
  limit: number
  cursor?: string
  sort_by?: string
  order?: string
  category?: string
  amount_from?: string
  amount_to?: string
  expense_date_from?: string
  expense_date_to?: string
}

const getExpenses = async (query: QueryInput): Promise<Pagination<Expense[]>> => {
  try {
    const { data: responseData } = await api.get<Response<Pagination<Expense[]>>>('/expense', {
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

export const getExpensesQueryKey = (query?: QueryInput) => ['expenses', query]

export const getExpensesQueryOption = (query: QueryInput) => {
  return queryOptions({
    queryKey: getExpensesQueryKey(query),
    queryFn: () => getExpenses(query),
  })
}
