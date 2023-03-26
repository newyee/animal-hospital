<script setup lang="ts">
  import { useRoute, useRouter } from 'vue-router'
  import Error404 from '@/features/error/views/Error404.vue'
  import userReserve from '@/features/reserve/hooks/useReserve'
  import { FormError, FormLabel } from '@/components/Form'
  import { reactive, ref } from 'vue'
  const { cancelReservation, cancelReservationInfo, state } = userReserve()
  const route = useRoute()
  const router = useRouter()
  const error404 = ref(false)
  const error500 = ref(false)
  const verifyToken: { token: string } = {
    token: String(route.query.token),
  }
  const error = await cancelReservationInfo(verifyToken)
  if (error) {
    error404.value = true
  }
  interface ReservationState {
    form: {
      cancellationReason: string
    }
    errors: { [k: string]: string }
    isLoading: boolean
  }
  const reserveState = reactive<ReservationState>({
    form: {
      cancellationReason: '',
    },
    errors: {},
    isLoading: false,
  })
  async function onSubmit() {
    const confirmed = window.confirm('本当にキャンセルしますか？')
    if (!confirmed) {
      return
    }
    reserveState.isLoading = true
    const pData = {
      cancellationReason: reserveState.form.cancellationReason,
      token: verifyToken.token,
    }
    const error: AxiosError = await cancelReservation(pData)
    if (error) {
      if (error.response.status === 404) {
        error404.value = true
        return
      }
      if (error.response.status === 500) {
        error500.value = true
        router.push('/error500')
        return
      }
      reserveState.errors = error.response.data.errors
      return
    }
    reserveState.isLoading = false
    router.push('/reservation/cancel/complete')
  }
</script>
<template>
  <div>
    <div v-if="error404">
      <Error404 />
    </div>
    <div v-else>
      <div class="daisy-card w-6/12 bg-base-100 mx-auto shadow-xl">
        <div class="daisy-card-body">
          <h1 class="daisy-card-title mx-auto">予約のキャンセル</h1>
          <div v-if="state.cancelled" class="mt-5">
            <div class="daisy-alert daisy-alert-error shadow-lg">
              <div>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="stroke-current flex-shrink-0 h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                <span
                  >この予約はキャンセルできません。
                  窓口で受付済み、またはキャンセル済みです。</span
                >
              </div>
            </div>
          </div>
          <div class="border-solid border border-indigo-600 p-3 mt-5">
            <div class="flex">
              <p class="grow-0 w-3/12 text-center pt-6 text-gray-400">
                予約内容
              </p>
              <div class="py-3">
                <p>{{ state.cancelInfo?.data?.date }}</p>
                <p>{{ state.cancelInfo?.data?.medicalType }}</p>
              </div>
            </div>
          </div>
          <div v-if="!state.cancelled">
            <div class="daisy-divider" />
            <form method="POST" @submit.prevent="onSubmit">
              <div class="flex items-center">
                <div class="font-semibold">
                  <FormLabel for="cancellationReason" class="mb-5 mr-3"
                    >キャンセル理由 (必須)
                  </FormLabel>
                </div>
                <div class="mb-7 w-1/2">
                  <textarea
                    v-model="reserveState.form.cancellationReason"
                    class="daisy-textarea daisy-textarea-bordered w-full"
                    placeholder=""
                    :has-error="!!reserveState.errors?.cancellationReason"
                    rows="3"
                    required
                  ></textarea>
                  <FormError
                    :message="reserveState.errors?.cancellationReason"
                  />
                </div>
              </div>
              <div class="text-center">
                <button
                  class="daisy-btn daisy-btn-primary w-6/12"
                  :class="{ 'daisy-loading': reserveState.isLoading }"
                >
                  予約をキャンセルする
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
