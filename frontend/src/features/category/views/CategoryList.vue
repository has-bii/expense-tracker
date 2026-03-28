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
import { Shapes, Sparkles } from 'lucide-vue-next'

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
  <!-- Empty state -->
  <div
    v-if="!isLoading && data.length === 0"
    class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-primary/40 bg-card/50 px-6 py-16 text-center animate-in fade-in-0 slide-in-from-bottom-4 duration-500"
  >
    <div class="relative mb-6">
      <div
        class="flex h-20 w-20 items-center justify-center rounded-2xl border border-primary/20 bg-accent shadow-md"
      >
        <Shapes class="h-10 w-10 text-accent-foreground" :stroke-width="1.5" />
      </div>
      <div
        class="absolute -top-2 -right-2 flex h-8 w-8 items-center justify-center rounded-lg border border-primary/20 bg-secondary shadow-sm"
      >
        <Sparkles class="h-4 w-4 text-secondary-foreground" :stroke-width="2" />
      </div>
    </div>
    <h2 class="font-serif text-xl font-semibold text-foreground">No categories yet</h2>
    <p class="mt-1.5 max-w-xs text-sm text-muted-foreground">
      Categories help you organize your expenses and income. Create your first one to get started.
    </p>
  </div>

  <!-- Grid -->
  <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
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
