import { format } from 'date-fns'
import z from 'zod'

export const incomeSchema = z.object({
  amount: z.number().positive('Must a positive number'),
  source: z.string().min(3, 'Min. 3 characters').max(255, 'Max. 255 characters'),
  description: z.string().max(255, 'Max. 255 characters'),
  income_date: z.date().transform((d) => format(d, 'yyyy-MM-dd')),
})

export type IncomeDto = z.infer<typeof incomeSchema>
export type IncomeFormValues = z.input<typeof incomeSchema>
