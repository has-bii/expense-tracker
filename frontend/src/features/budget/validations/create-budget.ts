import z from 'zod'

export const createBudgetSchema = z.object({
  category_id: z.uuid('Category is required'),
  limit_amount: z.number().positive('Must be a positive number'),
  period: z.enum(['daily', 'weekly', 'monthly']),
  start_date: z.date(),
})

export type CreateBudgetDto = z.infer<typeof createBudgetSchema>
