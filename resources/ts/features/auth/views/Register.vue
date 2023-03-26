<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { REGISTER_TITLE } from '../config'
  import { reactive } from 'vue'
  import { useRouter } from 'vue-router'
  import useAuth from '@/hooks/useAuth'
  import { RegisterForm } from '@/types'
  import { AxiosError } from '@/libs/axios'
  import {
    FormButton,
    FormError,
    FormLabel,
    FormInput,
  } from '@/components/Form'

  interface RegisterState {
    form: RegisterForm
    errors: { [k: string]: string }
    isLoading: boolean
  }

  const router = useRouter()

  const state = reactive<RegisterState>({
    form: { name: '', email: '', password: '', password_confirmation: '' },
    errors: {},
    isLoading: false,
  })
  const { register } = useAuth()

  useHead({
    title: REGISTER_TITLE,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${REGISTER_TITLE} Page` }],
  })

  async function onSubmit() {
    state.errors = {}
    state.isLoading = true
    const error: AxiosError = await register(state.form)
    state.isLoading = false
    if (error) {
      state.errors = error.response.data.errors
      return
    }
    router.push({
      path: '/register-confirm',
    })
  }
</script>

<template>
  <div class="w-96 daisy-card bg-base-100 mx-auto">
    <form method="POST" class="daisy-card-body" @submit.prevent="onSubmit">
      <FormLabel for="email">氏名</FormLabel>
      <FormInput
        v-model="state.form.name"
        type="name"
        placeholder="山田太郎"
        :has-error="!!state.errors?.email"
      />
      <FormError :message="state.errors?.name" />
      <FormLabel for="email">メールアドレス</FormLabel>
      <FormInput
        v-model="state.form.email"
        type="email"
        placeholder="user@example.com"
        :has-error="!!state.errors?.email"
      />
      <FormError :message="state.errors?.email" />
      <FormLabel for="password">パスワード</FormLabel>
      <FormInput
        v-model="state.form.password"
        type="password"
        :has-error="!!state.errors?.password"
      />
      <FormError :message="state.errors?.password" />
      <FormLabel for="password_confirmation"> パスワード(確認入力) </FormLabel>
      <FormInput
        v-model="state.form.password_confirmation"
        type="password"
        :has-error="!!state.errors?.password_confirmation"
      />
      <FormError :message="state.errors?.password_confirmation" />
      <div class="mt-6">
        <FormButton type="submit" :is-loading="state.isLoading">
          この内容で登録する
        </FormButton>
      </div>
      <div class="daisy-divider my-0" />
      <router-link to="/login" class="daisy-btn daisy-btn-outline">
        ログイン
      </router-link>
    </form>
  </div>
</template>
