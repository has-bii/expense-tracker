<script setup lang="ts">
import { useBreadcrumbStore } from '@/stores/breadcrumb'
import { onBeforeMount } from 'vue'

const breadcrumb = useBreadcrumbStore()

onBeforeMount(() => {
  breadcrumb.setNavs([{ name: 'Category List' }])
})
</script>

<template></template>
