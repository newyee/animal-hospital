<script setup lang="ts">
  import { EmailVerifyCode } from '@/types'
  import useAuth from '@/hooks/useAuth'
  import { useRoute } from 'vue-router'
  import router from '@/router'

  const { changeEmailVerify } = useAuth()
  const route = useRoute()
  const checkCode: EmailVerifyCode = {
    code: String(route.query.code),
  }

  await changeEmailVerify(checkCode)
  router.push({
    path: '/mypage/settings',
  })
</script>

<template>
  <div></div>
</template>
