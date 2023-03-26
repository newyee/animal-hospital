<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { ABOUT_TITLE } from '../config'

  useHead({
    title: ABOUT_TITLE,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${ABOUT_TITLE} Page` }],
  })
</script>

<template>
  <h2 class="text-3xl text-red-500">{{ ABOUT_TITLE }}</h2>
</template>
