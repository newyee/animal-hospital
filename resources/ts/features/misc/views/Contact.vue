<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { CONTACT_TITLE } from '../config'

  useHead({
    title: CONTACT_TITLE,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${CONTACT_TITLE} Page` }],
  })
</script>

<template>
  <h2>{{ CONTACT_TITLE }}</h2>
</template>
