<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { PASSWORD_RESET } from '../config'
  import useAuth from '@/hooks/useAuth'
  import {
    FormButton,
    FormError,
    FormLabel,
    FormInput,
  } from '@/components/Form'
  import { reactive, ref } from 'vue'
  const isSendResetMail = ref(false)
  useHead({
    title: PASSWORD_RESET,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${PASSWORD_RESET} Page` }],
  })

  interface ResetPasswordState {
    form: { email: string }
    errors: { [k: string]: string }
    isLoading: boolean
  }

  // const router = useRouter()
  const { resetPassword } = useAuth()
  const state = reactive<ResetPasswordState>({
    form: { email: '' },
    errors: {},
    isLoading: false,
  })
  async function onSubmit() {
    state.errors = {}
    state.isLoading = true
    const error: AxiosError = await resetPassword(state.form)
    if (error) {
      state.errors = error.response.data.errors
      return
    }
    state.isLoading = false
    isSendResetMail.value = true
  }
</script>

<template>
  <div class="w-1/3 daisy-card bg-base-100 mx-auto">
    <div class="daisy-card-body">
      <div v-if="isSendResetMail" class="flex flex-col items-center">
        <h1 class="mb-3 daisy-card-title mx-auto text-center">
          メールを送信しました。
        </h1>
        <p>
          パスワード再設定用のメールを送信しました。メールに記載されているURLからパスワードの再設定を行ってください。
        </p>
      </div>
      <div v-else class="flex flex-col items-center">
        <h1 class="daisy-card-title text-center mb-3">パスワード再設定</h1>
        <p>
          パスワード再設定用のURLを、登録されているメールアドレスに送信します。登録されているメールアドレスを入力してください。
        </p>
        <form class="w-full" method="POST" @submit.prevent="onSubmit">
          <FormLabel for="email">メールアドレス</FormLabel>
          <FormInput
            id="email"
            v-model="state.form.email"
            type="email"
            placeholder="user@example.com"
            :has-error="!!state.errors?.email"
          />
          <FormError :message="state.errors?.email" />
          <div class="mt-5">
            <FormButton type="submit" :is-loading="state.isLoading">
              送信する
            </FormButton>
          </div>
        </form>
      </div>
      <router-link to="/login" class="mx-auto mt-3 daisy-link daisy-link-accent"
        >ログイン画面へ</router-link
      >
    </div>
  </div>
</template>
