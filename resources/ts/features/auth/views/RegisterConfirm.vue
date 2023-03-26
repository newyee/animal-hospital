<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { REGISTER_CONFIRM_TITLE } from '../config'
  import useAuth from '@/hooks/useAuth'

  useHead({
    title: REGISTER_CONFIRM_TITLE,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${REGISTER_CONFIRM_TITLE} Page` }],
  })
  const { state } = useAuth()
  const email = state.ProvisionalUser?.requestMail
</script>

<template>
  <div class="w-96 daisy-card bg-base-100 mx-auto">
    <div class="daisy-card-body">
      <h1 class="daisy-card-title">確認メールを送信しました</h1>
      <p>{{ email }}&nbsp;へ確認メールを送信しました。</p>
      <p>メールに記載されているURLをクリックすると登録が完了します。</p>
    </div>
  </div>
</template>
