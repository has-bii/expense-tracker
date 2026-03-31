import { format } from 'date-fns'
import z from 'zod'

export const expenseSchema = z.object({
  category_id: z.uuid('Please select a category'),
  amount: z.number().positive('Must a positive number'),
  description: z.string().max(255, 'Max. 255 characters'),
  expense_date: z.date().transform((d) => format(d, 'yyyy-MM-dd')),
})

export type ExpenseDto = z.infer<typeof expenseSchema>
