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
