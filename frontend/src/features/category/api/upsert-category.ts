import type { Response } from '@/types/response'
import type { Category } from '../types'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { useForm } from '@tanstack/vue-form'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'
import { getCategoriesQueryKey } from './get-categories'
import { createCategorySchema, type CreateCategoryDto } from '../validations/create-category'
import { computed, reactive, type Ref, toValue } from 'vue'

type UpsertApi = {
  input: CreateCategoryDto
  id?: string
}

const upsertCategory = async ({ input, id }: UpsertApi): Promise<Response<Category>> => {
  try {
    let responseData: null | Response<Category> = null

    if (id) {
      const { data } = await api.put<Response<Category>>(`/category/${id}`, input)
      responseData = data
    } else {
      const { data } = await api.post<Response<Category>>('/category', input)
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

type OldValue = Pick<Category, 'id' | 'name' | 'icon'>

type Args = {
  onSuccess?: () => void
  oldValue?: Ref<OldValue | undefined> | OldValue
}

export const useUpsertCategoryForm = ({ onSuccess, oldValue }: Args) => {
  const id = computed(() => toValue(oldValue)?.id)
  const name = computed(() => toValue(oldValue)?.name ?? '')
  const icon = computed(() => toValue(oldValue)?.icon ?? '🍔')

  const defaultValues = reactive({
    name,
    icon,
  })

  const form = useForm({
    defaultValues,
    validators: {
      onSubmit: createCategorySchema,
    },
    onSubmit: async ({ value }) => {
      mutation.mutate(value, {
        onSuccess,
      })
    },
  })

  const mutation = useMutation({
    mutationFn: (input: CreateCategoryDto) => upsertCategory({ input, id: id.value }),
    onError: (error) => {
      toast.error(error.message)
    },
    onSuccess: async (responseData, _v, _r, context) => {
      toast.success(responseData.message)
      await context.client.invalidateQueries({
        exact: true,
        queryKey: getCategoriesQueryKey(),
      })
      form.reset()
    },
  })

  return { form, mutation }
}
