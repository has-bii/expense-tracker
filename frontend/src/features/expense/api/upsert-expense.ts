import type { Response } from '@/types/response'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { useForm } from '@tanstack/vue-form'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'
import type { Expense } from '../types'
import { expenseSchema, type ExpenseDto } from '../validations/expense.schema'
import { format } from 'date-fns'
import { computed, reactive, type Ref, toValue } from 'vue'

type UpsertApi = {
  input: ExpenseDto
  id?: string
}

const upsertExpense = async ({ input, id }: UpsertApi): Promise<Response<Expense>> => {
  try {
    let responseData: null | Response<Expense> = null

    if (id) {
      const { data } = await api.put<Response<Expense>>(`/expense/${id}`, input)
      responseData = data
    } else {
      const { data } = await api.post<Response<Expense>>('/expense', input)
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

type OldValue = Pick<Expense, 'id' | 'amount' | 'category_id' | 'description' | 'expense_date'>

type Args = {
  onSuccess?: () => void
  oldValue?: Ref<OldValue | undefined> | OldValue
}

export const useCreateExpenseForm = ({ onSuccess, oldValue }: Args) => {
  const id = computed(() => toValue(oldValue)?.id)
  const amount = computed(() => Number(toValue(oldValue)?.amount) || 0)
  const category_id = computed(() => toValue(oldValue)?.category_id || '')
  const description = computed(() => toValue(oldValue)?.description || '')
  const expense_date = computed(() =>
    toValue(oldValue)?.expense_date ? new Date(toValue(oldValue)!.expense_date) : new Date(),
  )

  const defaultValues = reactive({
    amount,
    category_id,
    description,
    expense_date,
  })

  const form = useForm({
    defaultValues,
    validators: {
      onSubmit: expenseSchema,
    },
    onSubmit: async ({ value }) => {
      const dto: ExpenseDto = {
        ...value,
        expense_date: format(value.expense_date, 'yyyy-MM-dd'),
      }
      mutation.mutate(dto, {
        onSuccess,
      })
    },
  })

  const mutation = useMutation({
    mutationFn: (input: ExpenseDto) => upsertExpense({ input, id: id.value }),
    onError: (error) => {
      toast.error(error.message)
    },
    onSuccess: async (responseData, _v, _r, context) => {
      toast.success(responseData.message)
      await context.client.invalidateQueries({
        queryKey: ['expenses'],
      })
      form.reset()
    },
  })

  return { form, mutation }
}
