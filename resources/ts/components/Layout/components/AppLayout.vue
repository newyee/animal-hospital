<script setup lang="ts">
  import DefaultLayout from './DefaultLayout.vue'
  import LoadingLayout from './LoadingLayout.vue'
  import { markRaw, ref, watch } from 'vue'
  import { useRoute } from 'vue-router'
  import { User } from '@/types'

  const layout = ref()
  const route = useRoute()

  const props = defineProps<{
    user: User | undefined
  }>()

  watch(
    () => route.meta?.layout as string | undefined,
    async (metaLayout) => {
      try {
        const component =
          metaLayout &&
          (await import(/* @vite-ignore */ `./${metaLayout}Layout.vue`))
        layout.value = markRaw(component?.default || LoadingLayout)
      } catch (e) {
        layout.value = markRaw(DefaultLayout)
      }
    },
    { immediate: true }
  )
</script>

<template>
  <component :is="layout" :user="props.user"> <router-view /> </component>
</template>
