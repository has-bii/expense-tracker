import z from 'zod'

export const loginSchema = z.object({
  email: z.email(),
  password: z.string().min(6, 'Min. 6 characters').max(255, 'Max. 255 characters'),
})

export type LoginDto = z.infer<typeof loginSchema>
