import { queryOptions } from '@tanstack/vue-query'

import api from '@/lib/api'
import type { Response } from '@/types/response'
import { parseAxiosError } from '@/utils/parse-axios-error'
import type { DashboardSummary } from '../types'

const getDashboardSummary = async (): Promise<DashboardSummary> => {
  try {
    const { data: responseData } = await api.get<Response<DashboardSummary>>('/dashboard/summary')

    if (responseData.success) return responseData.data

    throw new Error(responseData.message)
  } catch (e) {
    if (e instanceof Error) throw e

    const { message } = parseAxiosError(e)
    throw new Error(message)
  }
}

export const getDashboardSummaryQueryKey = () => ['dashboard-summary']

export const getDashboardSummaryQueryOption = () => {
  return queryOptions({
    queryKey: getDashboardSummaryQueryKey(),
    queryFn: getDashboardSummary,
  })
}
