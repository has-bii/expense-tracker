import type { Category } from '@/features/category/types'

export type Period = 'daily' | 'weekly' | 'monthly'

export interface Budget {
  id: string
  category_id: string
  limit_amount: string
  period: Period
  start_date: string
  created_at: string
  updated_at: string
  category: Pick<Category, 'id' | 'name' | 'icon'>
}
