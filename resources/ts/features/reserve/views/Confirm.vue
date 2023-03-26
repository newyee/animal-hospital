<script setup lang="ts">
  import useReserve from '@/features/reserve/hooks/useReserve'
  import { AxiosError } from 'axios'
  import { onUnmounted, ref } from 'vue'
  import { useRouter } from 'vue-router'
  import useAuth from '@/hooks/useAuth'
  const { state, decide } = useReserve()
  const store = useAuth()
  function confirmSave(event: { returnValue: string }) {
    event.returnValue = '予約内容が保存されませんが、よろしいですか？'
  }
  window.addEventListener('beforeunload', confirmSave)

  onUnmounted(() => {
    window.removeEventListener('beforeunload', confirmSave)
  })
  let petTypeName = ''
  let displayMenu = ''
  const isLoading = ref(false)
  let displayTicketNumber = ''
  const router = useRouter()
  const petTypes: Record<string, string> = {
    '1': 'イヌ',
    '2': 'ネコ',
    '3': 'うさぎ',
    '4': 'ハムスター',
    '5': 'フェレット',
    '6': 'モルモット',
  }
  petTypeName = petTypes[state.reserveInfo?.petType ?? '']
  const menus: Record<string, string> = {
    '1': '※犬猫以外の初診※',
    '2': '※犬と猫の初診※',
    '3': '',
    '8': '前回の再診',
    '9': '別件での診察',
  }
  displayMenu = menus[state.menu ?? '']
  if (state.reserveInfo?.ticketNumber === '0') {
    displayTicketNumber = '（初診）'
  } else {
    displayTicketNumber = state.reserveInfo?.ticketNumber as string
  }
  async function decideReservation() {
    const pData = {
      userId: store.state.user?.id as number,
      visit: state.isVisit as number,
      medicalType: state.menu as number,
      date: state.date as string,
      timeZoneId: state.timeZoneId as number,
      ownerName: state.reserveInfo?.ownerName as string,
      ticketNumber: state.reserveInfo?.ticketNumber as string,
      petName: state.reserveInfo?.petName as string,
      petType: state.reserveInfo?.petType as string,
      phoneNumber: state.reserveInfo?.phoneNumber as string,
      email: state.reserveInfo?.email as string,
      remark: state.reserveInfo?.remark as string,
    }
    isLoading.value = true
    const error: AxiosError = await decide(pData)
    isLoading.value = false
    if (error) {
      router.push('/')
      return
    }
    router.push('/reservation/complete')
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
        <li class="daisy-step daisy-step-primary">日時選択</li>
        <li class="daisy-step daisy-step-primary">情報入力</li>
        <li class="daisy-step daisy-step-primary">内容確認</li>
      </ul>
      <div class="daisy-divider" />
      <div class="w-full mx-auto bg-red-100 text-red-500 p-3 font-medium">
        <p>予約は完了していません。</p>
        <p>内容を確認し、下の「予約を確定する」ボタンを押してください。</p>
      </div>
      <div class="border-solid border border-indigo-600 p-3 mt-5">
        <div class="flex">
          <p class="grow-0 w-3/12 text-center pt-11 text-gray-400">予約内容</p>
          <div class="pt-5">
            <p>{{ state.formatDate }} {{ state.displayTime }}</p>
            <p>診察</p>
            <p>{{ displayMenu }}</p>
          </div>
        </div>
        <div class="daisy-divider" />
        <div class="flex">
          <p class="grow-0 w-3/12 text-center pt-5 text-gray-400">
            オーナー情報
          </p>
          <div class="pt-5">
            <p>{{ state.reserveInfo?.ownerName }}</p>
          </div>
        </div>
        <div class="daisy-divider" />
        <div class="flex">
          <p class="grow-0 w-3/12 text-center pt-11 text-gray-400">
            ペット情報
          </p>
          <div class="pt-5">
            <p>{{ state.reserveInfo?.petName }}</p>
            <p>
              <span class="daisy-badge daisy-badge-accent">ペットの種類</span>
              {{ petTypeName }}
            </p>
            <p>
              <span class="daisy-badge daisy-badge-accent">診察券番号</span>
              {{ displayTicketNumber }}
            </p>
          </div>
        </div>
        <div class="daisy-divider" />
        <div class="flex">
          <p class="grow-0 w-3/12 text-center pt-11 text-gray-400">連絡先</p>
          <div class="pt-5">
            <p>
              <span class="daisy-badge daisy-badge-accent">携帯番号</span>
              {{ state.reserveInfo?.phoneNumber }}
            </p>
            <p>
              <span class="daisy-badge daisy-badge-accent">メールアドレス</span>
              {{ state.reserveInfo?.email }}
            </p>
            <p class="text-gray-400 text-sm">
              ※ご予約の完了通知およびご予約のリマインドに利用されます。
            </p>
            <p class="text-gray-400 text-sm">
              ※ドメイン指定受信を設定されている場合、「gmail.com」を受信可能に設定してください。
            </p>
          </div>
        </div>
        <div class="daisy-divider" />
        <div class="flex">
          <p class="grow-0 w-3/12 text-center pt-5 pb-5 text-gray-400">備考</p>
          <div class="pt-5">
            <p>{{ state.reserveInfo?.remark }}</p>
          </div>
        </div>
      </div>
      <button
        class="daisy-btn daisy-btn-primary w-5/12 mx-auto mt-5"
        :class="{ 'daisy-loading': isLoading }"
        @click="decideReservation()"
      >
        予約を確定する
      </button>
    </div>
  </div>
</template>
