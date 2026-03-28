import { ref } from 'vue'
import type { Expense } from '../types'

export const useExpenseDelete = () => {
  const expense = ref<Expense | null>(null)
  const isOpen = ref(false)

  const select = (data: Expense) => {
    expense.value = data
    isOpen.value = true
  }

  const close = () => {
    isOpen.value = false
  }

  return { expense, select, isOpen, close }
}
