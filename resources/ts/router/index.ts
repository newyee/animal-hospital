import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/pages/Home.vue'
import useAuth from '@/hooks/useAuth'
import useReserve from '@/features/reserve/hooks/useReserve'
const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      component: Home,
      meta: { layout: 'Default' },
    },
    {
      path: '/login',
      component: () => import('@/pages/Login.vue'),
      meta: { layout: 'Auth', requiresAuth: false },
    },
    {
      path: '/register',
      component: () => import('@/pages/Register.vue'),
      meta: { layout: 'Auth', requiresAuth: false },
    },
    {
      path: '/register-confirm',
      component: () => import('@/pages/RegisterConfirm.vue'),
      meta: { layout: 'Auth', requiresAuth: false },
    },
    {
      path: '/register/verify',
      component: () => import('@/pages/RegisterVerify.vue'),
      meta: { layout: 'Auth', requiresAuth: false },
    },
    {
      path: '/mypage',
      component: () => import('@/pages/MyPage.vue'),
      meta: { layout: 'Dashboard', requiresAuth: true },
    },
    {
      path: '/mypage/settings',
      component: () => import('@/pages/Settings.vue'),
      meta: { layout: 'Dashboard', requiresAuth: true },
    },
    {
      path: '/mypage/email/change/verify',
      component: () => import('@/pages/EmailChangeVerify.vue'),
      meta: { layout: 'Dashboard', requiresAuth: true },
    },
    {
      path: '/about',
      component: () => import('@/pages/About.vue'),
      meta: { layout: 'Dashboard', requiresAuth: true },
    },
    {
      path: '/contact',
      component: () => import('@/pages/Contact.vue'),
      meta: { layout: 'Dashboard', requiresAuth: true },
    },
    {
      path: '/reservation/select',
      component: () => import('@/pages/ReservationSelect.vue'),
      meta: { layout: 'Default' },
    },
    {
      path: '/reservation/menu',
      component: () => import('@/pages/ReservationMenu.vue'),
      meta: { layout: 'Default' },
    },
    {
      path: '/reservation/time',
      component: () => import('@/pages/ReservationTime.vue'),
      meta: { layout: 'Default' },
    },
    {
      path: '/reservation/info',
      component: () => import('@/pages/ReservationInfo.vue'),
      meta: { layout: 'Default' },
    },
    {
      path: '/reservation/confirm',
      component: () => import('@/pages/Confirm.vue'),
      meta: { layout: 'Default' },
    },
    {
      path: '/reservation/complete',
      component: () => import('@/pages/Complete.vue'),
      meta: { layout: 'Default' },
    },
    {
      path: '/reservation/cancel',
      component: () => import('@/pages/ReserveCancel.vue'),
      meta: { layout: 'Default' },
    },
    {
      path: '/reservation/cancel/complete',
      component: () => import('@/pages/ReserveCancelComplete.vue'),
      meta: { layout: 'Default' },
    },
    {
      path: '/user/delete-account',
      component: () => import('@/pages/DeleteAccountSuccess.vue'),
      meta: { layout: 'Auth', requiresAuth: false },
    },
    {
      path: '/user/reset-password',
      component: () => import('@/pages/ResetPassword.vue'),
      meta: { layout: 'Auth', requiresAuth: false },
    },
    {
      path: '/user/password/verify',
      component: () => import('@/pages/PasswordVerify.vue'),
      meta: { layout: 'Auth', requiresAuth: false },
    },
    {
      path: '/:catchAll(.*)',
      component: () => import('@/pages/Error404.vue'),
      meta: { layout: 'Default' },
    },
    {
      path: '/error500',
      component: () => import('@/pages/Error500.vue'),
      meta: { layout: 'Default' },
    },
  ],
})

router.beforeEach(async (to, _, next) => {
  const auth = useAuth()
  const storeReserve = useReserve()
  const isAuth = await auth.isLoggedIn()
  if (to.path === '/mypage/email/change/verify') {
    next()
  }
  if (to.path === '/login' && isAuth) {
    next({ path: '/' })
  } else if (
    to.matched.some(
      (record) =>
        record.meta.requiresAuth != undefined && record.meta.requiresAuth
    ) &&
    !isAuth
  ) {
    next({ path: '/login' })
  } else if (
    to.matched.some(
      (record) =>
        record.meta.requiresAuth != undefined && !record.meta.requiresAuth
    ) &&
    isAuth
  ) {
    next({ path: '/' })
  } else if (
    to.path === '/register-confirm' &&
    auth.state.ProvisionalUser === undefined
  ) {
    next({ path: '/' })
  } else if (
    to.path === '/reservation/menu' &&
    storeReserve.state.isVisit === undefined
  ) {
    next({ path: '/' })
  } else if (
    to.path === '/reservation/time' &&
    (storeReserve.state.isVisit === undefined ||
      storeReserve.state.menu === undefined)
  ) {
    next({ path: '/' })
  } else if (
    to.path === '/reservation/info' &&
    (storeReserve.state.isVisit === undefined ||
      storeReserve.state.menu === undefined ||
      storeReserve.state.date === undefined ||
      storeReserve.state.timeZoneId === undefined)
  ) {
    next({ path: '/' })
  } else if (
    to.path === '/reservation/confirm' &&
    (storeReserve.state.isVisit === undefined ||
      storeReserve.state.menu === undefined ||
      storeReserve.state.date === undefined ||
      storeReserve.state.timeZoneId === undefined ||
      storeReserve.state.reserveInfo === undefined)
  ) {
    next({ path: '/' })
  } else if (
    to.path === '/reservation/complete' &&
    storeReserve.state.completeReserveInfo === undefined
  ) {
    next({ path: '/' })
  } else if (
    to.path === '/user/delete-account' &&
    !auth.state.showDeleteSuccess
  ) {
    next({ path: '/' })
  } else {
    next()
  }
})
export default router
