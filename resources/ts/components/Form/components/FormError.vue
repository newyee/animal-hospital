<script setup lang="ts">
  const props = defineProps<{
    message?: string | null
  }>()
</script>

<template>
  <p v-if="!!props.message" class="text-red-500 text-xs mt-1">
    {{ props.message }}
  </p>
</template>
