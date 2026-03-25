<!-- eslint-disable @typescript-eslint/no-explicit-any -->
<script setup lang="ts">
import { ref } from 'vue'
import InputGroup from './InputGroup.vue'
import InputGroupInput from './InputGroupInput.vue'
import InputGroupAddon from './InputGroupAddon.vue'
import { Eye, EyeOff, Lock } from 'lucide-vue-next'
import InputGroupButton from './InputGroupButton.vue'

// interface Props {
//   field: any
//   isInvalid: boolean
//   placeholder?: string
// }
// const props = defineProps<Props>()

const isShow = ref(false)
const toggleShow = () => (isShow.value = !isShow.value)
</script>

<template>
  <InputGroup>
    <InputGroupInput v-bind="$attrs" :type="isShow ? 'text' : 'password'" />
    <InputGroupAddon>
      <Lock />
    </InputGroupAddon>
    <InputGroupAddon align="inline-end">
      <InputGroupButton size="sm" type="button" @click="toggleShow">
        <Eye role="button" v-if="isShow" />
        <EyeOff role="button" v-else />
      </InputGroupButton>
    </InputGroupAddon>
  </InputGroup>
</template>
