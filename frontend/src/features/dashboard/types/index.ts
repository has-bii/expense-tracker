export interface DashboardSummary {
  all_time_net_balance: number
  all_time_income: number
  all_time_expense: number
  net_balance: number
  total_income: number
  total_expense: number
  prev_net_balance: number
  percentage_change: number
  top_categories: Array<{
    category_id: string
    category_name: string
    icon: string
    total: string
  }>
  top_sources: Array<{
    source: string
    total: string
  }>
}
