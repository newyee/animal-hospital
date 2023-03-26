<script setup lang="ts">
  const props = defineProps<{
    message: string
    show: boolean
  }>()
  const emits = defineEmits(['dismiss'])
</script>
<template>
  <div v-if="show" class="daisy-alert daisy-alert-error shadow-lg my-3">
    <div>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="stroke-current flex-shrink-0 h-6 w-6"
        fill="none"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
      <span>{{ props.message }}</span>
    </div>
    <div class="flex-shrink-0 ml-4">
      <button
        type="button"
        class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400"
        @click="emits('dismiss')"
      >
        <span class="sr-only">Close</span>
        <svg
          class="h-6 w-6"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
    </div>
  </div>
</template>
