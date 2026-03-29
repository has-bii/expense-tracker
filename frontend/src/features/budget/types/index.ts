import type { Category } from '@/features/category/types'

export type Period = 'daily' | 'weekly' | 'monthly'

export interface Budget {
  id: string
  user_id: string
  category_id: string
  limit_amount: string
  period: Period
  spent: number
  percentage: number
  created_at: string
  updated_at: string
  category: Pick<Category, 'id' | 'name' | 'icon'>
}
