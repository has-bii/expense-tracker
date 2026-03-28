<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { Button } from './ui/button'
import TableHead from './ui/table/TableHead.vue'
import { ChevronDown, ChevronsUpDown, ChevronUp } from 'lucide-vue-next'

const props = defineProps<{
  class?: HTMLAttributes['class']
  sort_by: string
  sorts?: {
    sort_by: string | undefined
    order: string | undefined
  }
}>()

const emit = defineEmits<{
  sort: [sort_by?: string, order?: string]
}>()

const update = () => {
  if (props.sorts?.sort_by !== props.sort_by) {
    emit('sort', props.sort_by, 'desc')
    return
  }

  if (props.sorts.order === 'desc') {
    emit('sort', props.sort_by, 'asc')
  } else {
    emit('sort')
  }
}
</script>

<template>
  <TableHead :class="props.class">
    <Button variant="ghost" size="sm" class="font-semibold uppercase" @click="update">
      <slot />
      <template v-if="sorts?.sort_by === sort_by">
        <ChevronDown v-if="sorts.order === 'desc'" />
        <ChevronUp v-else-if="sorts.order === 'asc'" />
      </template>
      <template v-else>
        <ChevronsUpDown />
      </template>
    </Button>
  </TableHead>
</template>
