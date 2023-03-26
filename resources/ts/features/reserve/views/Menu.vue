<script setup lang="ts">
  import useReserve from '@/features/reserve/hooks/useReserve'
  import { useRouter } from 'vue-router'
  import { ref, onUnmounted } from 'vue'

  const router = useRouter()

  const { state } = useReserve()
  window.addEventListener('beforeunload', confirmSave)

  onUnmounted(() => {
    window.removeEventListener('beforeunload', confirmSave)
  })

  function reserveOtherDogAndCat() {
    state.menu = 1
    router.push('/reservation/time')
  }
  function reserveDogAndCat() {
    state.menu = 2
    router.push('/reservation/time')
  }
  function reserveOnlineConsultation() {
    state.menu = 3
    router.push('/reservation/time')
  }
  function confirmSave(event: { returnValue: string }) {
    event.returnValue = '予約内容が保存されませんが、よろしいですか？'
  }
  function reservePreviousVisit() {
    state.menu = 8
    router.push('/reservation/time')
  }
  function reserveAnotherVisit() {
    state.menu = 9
    router.push('/reservation/time')
  }
</script>
<template>
  <div class="daisy-card w-7/12 bg-base-100 mx-auto shadow-xl">
    <div class="daisy-card-body">
      <h2 class="daisy-card-title mx-auto">メニュー選択</h2>
      <ul
        class="daisy-steps daisy-steps-vertical lg:daisy-steps-horizontal w-10/12 mx-auto"
      >
        <li class="daisy-step daisy-step-primary">初再診選択</li>
        <li class="daisy-step daisy-step-primary">メニュー選択</li>
        <li class="daisy-step">日時選択</li>
        <li class="daisy-step">情報入力</li>
        <li class="daisy-step">内容確認</li>
      </ul>
      <div class="daisy-divider" />

      <div v-if="state.isVisit === 1">
        <div
          class="flex flex-col w-full lg:flex-row justify-around items-center"
        >
          <div class="w-4/12">
            <h2 class="text-lg font-semibold">※犬猫以外の初診※</h2>
            <p>
              ウサギ・フェレット・モルモット・ハムスターなどはこちらを選択してください
            </p>
          </div>
          <button
            class="bg-green-600 hover:bg-green-500 text-white rounded px-4 py-2 w-4/12"
            @click="reserveOtherDogAndCat()"
          >
            選択する
          </button>
        </div>
        <div class="daisy-divider" />
        <div
          class="flex flex-col w-full lg:flex-row justify-around items-center"
        >
          <div class="w-4/12">
            <h2 class="text-lg font-semibold">※犬と猫の初診※</h2>
            <p>ワンちゃんネコちゃんの初診はこちらを選択してください</p>
          </div>
          <button
            class="bg-green-600 hover:bg-green-500 text-white rounded px-4 py-2 w-4/12"
            @click="reserveDogAndCat()"
          >
            選択する
          </button>
        </div>
        <div class="daisy-divider" />
        <div
          class="flex flex-col w-full lg:flex-row justify-around items-center"
        >
          <div class="w-4/12">
            <h2 class="text-lg font-semibold">オンライン相談</h2>
            <span
              class="bg-red-100 text-red-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300"
              >オンライン決済（クレジットカード）必須</span
            >
          </div>
          <button
            class="daisy-btn-disabled  text-white rounded px-4 py-2 w-4/12"
            @click="reserveOnlineConsultation()"
          >
            <span class="text-gray-400">開発中</span>
          </button>
        </div>
      </div>
      <div v-if="state.isVisit === 2">
        <div
          class="flex flex-col w-full lg:flex-row justify-around items-center"
        >
          <div class="w-4/12">
            <h2 class="text-lg font-semibold">前回の再診</h2>
            <p>ついでにケアや予防も可能です。</p>
          </div>
          <button
            class="bg-green-600 hover:bg-green-500 text-white rounded px-4 py-2 w-4/12"
            @click="reservePreviousVisit()"
          >
            選択する
          </button>
        </div>
        <div class="daisy-divider" />
        <div
          class="flex flex-col w-full lg:flex-row justify-around items-center"
        >
          <div class="w-4/12">
            <h2 class="text-lg font-semibold">別件での診察</h2>
            <p>ついでにケアや予防も可能です。</p>
          </div>
          <button
            class="bg-green-600 hover:bg-green-500 text-white rounded px-4 py-2 w-4/12"
            @click="reserveAnotherVisit()"
          >
            選択する
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
