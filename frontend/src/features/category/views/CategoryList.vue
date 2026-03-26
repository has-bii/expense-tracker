<script setup lang="ts">
import { useBreadcrumbStore } from '@/stores/breadcrumb'
import { onBeforeMount } from 'vue'
import CategoryCreateDialog from '../components/CategoryCreateDialog.vue'
import { useQuery } from '@tanstack/vue-query'
import { getCategoriesQueryOption } from '../api/get-categories'
import CategoryCard from '../components/CategoryCard.vue'
import CategoryCardSkeleton from '../components/CategoryCardSkeleton.vue'
import { useCategoryUpdate } from '../hooks/use-category-update'
import CategoryDeleteDialog from '../components/CategoryDeleteDialog.vue'
import { useCategoryDelete } from '../hooks/use-category-delete'
import CategoryUpdateDialog from '../components/CategoryUpdateDialog.vue'

const breadcrumb = useBreadcrumbStore()
onBeforeMount(() => {
  breadcrumb.setNavs([{ name: 'Category List' }])
})

const { data = [], isLoading } = useQuery({ ...getCategoriesQueryOption() })

const categoryUpdate = useCategoryUpdate()
const categoryDelete = useCategoryDelete()
</script>

<template>
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Category</h1>
    <CategoryCreateDialog />
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
    <CategoryCard
      v-for="category in data"
      :key="category.id"
      :data="category"
      :delete="categoryDelete.select"
      :update="categoryUpdate.select"
    />
    <CategoryCardSkeleton v-if="isLoading" />
  </div>

  <CategoryDeleteDialog
    :is-open="categoryDelete.isOpen"
    :category="categoryDelete.category"
    :close="categoryDelete.close"
  />
  <CategoryUpdateDialog
    :is-open="categoryUpdate.isOpen"
    :category="categoryUpdate.category"
    :close="categoryUpdate.close"
  />
</template>
