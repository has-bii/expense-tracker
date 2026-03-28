<script setup lang="ts">
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { Check, Loader2, Plus } from 'lucide-vue-next'
import { Field, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Input } from '@/components/ui/input'

import 'vue3-emoji-picker/css'
import EmojiPickerInput from '@/components/EmojiPickerInput.vue'
import { computed, ref, toRef } from 'vue'
import { useUpsertCategoryForm } from '../api/upsert-category'
import type { Category } from '../types'

// Props
const props = withDefaults(
  defineProps<{
    oldValue?: Pick<Category, 'id' | 'name' | 'icon'>
    type?: 'create' | 'update'
    open?: boolean
  }>(),
  { type: 'create' },
)

const emit = defineEmits<{
  'update:open': [value: boolean]
}>()

const isUpdate = computed(() => props.type === 'update')

// Dialog state
const internalOpen = ref(false)
const isOpen = computed({
  get: () => (isUpdate.value ? props.open ?? false : internalOpen.value),
  set: (val) => {
    if (isUpdate.value) {
      emit('update:open', val)
    } else {
      internalOpen.value = val
    }
  },
})

// Form state
const { form, mutation } = useUpsertCategoryForm({
  onSuccess: () => {
    isOpen.value = false
  },
  oldValue: toRef(() => props.oldValue),
})

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const isInvalid = (field: any) => field.state.meta.isTouched && !field.state.meta.isValid

const { isPending } = mutation
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger v-if="!isUpdate" as-child>
      <Button size="sm">Add Category <Plus /></Button>
    </DialogTrigger>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ isUpdate ? 'Update category' : 'Add new category' }}</DialogTitle>
        <DialogDescription>
          {{ isUpdate ? 'Change name or icon to update category' : 'To create a new category, enter name and icon' }}
        </DialogDescription>
      </DialogHeader>
      <form @submit.prevent="form.handleSubmit">
        <FieldGroup>
          <!-- Name -->
          <form.Field name="name">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Name</FieldLabel>
                <Input
                  :id="field.name"
                  :name="field.name"
                  :model-value="field.state.value"
                  :aria-invalid="isInvalid(field)"
                  @blur="field.handleBlur"
                  @input="field.handleChange($event.target.value)"
                  placeholder="Food"
                  autocomplete="off"
                />
                <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
              </Field>
            </template>
          </form.Field>

          <!-- Icon -->
          <form.Field name="icon">
            <template v-slot="{ field }">
              <Field :data-invalid="isInvalid(field)">
                <FieldLabel :for="field.name">Icon</FieldLabel>

                <EmojiPickerInput
                  :value="field.state.value"
                  @select="(value) => field.handleChange(value)"
                />
                <FieldError v-if="isInvalid(field)" :errors="field.state.meta.errors" />
              </Field>
            </template>
          </form.Field>

          <!-- Submit button -->
          <form.Subscribe>
            <template v-slot="{ canSubmit }">
              <Field>
                <Button :disabled="!canSubmit || isPending">
                  {{ isUpdate ? 'Update' : 'Add' }}
                  <Loader2 v-if="isPending" class="animate-spin" />
                  <Check v-else-if="isUpdate" />
                  <Plus v-else />
                </Button>
              </Field>
            </template>
          </form.Subscribe>
        </FieldGroup>
      </form>
    </DialogContent>
  </Dialog>
</template>
