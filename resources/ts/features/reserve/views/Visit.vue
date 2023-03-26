<script setup lang="ts">
  import useReserve from '@/features/reserve/hooks/useReserve'
  import { useRouter } from 'vue-router'

  const { state } = useReserve()

  const router = useRouter()
  function reserveFirstMedical(isVisit: number) {
    state.isVisit = isVisit
    router.push('/reservation/menu')
  }
  function revisit(isVisit: number) {
    state.isVisit = isVisit
    router.push('/reservation/menu')
  }
</script>
<template>
  <div class="daisy-card w-7/12 bg-base-100 mx-auto shadow-xl">
    <div class="daisy-card-body">
      <h2 class="daisy-card-title mx-auto">初診/再診選択</h2>
      <ul
        class="daisy-steps daisy-steps-vertical lg:daisy-steps-horizontal w-10/12 mx-auto"
      >
        <li class="daisy-step daisy-step-primary">初再診選択</li>
        <li class="daisy-step">メニュー選択</li>
        <li class="daisy-step">日時選択</li>
        <li class="daisy-step">情報入力</li>
        <li class="daisy-step">内容確認</li>
      </ul>
      <div class="daisy-divider"></div>
      <div class="flex flex-col w-full lg:flex-row justify-around">
        <div class="w-4/12">
          <button
            class="bg-green-600 hover:bg-green-500 text-white rounded px-4 py-2 w-full"
            @click="reserveFirstMedical(1)"
          >
            初診の方
          </button>
          <p class="mt-2">
            当院を初めて受診する子のみ初診扱いとなります（※複数頭受診される場合はそれぞれ予約が必要ですのでご注意ください）
          </p>
        </div>
        <div class="w-4/12">
          <button
            class="bg-green-600 hover:bg-green-500 text-white rounded px-4 py-2 w-full"
            @click="revisit(2)"
          >
            再診の方
          </button>
          <p class="mt-2">
            前回の再診だけでなく別件で改めて来院される場合も再診扱いとなります（同じお宅でも初めてかかる別の子は初診扱いとなります。※複数頭受診される場合はそれぞれ予約が必要ですのでご注意ください）
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
