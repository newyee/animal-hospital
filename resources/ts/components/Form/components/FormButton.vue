<script setup lang="ts">
  const props = defineProps<{
    type?: string
    isLoading?: boolean
  }>()
</script>

<template>
  <button
    :type="props.type"
    class="daisy-btn w-full"
    :class="{ 'daisy-loading': props.isLoading }"
  >
    <slot />
  </button>
</template>
'
