import z from 'zod'

export const updateCategorySchema = z.object({
  name: z.string().min(1, 'Min. 1 character').max(255, 'Max. 255 characters'),
  icon: z.string().regex(/^\p{Emoji}$/u, 'Must be an emoji'),
})

export type UpdateCategoryDto = z.infer<typeof updateCategorySchema>
