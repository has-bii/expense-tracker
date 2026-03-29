<script setup lang="ts">
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import type { Budget } from '../types'
import { type Ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Loader2, Trash2 } from 'lucide-vue-next'
import { useDeleteBudgetMutation } from '../api/delete-budget'

const props = defineProps<{
  isOpen: Ref<boolean>
  close: () => void
  budget: Ref<Budget | null>
}>()

const mutation = useDeleteBudgetMutation()

const handleDelete = () => {
  if (!props.budget.value) return

  mutation.mutate(props.budget.value.id, {
    onSuccess: () => {
      props.close()
    },
  })
}

const { isPending } = mutation
</script>

<template>
  <Dialog :open="isOpen.value" @update:open="close">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Delete budget: {{ budget.value?.category.name }}</DialogTitle>
        <DialogDescription>
          This action cannot be undone. This will permanently delete your data from our servers.
        </DialogDescription>
      </DialogHeader>
      <DialogFooter>
        <DialogClose as-child>
          <Button @click="close" variant="outline">Cancel</Button>
        </DialogClose>
        <Button variant="destructive" :disabled="isPending" @click="handleDelete">
          Delete
          <Loader2 v-if="isPending" class="animate-spin" />
          <Trash2 v-else />
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
