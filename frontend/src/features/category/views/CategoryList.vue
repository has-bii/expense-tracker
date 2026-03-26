<script setup lang="ts">
import { useBreadcrumbStore } from '@/stores/breadcrumb'
import { onBeforeMount } from 'vue'
import CreateCategoryDialog from '../components/CreateCategoryDialog.vue'

const breadcrumb = useBreadcrumbStore()

onBeforeMount(() => {
  breadcrumb.setNavs([{ name: 'Category List' }])
})
</script>

<template>
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Category</h1>
    <CreateCategoryDialog />
  </div>
</template>
