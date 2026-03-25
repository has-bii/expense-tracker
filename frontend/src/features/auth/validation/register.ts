import z from 'zod'

export const registerSchema = z
  .object({
    name: z.string().min(6, 'Min. 6 characters').max(255, 'Max. 255 characters').trim(),
    email: z.email(),
    password: z.string().min(6, 'Min. 6 characters').max(255, 'Max. 255 characters'),
    confirmPassword: z.string().min(1, 'Confirm password is required'),
  })
  .refine(
    ({ password, confirmPassword }) => {
      if (confirmPassword.length === 0) return true

      return password === confirmPassword
    },
    {
      error: "Confirm password doesn't match",
      path: ['confirmPassword'],
    },
  )

export type RegisterDto = z.infer<typeof registerSchema>
