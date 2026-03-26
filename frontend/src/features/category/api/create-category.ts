import type { Response } from '@/types/response'
import type { Category } from '../types'
import api from '@/lib/api'
import { parseAxiosError } from '@/utils/parse-axios-error'
import { createCategorySchema, type CreateCategoryDto } from '../validations/create-category'
import { useForm } from '@tanstack/vue-form'
import { useMutation } from '@tanstack/vue-query'
import { toast } from 'vue-sonner'

const createCategory = async (input: CreateCategoryDto): Promise<Response<Category>> => {
  try {
    const { data: responseData } = await api.post<Response<Category>>('/category', input)

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

type UseCreateCategoryFormOption = {
  onSuccess?: () => void
  onError?: () => void
}

export const useCreateCategoryForm = ({ onError, onSuccess }: UseCreateCategoryFormOption = {}) => {
  const form = useForm({
    defaultValues: {
      name: '',
      icon: '🍔',
    },
    validators: {
      onSubmit: createCategorySchema,
    },
    onSubmit: async ({ value }) => {
      mutation.mutate(value, {
        onSuccess,
        onError,
      })
      form.reset()
    },
  })

  const mutation = useMutation({
    mutationFn: createCategory,
    onError: (error) => {
      toast.error(error.message)
    },
    onSuccess: (responseData) => {
      toast.success(responseData.message)
    },
    onMutate: () => {},
  })

  return { form, mutation }
}
