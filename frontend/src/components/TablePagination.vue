<!-- eslint-disable @typescript-eslint/no-explicit-any -->
<script setup lang="ts">
import type { Pagination } from '@/types/response'
import { Button } from './ui/button'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from './ui/select'
import type { AcceptableValue } from 'reka-ui'
import { useRoute, useRouter } from 'vue-router'

const LIMIT = [10, 25, 50, 100]

defineProps<{
  pagination: Omit<Pagination<any>, 'data'>
}>()

const router = useRouter()
const route = useRoute()

const query = defineModel<{ limit: number; cursor?: string }>('query', {
  default: {
    limit: 10,
    cursor: undefined,
  },
})

const updateLimit = (newLimit: AcceptableValue) => {
  router.push({
    query: {
      ...route.query,
      limit: newLimit as string,
      cursor: undefined,
    },
  })
}

const updateCursor = (value: string | null) => {
  router.push({
    query: {
      ...route.query,
      cursor: value ?? undefined,
    },
  })
}
</script>

<template>
  <div class="flex items-center justify-between px-6 mt-4">
    <Select :model-value="query.limit.toString()" @update:model-value="updateLimit">
      <SelectTrigger class="w-[120px]">
        <SelectValue placeholder="Select a limit" />
      </SelectTrigger>
      <SelectContent>
        <SelectItem v-for="l in LIMIT" :key="l" :value="l.toString()">
          {{ l }}
        </SelectItem>
      </SelectContent>
    </Select>

    <div class="inline-flex items-center gap-2">
      <Button
        size="sm"
        variant="outline"
        :disabled="!pagination.prev_cursor"
        @click="updateCursor(pagination.prev_cursor)"
        >Prev</Button
      >
      <Button
        size="sm"
        variant="outline"
        :disabled="!pagination.next_cursor"
        @click="updateCursor(pagination.next_cursor)"
        >Next</Button
      >
    </div>
  </div>
</template>
