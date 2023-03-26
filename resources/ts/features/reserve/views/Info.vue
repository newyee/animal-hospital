<script setup lang="ts">
  import useReserve from '@/features/reserve/hooks/useReserve'
  import { useRouter } from 'vue-router'
  import { ref, reactive, watch, onUnmounted } from 'vue'
  import { FormError, FormLabel, FormInput } from '@/components/Form'
  import { ReserveInfo } from '@/types'
  import { AxiosError } from '@/libs/axios'
  import useAuth from '@/hooks/useAuth'
  const store = useAuth()

  function confirmSave(event: { returnValue: string }) {
    event.returnValue = '予約内容が保存されませんが、よろしいですか？'
  }
  window.addEventListener('beforeunload', confirmSave)

  onUnmounted(() => {
    window.removeEventListener('beforeunload', confirmSave)
  })
  const router = useRouter()
  const { confirm, state } = useReserve()
  const isChecked = ref(false)
  const disabled = ref(false)
  const menu = state.menu
  let menuValue = ''

  switch (menu) {
    case 1:
      menuValue = '※犬猫以外の初診※'
      break
    case 2:
      menuValue = '※犬と猫の初診※'
      break
    case 3:
      menuValue = 'オンライン相談'
      break
    case 8:
      menuValue = '前回の再診'
      break
    case 9:
      menuValue = '別件での診察'
      break
  }

  watch(isChecked, (next: boolean) => {
    //
    if (next) {
      disabled.value = true
      reserveState.form.ticketNumber = '（初診）'
    } else {
      disabled.value = false
      reserveState.form.ticketNumber = ''
    }
  })

  interface ReservationState {
    form: ReserveInfo
    errors: { [k: string]: string }
    isLoading: boolean
  }

  const reserveState = reactive<ReservationState>({
    form: {
      ownerName: '',
      ticketNumber: '',
      petName: '',
      petType: '',
      phoneNumber: '',
      email: '',
      remark: '',
    },
    errors: {},
    isLoading: false,
  })

  async function onSubmit() {
    if (store.state.user?.name) {
      reserveState.form.ownerName = store.state.user?.name
    }
    if (store.state.user?.email) {
      reserveState.form.email = store.state.user?.email
    }
    if (store.state.user?.phoneNumber) {
      reserveState.form.phoneNumber = store.state.user?.phoneNumber
    }
    let formatTicketNumber = ''
    if (reserveState.form.ticketNumber === '（初診）') {
      formatTicketNumber = '0'
    } else {
      formatTicketNumber = reserveState.form.ticketNumber
    }
    const pData = {
      ownerName: reserveState.form.ownerName,
      ticketNumber: formatTicketNumber,
      petName: reserveState.form.petName,
      petType: reserveState.form.petType,
      phoneNumber: reserveState.form.phoneNumber,
      email: reserveState.form.email,
      remark: reserveState.form.remark,
    }
    reserveState.errors = {}
    reserveState.isLoading = true
    const error: AxiosError = await confirm(pData)
    reserveState.isLoading = false
    if (error) {
      reserveState.errors = error.response.data.errors
      return
    }
    if (state.menu === 3) {
      router.push('/reservation/payment')
      return
    }
    router.push('/reservation/confirm')
  }
</script>
<template>
  <div class="daisy-card w-7/12 bg-base-100 mx-auto shadow-xl">
    <div class="daisy-card-body">
      <h2 class="daisy-card-title mx-auto">日時選択</h2>
      <ul
        class="daisy-steps daisy-steps-vertical lg:daisy-steps-horizontal w-10/12 mx-auto"
      >
        <li class="daisy-step daisy-step-primary">初再診選択</li>
        <li class="daisy-step daisy-step-primary">メニュー選択</li>
        <li class="daisy-step daisy-step-primary">日時選択</li>
        <li class="daisy-step daisy-step-primary">情報入力</li>
        <li class="daisy-step">内容確認</li>
      </ul>
      <div class="daisy-divider" />
      <div
        class="flex items-center py-3 mb-2 border border-solid border-emerald-500"
      >
        <p class="text-center">予約内容</p>
        <div class="w-8/12">
          <p>{{ state.formatDate }} {{ state.displayTime }}</p>
          <p>診察</p>
          <p>{{ menuValue }}</p>
        </div>
      </div>
      <p class="font-semibold text-center">
        受信するペットの情報を入力して下さい
      </p>
      <form method="POST" @submit.prevent="onSubmit">
        <div class="flex">
          <FormLabel for="ticketNumber" class="w-40 pb-11 font-semibold"
            >診察券番号</FormLabel
          >
          <div>
            <FormInput
              v-model="reserveState.form.ticketNumber"
              type="text"
              placeholder=""
              :has-error="!!reserveState.errors?.ticketNumber"
              :disabled="disabled"
              class="mt-2"
            />
            <FormError :message="reserveState.errors?.ticketNumber" />
            <div class="daisy-form-control">
              <label class="daisy-cursor-pointer flex">
                <input
                  v-model="isChecked"
                  type="checkbox"
                  class="daisy-checkbox daisy-checkbox-accent m-3"
                />
                <span class="mt-3"
                  >初診(当病院に掛かるのは初めて) または 番号不明</span
                >
              </label>
            </div>
          </div>
        </div>
        <div class="flex">
          <FormLabel for="ticketNumber" class="w-40 mb-3 font-semibold"
            >ペット名</FormLabel
          >
          <div class="mb-5">
            <FormInput
              v-model="reserveState.form.petName"
              type="text"
              placeholder=""
              :has-error="!!reserveState.errors?.petName"
            />
            <FormError :message="reserveState.errors?.petName" />
          </div>
        </div>
        <div class="flex">
          <FormLabel for="ticketNumber" class="w-40 mb-3 font-semibold"
            >ペットの種類</FormLabel
          >
          <div class="mb-5">
            <select
              v-model="reserveState.form.petType"
              class="daisy-select daisy-select-bordered w-full max-w-xs"
            >
              <option selected value="1">イヌ</option>
              <option value="2">ネコ</option>
              <option value="3">ウサギ</option>
              <option value="4">ハムスター</option>
              <option value="5">フェレット</option>
              <option value="6">モルモット</option>
            </select>
            <FormError :message="reserveState.errors?.petType" />
          </div>
        </div>
        <div class="flex">
          <FormLabel for="ticketNumber" class="w-40 mb-3 font-semibold"
            >飼い主名</FormLabel
          >
          <div v-if="store.state.user?.name">
            <p class="mb-1">{{ store.state.user?.name }}</p>
            <p class="text-sm mb-3 text-slate-500">
              変更する場合は、<router-link
                to="/mypage/settings"
                class="text-blue-600"
                >マイページ</router-link
              >より変更してください
            </p>
          </div>
          <div v-else class="mb-7">
            <FormInput
              v-model="reserveState.form.ownerName"
              type="text"
              placeholder=""
              :has-error="!!reserveState.errors?.ownerName"
            />
            <FormError :message="reserveState.errors?.ownerName" />
          </div>
        </div>

        <div class="flex">
          <FormLabel for="phoneNumber" class="w-40 mb-5 font-semibold"
            >携帯番号</FormLabel
          >
          <div v-if="store.state.user?.phoneNumber">
            <p class="mb-1">
              {{ store.state.user?.phoneNumber }}
            </p>
            <p class="text-sm mb-3 text-slate-500">
              変更する場合は、<router-link
                to="/mypage/settings"
                class="text-blue-600"
                >マイページ</router-link
              >より変更してください
            </p>
          </div>
          <div v-else class="mb-5">
            <FormInput
              v-model="reserveState.form.phoneNumber"
              type="text"
              placeholder=""
              :has-error="!!reserveState.errors?.phoneNumber"
            />
            <FormError :message="reserveState.errors?.phoneNumber" />
          </div>
        </div>
        <div class="flex">
          <FormLabel for="email" class="w-40 mb-5 font-semibold"
            >メールアドレス</FormLabel
          >
          <div v-if="store.state.user?.email">
            <p class="mb-1">{{ store.state.user?.email }}</p>
            <p class="text-sm mb-3 text-slate-500">
              変更する場合は、<router-link
                to="/mypage/settings"
                class="text-blue-600"
                >マイページ</router-link
              >より変更してください
            </p>
          </div>
          <div v-else class="mb-7">
            <FormInput
              v-model="reserveState.form.email"
              type="email"
              placeholder=""
              :has-error="!!reserveState.errors?.email"
            />
            <FormError :message="reserveState.errors?.email" />
          </div>
        </div>
        <div class="flex font-semibold">
          <FormLabel for="remark" class="w-40 mb-5"
            >備考(症状・その他)</FormLabel
          >
          <div class="mb-7">
            <textarea
              v-model="reserveState.form.remark"
              class="daisy-textarea daisy-textarea-bordered"
              placeholder=""
              :has-error="!!reserveState.errors?.remark"
              rows="3"
              required
            ></textarea>
            <FormError :message="reserveState.errors?.remark" />
          </div>
        </div>
        <div class="text-center">
          <button
            v-if="state.menu === 3"
            class="daisy-btn daisy-btn-primary w-6/12"
            :class="{ 'daisy-loading': reserveState.isLoading }"
          >
            支払い方法の選択へ進む
          </button>
          <button
            v-else
            class="daisy-btn daisy-btn-primary w-6/12"
            :class="{ 'daisy-loading': reserveState.isLoading }"
          >
            入力内容を確認する
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
