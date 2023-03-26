<script setup lang="ts">
  import { reactive } from 'vue'
  import { useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import useAuth from '@/hooks/useAuth'
  import { APP_TITLE } from '@/config'
  import { LOGIN_TITLE } from '../config'
  import { LoginForm } from '@/types'
  import { AxiosError } from '@/libs/axios'
  import {
    FormButton,
    FormError,
    FormLabel,
    FormInput,
  } from '@/components/Form'

  interface LoginState {
    form: LoginForm
    errors: { [k: string]: string }
    isLoading: boolean
  }

  const router = useRouter()
  const loginState = reactive<LoginState>({
    form: { email: '', password: '' },
    errors: {},
    isLoading: false,
  })
  const { state, login } = useAuth()
  useHead({
    title: LOGIN_TITLE,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${LOGIN_TITLE} Page` }],
  })

  async function onSubmit() {
    loginState.errors = {}
    loginState.isLoading = true
    const error: AxiosError = await login(loginState.form)
    loginState.isLoading = false
    if (error) {
      loginState.errors = error.response.data.errors
      return
    }
    router.push('/mypage')
  }
</script>

<template>
  <div
    class="w-1/3 daisy-card bg-base-100 mx-auto px-5"
    :class="{
      'pt-5': state.passwordResetError || state.passwordResetSuccess,
    }"
  >
    <div
      v-if="state.passwordResetSuccess"
      class="daisy-alert daisy-alert-info shadow-lg"
    >
      <div>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          class="stroke-current flex-shrink-0 w-6 h-6"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
          ></path>
        </svg>
        <span>{{ state.passwordResetSuccess }}</span>
      </div>
    </div>

    <div
      v-if="state.passwordResetError"
      class="daisy-alert daisy-alert-error shadow-lg mx-auto w-11/12"
    >
      <div>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="stroke-current flex-shrink-0 h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
          />
        </svg>
        <span>{{ state.passwordResetError }}</span>
      </div>
    </div>
    <form method="POST" class="daisy-card-body" @submit.prevent="onSubmit">
      <FormLabel for="email">メールアドレス</FormLabel>
      <FormInput
        v-model="loginState.form.email"
        type="email"
        placeholder="user@example.com"
        :has-error="!!loginState.errors?.email"
      />
      <FormError :message="loginState.errors?.email" />

      <FormLabel for="password">パスワード</FormLabel>
      <FormInput
        v-model="loginState.form.password"
        type="password"
        :has-error="!!loginState.errors?.email"
      />
      <FormError :message="loginState.errors?.password" />

      <div class="mt-6">
        <FormButton type="submit" :is-loading="loginState.isLoading">
          ログイン
        </FormButton>
      </div>
      <div class="daisy-divider my-0" />
      <p class="text-center">
        <router-link
          to="/user/reset-password"
          class="daisy-link daisy-link-primary"
          >パスワードをお忘れの方</router-link
        >
      </p>
      <router-link to="/register" class="daisy-btn daisy-btn-outline">
        アカウント登録
      </router-link>
    </form>
  </div>
</template>
