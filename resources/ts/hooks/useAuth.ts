import { reactive } from 'vue'
import { createGlobalState } from '@vueuse/core'
import { useAxios } from '@vueuse/integrations/useAxios'
import { TOKEN_STORE_NAME } from '@/config'
import axios, { AxiosError } from '@/libs/axios'
import {
  RegisterForm,
  LoginForm,
  AuthorisationUser,
  User,
  ConfirmRegistration,
  registerVerify,
  UpdateUser,
  ChangePassword,
} from '@/types'

/**
 * https://vueuse.org/shared/createGlobalState/
 */
const useAuth = createGlobalState(() => {
  const state = reactive<{
    user?: User
    isFinished?: boolean
    ProvisionalUser?: ConfirmRegistration
    isLogin?: boolean
    updateUserData?: User
    verifyNewEmail?: string
    failedAuthenticateEmail?: string
    emailResendMessage?: string
    cancelChangeEmail?: string
    changePasswordMessage?: string
    showDeleteSuccess?: boolean
    confirmationSentMessage?: string
    newEmailVerifyMessage?: string
    passwordVerifyMail?: string
    passwordResetError?: string
    passwordResetSuccess?: string
  }>({
    isFinished: false,
    isLogin: false,
  })

  async function me(): Promise<boolean> {
    if (state.user) {
      return true
    }

    const response = await useAxios<User>('/me', axios)
    state.isFinished = response?.isFinished?.value
    if (response?.data?.value) {
      state.user = response?.data?.value
    }

    return !!state.user
  }

  async function resetPassword(data: {
    email: string
  }): Promise<AxiosError | undefined> {
    const response = await useAxios<void>(
      '/user/password/reset',
      { method: 'POST', data },
      axios
    )
    return response.error.value as AxiosError
  }

  async function changePassword(
    data: ChangePassword
  ): Promise<AxiosError | undefined> {
    const response = await useAxios<{ message: string }>(
      '/user/password/update',
      { method: 'PUT', data },
      axios
    )

    if (response?.data?.value) {
      state.changePasswordMessage = response.data.value.message
    }
    return response.error.value as AxiosError
  }
  async function cancelChangeEmail(): Promise<AxiosError | undefined> {
    const response = await useAxios<{ message: string }>(
      '/user/email/change/cancel',
      { method: 'DELETE' },
      axios
    )
    if (response?.data?.value) {
      state.cancelChangeEmail = response.data.value.message
    }
    return response.error.value as AxiosError
  }

  async function resendEmail(): Promise<AxiosError | undefined> {
    const response = await useAxios<{ message: string }>(
      '/user/email/resend',
      axios
    )
    if (response?.data?.value) {
      state.emailResendMessage = response.data.value.message
    }
    return response.error.value as AxiosError
  }

  async function changeUserInfo(
    data: UpdateUser
  ): Promise<AxiosError | undefined> {
    const response = await useAxios<UpdateUser>(
      '/user/update',
      { method: 'PUT', data },
      axios
    )
    if (response?.data?.value) {
      const { name, phoneNumber } = response.data.value
      state.user.name = name
      state.user.phoneNumber = phoneNumber
    }
    return response.error.value as AxiosError
  }

  async function changeEmail(data: {
    email: string
    userId: number
  }): Promise<AxiosError | undefined> {
    const response = await useAxios<{
      newEmail: string
      message: string
      userId: number
    }>(`/user/email/update`, { method: 'PUT', data }, axios)
    if (response?.data?.value) {
      const resData = response.data.value
      state.confirmationSentMessage = resData.message
      state.user.newEmail = resData.newEmail
    }
    return response.error.value as AxiosError
  }
  async function changeEmailVerify(data: registerVerify): Promise<void> {
    const response = await useAxios(
      '/user/email/change/verify',
      { method: 'POST', data },
      axios
    )
    if (response?.data?.value) {
      state.verifyNewEmail = response.data.value.email
      state.newEmailVerifyMessage = response.data.value.message
    }
    if (response.error.value) {
      state.failedAuthenticateEmail =
        response.error.value?.response?.data?.error
    }
  }

  async function register(data: RegisterForm): Promise<AxiosError | undefined> {
    const response = await useAxios<ConfirmRegistration>(
      '/register',
      { method: 'POST', data },
      axios
    )

    if (response?.data?.value) {
      const resMail = response.data.value
      state.ProvisionalUser = resMail
    }
    return response.error.value as AxiosError
  }
  async function verify(data: registerVerify): Promise<AxiosError | undefined> {
    const response = await useAxios(
      '/register/verify',
      { method: 'POST', data },
      axios
    )
    return response.error.value as AxiosError
  }
  async function passwordVerify(data: {
    token: string
  }): Promise<AxiosError | undefined> {
    const response = await useAxios(
      '/user/password/verify',
      { method: 'POST', data },
      axios
    )
    if (response.error.value) {
      state.passwordResetError = response.error.value?.response?.data?.error
    }
    if (response.data.value) {
      state.passwordVerifyMail = response.data.value.email
    }

    return response.error.value as AxiosError
  }

  async function passwordReset(data: {
    email: string
    password: string
    token: string
  }): Promise<AxiosError | undefined> {
    const response = await useAxios(
      '/user/password/change',
      { method: 'POST', data },
      axios
    )
    if (response.error.value) {
      const errors = response.error.value?.response?.data?.errors
      const error = response.error.value?.response?.data?.error
      if (errors) {
        if (errors.email || errors.token) {
          state.passwordResetError =
            '認証エラーが発生しました。もう一度パスワードリセット用のメールを送信してください'
        }
      }
      if (error) {
        state.passwordResetError = error
      }
    }
    if (response.data.value) {
      state.passwordResetSuccess = response.data.value.message
    }
    return response.error.value as AxiosError
  }

  async function login(data: LoginForm): Promise<AxiosError | undefined> {
    const response = await useAxios<AuthorisationUser>(
      '/login',
      { method: 'POST', data },
      axios
    )
    if (response?.data?.value) {
      const authorisationUser = response.data.value
      state.user = authorisationUser.user
      localStorage.setItem(
        TOKEN_STORE_NAME,
        authorisationUser.authorisation.token
      )
    }
    return response.error.value as AxiosError
  }

  async function logout(): Promise<void> {
    await useAxios<void>('/logout', { method: 'DELETE' }, axios)
    state.user = undefined
    state.verifyNewEmail = undefined
    state.failedAuthenticateEmail = undefined
    localStorage.removeItem(TOKEN_STORE_NAME)
  }

  async function isLoggedIn(): Promise<boolean> {
    return !!state.user || (await me())
  }

  async function deleteAccount(): Promise<void> {
    await useAxios<void>('/user/delete', { method: 'DELETE' }, axios)
    delete state.user
    localStorage.removeItem(TOKEN_STORE_NAME)
    state.showDeleteSuccess = true
  }

  return {
    state,
    me,
    login,
    logout,
    isLoggedIn,
    register,
    verify,
    changeUserInfo,
    changeEmail,
    changeEmailVerify,
    resendEmail,
    cancelChangeEmail,
    changePassword,
    deleteAccount,
    resetPassword,
    passwordVerify,
    passwordReset,
  }
})

export default useAuth
