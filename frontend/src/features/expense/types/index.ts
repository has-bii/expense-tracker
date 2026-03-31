export interface Expense {
  id: string
  user_id: string
  category_id: string
  amount: string
  description: string | null
  expense_date: string
  created_at: string
  updated_at: string
}

export interface ExpenseDetail {
  prev_total: string
  current_total: string
  percentage: number
  detail: Array<{ category_id: string; total: string }>
}
