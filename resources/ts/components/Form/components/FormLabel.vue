<script setup lang="ts">
  const props = defineProps<{
    for?: string
    required?: boolean
  }>()
</script>

<template>
  <label :for="props.for" class="daisy-label">
    <span class="daisy-label-text"><slot /></span>
  </label>
</template>
