import type { Response } from '@/types/response'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { useForm } from '@tanstack/vue-form'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'
import type { Income } from '../types'
import { incomeSchema, type IncomeDto, type IncomeFormValues } from '../validations/income.schema'
import { format } from 'date-fns'
import { computed, reactive, type Ref, toValue } from 'vue'

type UpsertApi = {
  input: IncomeDto
  id?: string
}

const upsertIncome = async ({ input, id }: UpsertApi): Promise<Response<Income>> => {
  try {
    let responseData: null | Response<Income> = null

    if (id) {
      const { data } = await api.put<Response<Income>>(`/income/${id}`, input)
      responseData = data
    } else {
      const { data } = await api.post<Response<Income>>('/income', input)
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

type OldValue = Pick<Income, 'id' | 'amount' | 'source' | 'description' | 'income_date'>

type Args = {
  onSuccess?: () => void
  oldValue?: Ref<OldValue | undefined> | OldValue
}

export const useCreateIncomeForm = ({ onSuccess, oldValue }: Args) => {
  const id = computed(() => toValue(oldValue)?.id)
  const amount = computed(() => Number(toValue(oldValue)?.amount) || 0)
  const source = computed(() => toValue(oldValue)?.source || '')
  const description = computed(() => toValue(oldValue)?.description || '')
  const income_date = computed(() =>
    toValue(oldValue)?.income_date ? new Date(toValue(oldValue)!.income_date) : new Date(),
  )

  const defaultValues = reactive({
    amount,
    source,
    description,
    income_date,
  })

  const form = useForm({
    defaultValues,
    validators: {
      onSubmit: incomeSchema,
    },
    onSubmit: async ({ value }) => {
      const dto: IncomeDto = {
        ...value,
        income_date: format(value.income_date, 'yyyy-MM-dd'),
      }
      mutation.mutate(dto, {
        onSuccess,
      })
    },
  })

  const mutation = useMutation({
    mutationFn: (input: IncomeDto) => upsertIncome({ input, id: id.value }),
    onError: (error) => {
      toast.error(error.message)
    },
    onSuccess: async (responseData, _v, _r, context) => {
      toast.success(responseData.message)
      await context.client.invalidateQueries({
        queryKey: ['incomes'],
      })
      form.reset()
    },
  })

  return { form, mutation }
}
