import z from 'zod'

export const resetPasswordSchema = z
  .object({
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

export type ResetPasswordDto = z.infer<typeof resetPasswordSchema>
