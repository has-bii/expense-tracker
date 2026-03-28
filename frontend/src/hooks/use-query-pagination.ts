import { computed } from 'vue'
import { useRoute } from 'vue-router'

export const useQueryPagination = () => {
  const route = useRoute()

  const pagination = computed(() => ({
    limit: Number(route.query.limit) || 10,
    cursor: route.query.cursor as string | undefined,
  }))

  return { pagination }
}
