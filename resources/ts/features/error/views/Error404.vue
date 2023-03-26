<script setup lang="ts">
  function goBack() {
    window.history.back()
  }
</script>
<template>
  <div class="w-1/3 daisy-card bg-base-100 mx-auto">
    <div class="daisy-card-body">
      <h1 class="text-4xl text-red-600 font-bold mb-4">400 Bad Request</h1>
      <p class="text-2xl mb-4">間違ったページへアクセスした可能性があります</p>
      <p>
        検索されたページは、一時的にアクセスができない状況にあるか、移動または削除された可能性があります。URLを再度ご確認いただくか、トップページよりご希望のページをお探しください。
      </p>
      <a class="daisy-link daisy-link-accent" @click="goBack">戻る</a>
    </div>
  </div>
</template>
