import { defineStore } from 'pinia'
import { ref } from 'vue'

interface Nav {
  name: string
  url?: string
}

export const useBreadcrumbStore = defineStore('breadcrumb', () => {
  const navs = ref<Nav[]>([])

  const setNavs = (newNav: Nav[]) => {
    navs.value = newNav
  }

  return { navs, setNavs }
})
