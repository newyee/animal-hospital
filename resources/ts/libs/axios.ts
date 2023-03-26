import axios, { AxiosHeaders, AxiosRequestConfig } from 'axios'
import { API_BASE_URL, TOKEN_STORE_NAME } from '@/config'
import router from '@/router'
import useAuth from '@/hooks/useAuth'

const getAuthState = () => {
  const { state } = useAuth()
  return state
}
const instance = axios.create({
  baseURL: API_BASE_URL,
})
instance.interceptors.request.use((config: AxiosRequestConfig) => {
  if (config.headers) {
    ;(config.headers as AxiosHeaders).set(
      'Authorization',
      `Bearer ${localStorage.getItem(TOKEN_STORE_NAME)}`
    )
  }
  return config
})

instance.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    if (error.response.status === 401) {
      const state = getAuthState()
      delete state.user
      delete state.changePasswordMessage
      localStorage.removeItem(TOKEN_STORE_NAME)
      router.push('/login')
    }
    return Promise.reject(error)
  }
)

export default instance
export { AxiosError } from 'axios'
