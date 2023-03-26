<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { REGISTER_Verify } from '../config'
  import useAuth from '@/hooks/useAuth'
  import { useRoute } from 'vue-router'
  import { AxiosError } from '@/libs/axios'
  import { ref } from 'vue'
  import { registerVerify } from '@/types'

  useHead({
    title: REGISTER_Verify,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${REGISTER_Verify} Page` }],
  })

  const { verify } = useAuth()
  const route = useRoute()
  const checkCode: registerVerify = {
    code: String(route.query.code),
  }
  const displayError = ref<string>('')

  const error: AxiosError = await verify(checkCode)
  if (error) {
    displayError.value = error.response.data.message
  }
</script>

<template>
  <div class="w-96 daisy-card bg-base-100 mx-auto">
    <div class="daisy-card-body">
      <div v-if="!displayError">
        <h1 class="daisy-card-title">確認メール承認</h1>
        <p>
          正常にメールアドレスの確認がされました。ログイン画面からログインできるようになります。
        </p>
      </div>
      <div v-else>
        <p>{{ displayError }}</p>
      </div>
      <router-link to="/login" class="daisy-link daisy-link-accent"
        >ログイン</router-link
      >
    </div>
  </div>
</template>
