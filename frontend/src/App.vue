<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { Loader2 } from 'lucide-vue-next'

const auth = useAuthStore()
</script>

<template>
  <!-- Block rendering until first auth check is done -->
  <div
    v-if="!auth.isInitialized"
    class="w-screen h-dvh flex flex-col items-center justify-center gap-4"
  >
    <Loader2 class="size-12 animate-spin text-foreground" />
    <p class="text-lg font-medium">Loading...</p>
  </div>

  <RouterView />
</template>

<style scoped></style>
