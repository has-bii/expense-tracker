import { ref } from 'vue'
import type { Category } from '../types'

export const useCategoryUpdate = () => {
  const category = ref<Category | null>(null)
  const isOpen = ref(false)

  const select = (data: Category) => {
    category.value = data
    isOpen.value = true
  }

  const remove = () => {
    category.value = null
    isOpen.value = false
  }

  const close = () => {
    isOpen.value = false
  }

  return { category, select, remove, isOpen, close }
}
