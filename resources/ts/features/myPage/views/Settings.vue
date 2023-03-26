<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { MYPAGE_TITLE } from '../config'
  import useAuth from '@/hooks/useAuth'
  import { ref, reactive } from 'vue'
  import { FormError, FormLabel, FormInput } from '@/components/Form'
  import router from '@/router'
  import { AxiosError } from 'axios'
  import SuccessAlert from '@/components/Alert/SuccessAlert.vue'
  import ErrorAlert from '@/components/Alert/ErrorAlert.vue'
  import ButtonGroup from '@/components/Button/ButtonGroup.vue'
  import { onBeforeRouteLeave } from 'vue-router'

  useHead({
    title: MYPAGE_TITLE,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${MYPAGE_TITLE} Page` }],
  })

  const {
    state,
    changeUserInfo,
    changeEmail,
    resendEmail,
    cancelChangeEmail,
    changePassword,
    deleteAccount,
  } = useAuth()
  const userId = state.user?.id as number
  const userName = ref(state.user?.name as string)
  const phoneNumber = state.user?.phoneNumber as string
  const newEmail = ref(state.user?.newEmail)
  const sendConfirmEmail = ref(false)
  const changeUserFlag = ref(false)
  const failedAuthenticateEmail = ref(state.failedAuthenticateEmail as string)
  const verifyEmail = ref(state.user?.email)
  const failedAuthenticateFlag = ref(false)
  const ownerInfo = ref()
  const emailContener = ref()
  const passwordContener = ref()
  const changePasswordMessage = ref('')
  const changePasswordFlag = ref(false)

  // 指定の要素が画面に表示されるようにスクロールする
  const scrollToOwnerElement = () => {
    if (ownerInfo.value) {
      ownerInfo.value.scrollIntoView({
        block: 'start',
      })
    }
  }

  const scrollToEmailElement = () => {
    if (emailContener.value) {
      emailContener.value.scrollIntoView({
        behavior: 'instant',
        block: 'start',
      })
    }
  }
  const scrollToPasswordElement = () => {
    if (passwordContener.value) {
      passwordContener.value.scrollIntoView({
        behavior: 'instant',
        block: 'start',
      })
    }
  }

  const mailAlerts = ref([
    {
      show: false,
      message: '',
    },
    {
      show: false,
      message: '',
    },
    {
      show: false,
      message: '',
    },
    {
      show: false,
      message: '',
    },
  ])

  function dismissAlert(index: number) {
    mailAlerts.value[index].show = false
  }

  if (state.verifyNewEmail) {
    mailAlerts.value[3].show = true
    mailAlerts.value[3].message = state.newEmailVerifyMessage as string
    verifyEmail.value = state.verifyNewEmail
  }

  if (state.failedAuthenticateEmail) {
    failedAuthenticateFlag.value = true
  }

  if (state.changePasswordMessage) {
    changePasswordMessage.value = state.changePasswordMessage as string
    changePasswordFlag.value = true
  }

  interface SettingsState {
    form: { name: string; phoneNumber: string }
    passwordForm: {
      currentPassword: string
      newPassword: string
      newPasswordConfirmation: string
      isPassWordFormLoading: boolean
    }
    newEmail: string
    errors: { [k: string]: string }
    isOwnerFormLoading: boolean
    isVerifyMailLoading: boolean
    isDeleteChangeLoading: boolean
    isDeleteAccountLoading: boolean
  }
  const formState = reactive<SettingsState>({
    form: {
      name: userName.value,
      phoneNumber: phoneNumber,
    },
    passwordForm: {
      currentPassword: '',
      newPassword: '',
      newPasswordConfirmation: '',
      isPassWordFormLoading: false,
    },
    newEmail: '',
    errors: {},
    isOwnerFormLoading: false,
    isVerifyMailLoading: false,
    isDeleteChangeLoading: false,
    isDeleteAccountLoading: false,
  })
  async function changeUserFunc() {
    formState.isOwnerFormLoading = true
    const error: AxiosError = await changeUserInfo(formState.form)
    formState.isOwnerFormLoading = false
    if (error) {
      formState.errors = error.response.data.errors
      return
    }
    formState.errors = {}
    userName.value = formState.form.name
    changeUserFlag.value = true
    scrollToOwnerElement()
  }

  async function changeEmailFunc() {
    newEmail.value = ''
    state.verifyNewEmail = ''
    formState.isVerifyMailLoading = true
    const pData = { email: formState.newEmail, userId: userId }
    const error: AxiosError = await changeEmail(pData)
    formState.isVerifyMailLoading = false
    if (error) {
      formState.errors = error.response.data.errors
      return
    }
    formState.errors = {}
    newEmail.value = formState.newEmail
    sendConfirmEmail.value = true
    mailAlerts.value[0].show = true
    mailAlerts.value[0].message = state.confirmationSentMessage as string
    scrollToEmailElement()
  }

  async function resendEmailFunc() {
    const error: AxiosError = await resendEmail()
    if (error) {
      formState.errors = error.response.data.errors
      return
    }
    mailAlerts.value[1].show = true
    mailAlerts.value[1].message = state.emailResendMessage as string
    scrollToEmailElement()
  }

  async function cancelEmailChangeFunc() {
    if (window.confirm('メールアドレスの変更を取り消しますか？')) {
      formState.isDeleteChangeLoading = true
      // OKがクリックされた場合の処
      const error: AxiosError = await cancelChangeEmail()
      if (error) {
        formState.errors = error.response.data.errors
        return
      }
      newEmail.value = ''
      sendConfirmEmail.value = false
      formState.newEmail = ''
      mailAlerts.value[2].show = true
      mailAlerts.value[2].message = state.cancelChangeEmail as string
      formState.isDeleteChangeLoading = false
      scrollToEmailElement()
    } else {
      // キャンセルがクリックされた場合の処理
      return false
    }
  }

  async function changePasswordFunc() {
    formState.passwordForm.isPassWordFormLoading = true
    const pData = {
      currentPassword: formState.passwordForm.currentPassword,
      newPassword: formState.passwordForm.newPassword,
      newPasswordConfirmation: formState.passwordForm.newPasswordConfirmation,
    }
    const error: AxiosError = await changePassword(pData)
    if (error) {
      formState.errors = error.response.data.errors
      formState.passwordForm.isPassWordFormLoading = false
      return
    }
    formState.errors = {}
    formState.passwordForm = {
      currentPassword: '',
      newPassword: '',
      newPasswordConfirmation: '',
      isPassWordFormLoading: false,
    }
    changePasswordMessage.value = state.changePasswordMessage as string
    changePasswordFlag.value = true
    scrollToPasswordElement()
  }

  async function deleteAccountFunc() {
    if (window.confirm('本当にアカウントを削除しますか？')) {
      formState.isDeleteAccountLoading = true
      await deleteAccount()
      router.push('/user/delete-account')
    } else {
      return false
    }
  }
  setTimeout(() => {
    if (state.failedAuthenticateEmail) {
      scrollToEmailElement()
    }
    if (state.verifyNewEmail) {
      scrollToEmailElement()
    }
  })
  onBeforeRouteLeave((to, from, next) => {
    state.changePasswordMessage = ''
    changePasswordFlag.value = false
    if (state.verifyNewEmail) {
      state.user.email = newEmail.value
      state.user.newEmail = ''
      newEmail.value = ''
      state.verifyNewEmail = ''
    }
    if (state.failedAuthenticateEmail) {
      state.failedAuthenticateEmail = ''
      failedAuthenticateFlag.value = false
    }
    next()
  })
</script>

<template>
  <h3 class="text-xl my-3">{{ userName }}さんのマイページ</h3>
  <div class="flex">
    <ButtonGroup />
  </div>
  <div class="daisy-divider" />
  <div class="w-full bg-base-200 mx-auto py-5">
    <div class="daisy-card w-7/12 bg-base-100 mx-auto shadow-xl">
      <div class="daisy-card-body">
        <div ref="ownerInfo">
          <p class="text-emerald-700">オーナー情報</p>
          <div class="flex flex-col">
            <p>
              この情報は、より簡単に予約できるよう予約情報の自動入力の為に利用されます。登録情報が公開されることはありません。
            </p>
            <SuccessAlert
              :show="changeUserFlag"
              message="設定を変更しました"
              @dismiss="changeUserFlag = false"
            />
            <FormLabel for="name">氏名</FormLabel>
            <FormInput
              id="name"
              v-model="formState.form.name"
              type="name"
              :has-error="!!formState.errors?.name"
            />
            <FormError :message="formState.errors?.name" />
            <FormLabel for="phoneNumber">携帯番号</FormLabel>
            <FormInput
              id="phoneNumber"
              v-model="formState.form.phoneNumber"
              type="text"
              :has-error="!!formState.errors?.phoneNumber"
            />
            <FormError :message="formState.errors?.phoneNumber" />
            <button
              class="w-5/12 daisy-btn daisy-btn-accent mx-auto mt-5"
              :class="{ 'daisy-loading': formState.isOwnerFormLoading }"
              @click="changeUserFunc"
            >
              変更
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="daisy-card w-7/12 bg-base-100 mx-auto shadow-xl mt-5">
      <div class="daisy-card-body">
        <div ref="emailContener">
          <p class="text-emerald-700">メールアドレス</p>
          <SuccessAlert
            v-for="(alert, index) in mailAlerts"
            :key="index"
            :show="alert.show"
            :message="alert.message"
            @dismiss="dismissAlert(index)"
          />

          <ErrorAlert
            :show="failedAuthenticateFlag"
            :message="failedAuthenticateEmail"
            @dismiss="failedAuthenticateFlag = false"
          />
          <div class="flex flex-col border border-2 p-5 mt-3">
            <p>
              ログイン、予約時の自動入力、予約の通知に使われます。
              予約内容や予約確認メールを1ヶ所にまとめられるよう、普段使いのメールアドレスを設定することをおすすめします。
            </p>
            <h4 class="font-bold mt-3">現在のメールアドレス</h4>
            <p class="mb-1">{{ verifyEmail }}</p>
            <p class="text-sm mb-3">このメールアドレスは認証済みです。</p>
            <div v-if="!newEmail || state.verifyNewEmail" class="flex flex-col">
              <FormLabel for="newEmail">新しいメールアドレス</FormLabel>
              <FormInput
                id="newEmail"
                v-model="formState.newEmail"
                type="email"
                :has-error="!!formState.errors?.email"
              />
              <FormError :message="formState.errors?.email" />
              <button
                class="w-5/12 daisy-btn daisy-btn-accent mx-auto mt-5"
                :class="{ 'daisy-loading': formState.isVerifyMailLoading }"
                @click="changeEmailFunc"
              >
                認証メール送信
              </button>
            </div>
          </div>
          <div
            v-if="(newEmail || sendConfirmEmail) && !state.verifyNewEmail"
            class="border border-2 p-5 mt-3"
          >
            <p class="font-bold">認証されていないメールアドレス</p>
            <div>
              <p class="mb-1">
                {{
                  newEmail
                }}宛に確認メールが送信されました。受信したメールに記載されているURLから、変更手続きを完了してください
              </p>
              <a class="daisy-link daisy-link-warning" @click="resendEmailFunc"
                >認証メール再送</a
              >
            </div>
            <p class="mt-1">{{ newEmail }}</p>
            <p class="my-2">このメールアドレスは未確認です</p>
            <button
              id="test"
              ref="test"
              class="w-5/12 daisy-btn mx-auto"
              :class="{ 'daisy-loading': formState.isDeleteChangeLoading }"
              @click="cancelEmailChangeFunc"
            >
              変更取り消し
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="daisy-card w-7/12 bg-base-100 mx-auto shadow-xl mt-5">
      <div class="daisy-card-body">
        <div ref="passwordContener">
          <p class="text-emerald-700">パスワード</p>
          <SuccessAlert
            :show="changePasswordFlag"
            :message="changePasswordMessage"
            @dismiss="changePasswordFlag = false"
          />
          <div class="flex flex-col border border-2 p-5 mt-3">
            <p>
              パスワードの変更を行うには、まず現在のパスワードを入力してください。次に、新しいパスワードを入力し、確認のためにもう一度同じパスワードを入力してください。
            </p>
            <FormLabel for="currentPassword">現在のパスワード</FormLabel>
            <FormInput
              id="currentPassword"
              v-model="formState.passwordForm.currentPassword"
              type="password"
              :has-error="!!formState.errors?.currentPassword"
            />
            <FormError :message="formState.errors?.currentPassword" />
            <FormLabel for="newPassword">新しいパスワード</FormLabel>
            <FormInput
              id="newPassword"
              v-model="formState.passwordForm.newPassword"
              type="password"
              :has-error="!!formState.errors?.newPassword"
            />
            <FormError :message="formState.errors?.newPassword" />
            <FormLabel for="password_confirmation">
              新しいパスワード(確認入力)
            </FormLabel>
            <FormInput
              id="password_confirmation"
              v-model="formState.passwordForm.newPasswordConfirmation"
              type="password"
              :has-error="!!formState.errors?.newPasswordConfirmation"
            />
            <FormError :message="formState.errors?.newPasswordConfirmation" />
            <button
              id="email"
              class="w-5/12 daisy-btn daisy-btn-accent mx-auto mt-5"
              :class="{
                'daisy-loading': formState.passwordForm.isPassWordFormLoading,
              }"
              @click="changePasswordFunc"
            >
              パスワード変更
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="daisy-card w-7/12 bg-base-100 mx-auto shadow-xl mt-5">
      <div class="daisy-card-body">
        <div>
          <p class="text-red-600">アカウントの削除</p>
          <div class="flex flex-col border border-2 border-red-400 p-10 mt-3">
            <p class="mb-5 text-red-500">
              アカウントを削除する前に、以下の注意事項をご確認ください。
            </p>
            <ul class="delete-account-desc text-red-500">
              <li>一度削除するとアカウントは復元できません。</li>
              <li>アカウントを削除しても受診予約はキャンセルされません。</li>
              <li>
                削除後も受診予約がある場合、リマインドメールがご登録のメールアドレス宛に送信される場合があります。
              </li>
              <li>過去に予約をした動物病院のカルテ情報は削除されません。</li>
            </ul>
            <button
              class="w-5/12 daisy-btn mx-auto mt-5 daisy-btn-error"
              :class="{ 'daisy-loading': formState.isDeleteAccountLoading }"
              @click="deleteAccountFunc"
            >
              アカウント削除
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
  .delete-account-desc {
    list-style-type: circle;
  }
</style>
