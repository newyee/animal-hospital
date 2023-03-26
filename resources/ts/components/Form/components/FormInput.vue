<script setup lang="ts">
  const props = defineProps<{
    modelValue: string
    type?: string
    placeholder?: string
    hasError?: boolean
    disabled?: boolean
  }>()

  const emit = defineEmits(['update:modelValue'])

  const updateValue = (event: InputEvent) => {
    emit('update:modelValue', event.target.value)
  }
</script>

<template>
  <input
    :type="props.type"
    :placeholder="props.placeholder"
    class="daisy-input daisy-input-bordered w-full"
    :class="{ 'daisy-input-error': !!props.hasError }"
    :value="modelValue"
    :disabled="disabled"
    required
    @input="updateValue"
  />
</template>
