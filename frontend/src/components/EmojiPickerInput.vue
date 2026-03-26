<script setup lang="ts">
import { ref } from 'vue'
import EmojiPicker, { type EmojiExt } from 'vue3-emoji-picker'
import { cn } from '@/lib/utils'
import { onClickOutside } from '@vueuse/core'

const props = defineProps<{
  onSelect: (value: string) => void
  value: string
}>()

const container = ref<HTMLElement | null>(null)
onClickOutside(container, () => {
  isOpen.value = false
})

const isOpen = ref(false)
const toggleOpen = () => {
  isOpen.value = !isOpen.value
}

const select = ({ i }: EmojiExt) => {
  props.onSelect(i)
  isOpen.value = false
}
</script>

<template>
  <div class="relative">
    <button type="button" class="text-6xl size-18!" @click="toggleOpen">{{ value }}</button>

    <div ref="container" :class="cn('absolute top-20 left-0', isOpen ? 'block' : 'hidden')">
      <EmojiPicker disable-skin-tones hide-group-names @select="select" />
    </div>
  </div>
</template>
