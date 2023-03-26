<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { DELETE_ACCOUNT } from '../config'
  import useAuth from '@/hooks/useAuth'
  import { ref } from 'vue'
  import { onBeforeRouteLeave } from 'vue-router'
  const { state } = useAuth()
  useHead({
    title: DELETE_ACCOUNT,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${DELETE_ACCOUNT} Page` }],
  })
  onBeforeRouteLeave((to, from, next) => {
    state.showDeleteSuccess = false
    next()
  })
</script>
<template>
  <div class="w-96 daisy-card bg-base-100 mx-auto">
    <div class="daisy-card-body">
      <h1 class="daisy-card-title">アカウントは正常に削除されました。</h1>
      <p>ご利用ありがとうございました。</p>
      <router-link to="/login" class="daisy-link daisy-link-accent"
        >ログイン</router-link
      >
    </div>
  </div>
</template>
