import { ref } from 'vue'
import type { Budget } from '../types'

export const useBudgetDelete = () => {
  const budget = ref<Budget | null>(null)
  const isOpen = ref(false)

  const select = (data: Budget) => {
    budget.value = data
    isOpen.value = true
  }

  const close = () => {
    isOpen.value = false
  }

  return { budget, select, isOpen, close }
}
