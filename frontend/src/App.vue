<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { Loader2 } from 'lucide-vue-next'
import { VueQueryDevtools } from '@tanstack/vue-query-devtools'
import { Toaster } from './components/ui/sonner'
import 'vue-sonner/style.css'

const auth = useAuthStore()
</script>

<template>
  <div
    v-if="!auth.isInitialized"
    class="w-screen h-dvh flex flex-col items-center justify-center gap-4"
  >
    <Loader2 class="size-12 animate-spin text-foreground" />
    <p class="text-lg font-medium">Loading...</p>
  </div>

  <main>
    <RouterView />
  </main>
  <VueQueryDevtools />
  <Toaster rich-colors theme="light" position="top-center" />
</template>

<style scoped></style>
