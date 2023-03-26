<script setup lang="ts"></script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <main class="flex flex-grow">
      <div class="w-full">
        <div class="h-screen flex items-center content-center">
          <slot />
        </div>
      </div>
    </main>
  </div>
</template>
