<script setup lang="ts"></script>

<template>
  <main class="h-screen bg-gray-100 flex items-center justify-center">
    <div
      class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full"
      role="status"
    >
      <span class="visually-hidden">Loading...</span>
    </div>
  </main>
</template>

<style>
  .spinner-border {
    vertical-align: -0.125em;
    border: 0.25em solid;
    border-right-color: transparent;
  }
  .visually-hidden {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
  }
</style>
