import type { Response } from '@/types/response'
import type { Category } from '../types'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { useForm } from '@tanstack/vue-form'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'
import { getCategoriesQueryKey } from './get-categories'
import { updateCategorySchema, type UpdateCategoryDto } from '../validations/update-category'
import { computed, reactive, type Ref } from 'vue'

interface UpdateCategoryArgs {
  id: string
  input: UpdateCategoryDto
}

const updateCategory = async ({ id, input }: UpdateCategoryArgs): Promise<Response<Category>> => {
  try {
    const { data: responseData } = await api.put<Response<Category>>(`/category/${id}`, input)

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

interface UseUpdateCategoryFormArgs {
  data: Ref<Category | null>
  onSuccess: () => void
}

export const useUpdateCategoryForm = ({ data, onSuccess }: UseUpdateCategoryFormArgs) => {
  const name = computed(() => data.value?.name ?? '')
  const icon = computed(() => data.value?.icon ?? '🍔')

  const defaultValues = reactive({
    name,
    icon,
  })

  const form = useForm({
    defaultValues,
    validators: {
      onSubmit: updateCategorySchema,
    },
    onSubmit: async ({ value }) => {
      if (!data.value) return
      mutation.mutate(
        { id: data.value.id, input: value },
        {
          onSuccess,
        },
      )
    },
  })

  const mutation = useMutation({
    mutationFn: updateCategory,
    onError: (error) => {
      toast.error(error.message)
    },
    onSuccess: async (responseData, _v, _r, context) => {
      toast.success(responseData.message)
      await context.client.invalidateQueries({
        exact: true,
        queryKey: getCategoriesQueryKey(),
      })
    },
  })

  return { form, mutation }
}
