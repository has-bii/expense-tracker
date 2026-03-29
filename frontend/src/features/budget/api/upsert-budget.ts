import type { Response } from '@/types/response'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { useForm } from '@tanstack/vue-form'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'

import type { Budget, Period } from '../types'
import { getBudgetsQueryKey } from './get-budgets'
import { budgetSchema, type BudgetDto } from '../validations/budget.schema'
import { computed, reactive, type Ref, toValue } from 'vue'

type UpsertApi = {
  input: BudgetDto
  id?: string
}

const upsertBudget = async ({ input, id }: UpsertApi): Promise<Response<Budget>> => {
  try {
    let responseData: null | Response<Budget> = null

    if (id) {
      const { data } = await api.put<Response<Budget>>(`/budget/${id}`, input)
      responseData = data
    } else {
      const { data } = await api.post<Response<Budget>>('/budget', input)
      responseData = data
    }

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

type OldValue = Pick<Budget, 'id' | 'category_id' | 'limit_amount' | 'period'>

type Args = {
  onSuccess?: () => void
  oldValue?: Ref<OldValue | undefined> | OldValue
}

export const useCreateBudgetForm = ({ onSuccess, oldValue }: Args = {}) => {
  const id = computed(() => toValue(oldValue)?.id)
  const category_id = computed(() => toValue(oldValue)?.category_id || '')
  const limit_amount = computed(() => Number(toValue(oldValue)?.limit_amount) || 0)
  const period = computed(() => toValue(oldValue)?.period || ('monthly' as Period))

  const defaultValues = reactive({
    category_id,
    limit_amount,
    period,
  })

  const form = useForm({
    defaultValues,
    validators: {
      onSubmit: budgetSchema,
    },
    onSubmit: async ({ value }) => {
      mutation.mutate(value, {
        onSuccess,
      })
    },
  })

  const mutation = useMutation({
    mutationFn: (input: BudgetDto) => upsertBudget({ input, id: id.value }),
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
