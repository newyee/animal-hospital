import { EmailVerifyCode } from '@/types'
import useAuth from '@/hooks/useAuth'
import { useRoute } from 'vue-router'
import router from '@/router'

const { ChangeEmailVerify } = useAuth()
const route = useRoute()
const checkCode: EmailVerifyCode = {
  code: String(route.query.code),
}
await ChangeEmailVerify(checkCode)
router.push({
  path: '/mypage/settings',
})

export const EmailVerify = async (code: EmailVerifyCode) => {
  const { ChangeEmailVerify } = useAuth()
  const route = useRoute()
  const checkCode: EmailVerifyCode = {
    code: String(route.query.code),
  }
  await ChangeEmailVerify(checkCode)
  router.push('/mypage/settings')
}
