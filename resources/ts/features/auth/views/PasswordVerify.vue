<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { PASSWORD_VERIFY } from '../config'
  import useAuth from '@/hooks/useAuth'
  import { useRoute, useRouter } from 'vue-router'
  import { AxiosError } from '@/libs/axios'
  import { ref, reactive, onMounted } from 'vue'
  import {
    FormButton,
    FormError,
    FormLabel,
    FormInput,
  } from '@/components/Form'

  useHead({
    title: PASSWORD_VERIFY,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${PASSWORD_VERIFY} Page` }],
  })

  const { state, passwordVerify, passwordReset } = useAuth()
  const route = useRoute()
  const router = useRouter()
  const verifyToken: { token: string } = {
    token: String(route.query.token),
  }
  const passwordVerifyError = ref<boolean>(false)
  const passwordVerifyMail = ref<string>('')
  const error: AxiosError = await passwordVerify(verifyToken)
  passwordVerifyMail.value = state.passwordVerifyMail as string
  if (error) {
    passwordVerifyError.value = true
    router.push('/login')
  }

  interface PasswordState {
    form: { password: string; password_confirmation: string }
    email: string
    token: string
    errors: { [k: string]: string }
    isLoading: boolean
  }

  const passwordResetState = reactive<PasswordState>({
    form: {
      password: '',
      password_confirmation: '',
    },
    email: passwordVerifyMail.value,
    token: verifyToken.token,
    errors: {},
    isLoading: false,
  })

  async function onSubmit() {
    passwordResetState.errors = {}
    passwordResetState.isLoading = true
    const pData = {
      email: passwordResetState.email,
      token: passwordResetState.token,
      password: passwordResetState.form.password,
      password_confirmation: passwordResetState.form.password_confirmation,
    }
    const error: AxiosError = await passwordReset(pData)
    passwordResetState.isLoading = false
    if (error) {
      if (state.passwordResetError) {
        router.push('/login')
        return
      }
      passwordResetState.errors = error.response.data.errors
      return

    } else {
      router.push({
        path: '/login',
      })
    }
  }
</script>

<template>
  <div v-if="!passwordVerifyError" class="w-1/3 daisy-card bg-base-100 mx-auto">
    <div class="daisy-card-body">
      <div class="flex flex-col items-center">
        <h1 class="daisy-card-title mb-5">パスワードの変更</h1>
        <p>
          パスワードの変更を行います。新しいパスワードを入力後、確認のため再度同じパスワードを入力してください。
        </p>
        <form
          method="POST"
          class="w-full daisy-card-body pt-5 pb-5"
          @submit.prevent="onSubmit"
        >
          <FormLabel for="password">新しいパスワード</FormLabel>
          <FormInput
            v-model="passwordResetState.form.password"
            type="password"
            :has-error="!!passwordResetState.errors?.password"
          />
          <FormError :message="passwordResetState.errors?.password" />
          <FormLabel for="password_confirmation">
            新しいパスワード(確認用)
          </FormLabel>
          <FormInput
            v-model="passwordResetState.form.password_confirmation"
            type="password"
            :has-error="!!passwordResetState.errors?.password_confirmation"
          />
          <div class="mt-6">
            <FormButton
              type="submit"
              :is-loading="passwordResetState.isLoading"
            >
              変更する
            </FormButton>
          </div>
        </form>
      </div>
      <router-link to="/login" class="mx-auto daisy-link daisy-link-accent"
        >ログイン</router-link
      >
    </div>
  </div>
</template>
