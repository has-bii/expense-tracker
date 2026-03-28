import { ref } from 'vue'
import type { Income } from '../types'

export const useIncomeDelete = () => {
  const income = ref<Income | null>(null)
  const isOpen = ref(false)

  const select = (data: Income) => {
    income.value = data
    isOpen.value = true
  }

  const close = () => {
    isOpen.value = false
  }

  return { income, select, isOpen, close }
}
