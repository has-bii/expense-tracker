import z from 'zod'

export const createBudgetSchema = z.object({
  category_id: z.uuid('Category is required'),
  limit_amount: z.number().positive('Must be a positive number'),
  period: z.enum(['daily', 'weekly', 'monthly']),
  start_date: z.string().regex(/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[0-9]|3)$/, 'Invalid date format'),
})

export type CreateBudgetDto = z.infer<typeof createBudgetSchema>
