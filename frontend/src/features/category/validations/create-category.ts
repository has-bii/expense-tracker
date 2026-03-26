import z from 'zod'

export const createCategorySchema = z.object({
  name: z.string().min(1, 'Min. 1 character').max(255, 'Max. 255 characters'),
  icon: z.string().regex(/^\p{Emoji}$/u, 'Must be an emoji'),
})

export type CreateCategoryDto = z.infer<typeof createCategorySchema>
