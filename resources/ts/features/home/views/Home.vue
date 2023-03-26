<script setup lang="ts">
  import { useHead } from '@vueuse/head'
  import { APP_TITLE } from '@/config'
  import { HOME_TITLE } from '../config'
  import { useRouter } from 'vue-router'

  useHead({
    title: HOME_TITLE,
    titleTemplate: (title) => `${title} | ${APP_TITLE}`,
    meta: [{ name: 'description', content: `${HOME_TITLE} Page` }],
  })
  const router = useRouter()
  function reserveMedical() {
    router.push('/reservation/select')
  }
</script>

<template>
  <div class="w-6/12 daisy-card bg-base-100 mx-auto pb-5">
    <div class="daisy-card-body">
      <h1 class="daisy-card-title mx-auto">ネット予約</h1>
    </div>
    <div class="daisy-divider my-0" />
    <div class="mt-3 flex justify-around">
      <p class="text-xl">診察</p>
      <div class="daisy-card-actions">
        <button
          class="bg-green-600 hover:bg-green-500 text-white rounded px-4 py-2"
          @click="reserveMedical()"
        >
          予約する
        </button>
      </div>
    </div>
  </div>
</template>
