<script setup lang="ts">
  import router from '@/router'
  const clickTop = () => {
    router.push({
      path: '/mypage',
    })
  }
</script>
<template>
  <button
    class="mr-2 daisy-btn daisy-btn-outline daisy-btn-primary"
    @click="clickTop"
  >
    トップ
  </button>
  <button
    class="daisy-btn daisy-btn-outline daisy-btn-primary daisy-btn-active"
  >
    アカウント設定
  </button>
</template>
