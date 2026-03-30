export interface Income {
  id: string
  user_id: string
  amount: string
  source: string
  description: string | null
  income_date: string
  created_at: string
}

export interface IncomeDetail {
  prev_total: string
  current_total: string
  percentage: number
  sources: Array<{ source: string; total: string }>
}
