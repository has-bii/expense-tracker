import z from 'zod'

export const expenseSchema = z.object({
  category_id: z.string().uuid('Please select a category'),
  amount: z.number().positive('Must a positive number'),
  description: z.string().max(255, 'Max. 255 characters'),
  expense_date: z.date(),
})

export type ExpenseDto = z.infer<typeof expenseSchema>
