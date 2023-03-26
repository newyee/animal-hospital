<script setup lang="ts">
  import useReserve from '../hooks/useReserve'
  const { state } = useReserve()
</script>
<template>
  <div>
    <div class="daisy-card w-6/12 bg-base-100 mx-auto shadow-xl">
      <div class="daisy-card-body">
        <h1 class="daisy-card-title mx-auto">キャンセル完了</h1>
        <div
          v-if="state.cancelComplete"
          class="border-solid border border-indigo-600 p-3 mt-5"
        >
          <div class="flex">
            <p class="grow-0 w-3/12 text-center pt-7 text-gray-400">予約内容</p>
            <div class="pt-5">
              <p>{{ state.cancelComplete?.data?.date }}</p>
              <p>{{ state.cancelComplete?.data?.medicalType }}</p>
            </div>
          </div>
        </div>
        <router-link class="daisy-link daisy-link-accent mx-auto mt-10" to="/"
          >トップページへ戻る</router-link
        >
      </div>
    </div>
  </div>
</template>
