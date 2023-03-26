<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { MYPAGE_TITLE } from '../config'
  import useAuth from '@/hooks/useAuth'
  import useReservedInfo from '@/hooks/useReservedInfo'
  import { ref, onMounted, watch } from 'vue'
  import { UserReservedList } from '@/types'
  import router from '@/router'

  useHead({
    title: MYPAGE_TITLE,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${MYPAGE_TITLE} Page` }],
  })
  const { state } = useAuth()
  const { reservedInfoState, getReservedInfo } = useReservedInfo()
  const userId = state.user?.id ?? 0
  const reservedData = ref<UserReservedList | undefined>()
  const userName = state.user?.name as string
  const loading = ref(true)

  async function getReservedData() {
    const reqData = { userId: userId }
    await getReservedInfo(reqData)
  }
  function goToAccountSettingsPage() {
    router.push({
      path: '/mypage/settings',
    })
  }

  onMounted(async () => {
    await getReservedData()
    reservedData.value = reservedInfoState.reservedInfo
    loading.value = false
  })
</script>

<template>
  <h3 class="text-xl my-3">{{ userName }}さんのマイページ</h3>
  <div class="flex">
    <button
      class="mr-2 daisy-btn daisy-btn-active daisy-btn-outline daisy-btn-primary"
    >
      トップ
    </button>
    <button
      class="daisy-btn daisy-btn-outline daisy-btn-primary"
      @click="goToAccountSettingsPage"
    >
      アカウント設定
    </button>
  </div>
  <div class="daisy-divider" />
  <h3 class="text-center mb-5 daisy-accent font-semibold">現在の予約状況</h3>
  <div class="daisy-card w-7/12 bg-base-200 mx-auto shadow-xl">
    <div class="daisy-card-body">
      <template v-if="loading">
        <div class="flex justify-center items-center bg-gray-100">
          <div
            class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full"
            role="status"
          >
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </template>
      <template v-else>
        <div v-if="reservedData && Object.keys(reservedData).length">
          <div v-for="(item, index) in reservedData" :key="index">
            <p>{{ item.date }}</p>
            <p>{{ item.medicalType }}</p>
            <p class="mb-5">{{ item.petName }}</p>
          </div>
        </div>
        <div v-else>
          <p>予約はまだありません</p>
        </div>
      </template>
    </div>
  </div>
</template>
