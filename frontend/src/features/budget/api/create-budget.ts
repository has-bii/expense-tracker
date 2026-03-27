import type { Response } from '@/types/response'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { useForm } from '@tanstack/vue-form'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'
import { createBudgetSchema, type CreateBudgetDto } from '../validations/create-budget'
import type { Budget, Period } from '../types'
import { getBudgetsQueryKey } from './get-budgets'
import { format } from 'date-fns'

const createBudget = async (input: CreateBudgetDto): Promise<Response<Budget>> => {
  try {
    const { data: responseData } = await api.post<Response<Budget>>('/budget', input)

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

type Args = {
  onSuccess?: () => void
}

export const useCreateBudgetForm = ({ onSuccess }: Args = {}) => {
  const form = useForm({
    defaultValues: {
      category_id: '',
      limit_amount: 0,
      period: 'monthly' as Period,
      start_date: format(new Date(), 'y-MM-dd'),
    },
    validators: {
      onSubmit: createBudgetSchema,
    },
    onSubmit: async ({ value }) => {
      mutation.mutate(value, {
        onSuccess,
      })
    },
  })

  const mutation = useMutation({
    mutationFn: createBudget,
    onError: (error) => {
      toast.error(error.message)
    },
    onSuccess: async (responseData, _v, _r, context) => {
      toast.success(responseData.message)
      await context.client.invalidateQueries({
        exact: true,
        queryKey: getBudgetsQueryKey(),
      })
      form.reset()
    },
  })

  return { form, mutation }
}
