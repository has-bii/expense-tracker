import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export const useQuerySort = () => {
  const route = useRoute()
  const router = useRouter()

  const sorts = computed(() => ({
    sort_by: (route.query.sort_by as string) || undefined,
    order: (route.query.order as string) || undefined,
  }))

  const updateSort = (sort_by?: string, order?: string) => {
    router.push({
      query: {
        ...route.query,
        sort_by,
        order,
        cursor: undefined,
      },
    })
  }

  return { sorts, updateSort }
}
