<script setup lang="ts">
  import { AppLayout } from '@/components/Layout'
  import useAuth from '@/hooks/useAuth'
  const store = useAuth()
</script>

<template>
  <AppLayout v-if="store.state.isFinished" :user="store.state.user" />
</template>
