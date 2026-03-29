import z from 'zod'

export const budgetSchema = z.object({
  category_id: z.uuid('Category is required'),
  limit_amount: z.number().positive('Must be a positive number'),
  period: z.enum(['daily', 'weekly', 'monthly']),
})

export type BudgetDto = z.infer<typeof budgetSchema>
