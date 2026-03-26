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
import { Loader2, Plus } from 'lucide-vue-next'
import { useCreateCategoryForm } from '../api/create-category'
import { Field, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Input } from '@/components/ui/input'

import 'vue3-emoji-picker/css'
import EmojiPickerInput from '@/components/EmojiPickerInput.vue'
import { ref } from 'vue'

const isOpen = ref(false)
const toggleOpen = () => {
  isOpen.value = !isOpen.value
}

const { form, mutation } = useCreateCategoryForm({
  onSuccess: () => {
    isOpen.value = false
  },
})

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const isInvalid = (field: any) => field.state.meta.isTouched && !field.state.meta.isValid

const { isPending } = mutation
</script>

<template>
  <Dialog :open="isOpen" @update:open="toggleOpen">
    <DialogTrigger as-child>
      <Button size="sm">Add Category <Plus /></Button>
    </DialogTrigger>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Add new category</DialogTitle>
        <DialogDescription> To create a new category, enter name and icon </DialogDescription>
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
                  Add
                  <Loader2 v-if="isPending" class="animate-spin" />
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
