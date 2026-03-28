<script setup lang="ts">
import { useBreadcrumbStore } from '@/stores/breadcrumb'
import { onBeforeMount, ref } from 'vue'
import { useQuery } from '@tanstack/vue-query'
import { getCategoriesQueryOption } from '../api/get-categories'
import CategoryCard from '../components/CategoryCard.vue'
import CategoryCardSkeleton from '../components/CategoryCardSkeleton.vue'
import CategoryUpsertDialog from '../components/CategoryUpsertDialog.vue'
import CategoryDeleteDialog from '../components/CategoryDeleteDialog.vue'
import { useCategoryDelete } from '../hooks/use-category-delete'
import type { Category } from '../types'

const breadcrumb = useBreadcrumbStore()
onBeforeMount(() => {
  breadcrumb.setNavs([{ name: 'Category List' }])
})

const { data = [], isLoading } = useQuery({ ...getCategoriesQueryOption() })

// Update dialog state
const selectedCategory = ref<Pick<Category, 'id' | 'name' | 'icon'> | undefined>()
const isEditOpen = ref(false)

const openEdit = (category: Category) => {
  selectedCategory.value = category
  isEditOpen.value = true
}

const onEditOpenChange = (val: boolean) => {
  isEditOpen.value = val
  if (!val) {
    selectedCategory.value = undefined
  }
}

// Delete dialog (kept as-is)
const categoryDelete = useCategoryDelete()
</script>

<template>
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Category</h1>
    <CategoryUpsertDialog />
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
    <CategoryCard
      v-for="category in data"
      :key="category.id"
      :data="category"
      :delete="categoryDelete.select"
      :update="openEdit"
    />
    <CategoryCardSkeleton v-if="isLoading" />
  </div>

  <CategoryDeleteDialog
    :is-open="categoryDelete.isOpen"
    :category="categoryDelete.category"
    :close="categoryDelete.close"
  />
  <CategoryUpsertDialog
    type="update"
    :old-value="selectedCategory"
    :open="isEditOpen"
    @update:open="onEditOpenChange"
  />
</template>
