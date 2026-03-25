<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <section class="w-screen min-h-dvh flex items-center justify-center text-center">
    <div class="flex flex-col gap-2 max-w-4xl px-4">
      <h1 class="text-4xl font-bold">Error 404: Page not found!</h1>
      <p class="text-lg text-muted-foreground">
        We've looked everywhere but couldn't find the page you were looking for.
      </p>
      <RouterLink to="/" class="underline">Back to home</RouterLink>
    </div>
  </section>
</template>
