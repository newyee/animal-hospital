<script setup lang="ts">
  import useReserve from '@/features/reserve/hooks/useReserve'
  import { computed } from 'vue'
  const store = useReserve()
  const completeReserveInfo = computed(() => store.state.completeReserveInfo)
</script>
<template>
  <div>
    <div class="daisy-card w-7/12 bg-base-100 mx-auto shadow-xl">
      <div class="daisy-card-body">
        <h1 class="daisy-card-title mx-auto">予約が完了しました</h1>
        <div class="text-left mx-auto">
          <p>
            あなたのメールアドレス:
            <span class="semi-bold">{{ completeReserveInfo?.email }}</span>
          </p>
          <p>登録されたメールアドレス宛に登録完了メールを送信しました。</p>
        </div>
        <div class="border-solid border border-indigo-600 p-3 mt-5">
          <div class="flex">
            <p class="grow-0 w-3/12 text-center pt-11 text-gray-400">
              予約内容
            </p>
            <div class="pt-5">
              <p>{{ completeReserveInfo?.formatDate }}</p>
              <p>診察</p>
              <p>※{{ completeReserveInfo?.medicalType }}※</p>
            </div>
          </div>
          <div class="daisy-divider" />
          <div class="flex">
            <p class="grow-0 w-3/12 text-center pt-11 text-gray-400">
              ペット情報
            </p>
            <div class="pt-5">
              <p>{{ completeReserveInfo?.petName }}</p>
              <p>
                <span class="daisy-badge daisy-badge-accent">ペットの種類</span>
                {{ completeReserveInfo?.petType }}
              </p>
            </div>
          </div>
          <div class="daisy-divider" />
          <div class="flex">
            <p class="grow-0 w-3/12 text-center pt-11 text-gray-400">連絡先</p>
            <div class="pt-5">
              <p>{{ completeReserveInfo?.ownerName }}</p>
              <p>
                <span class="daisy-badge daisy-badge-accent">携帯番号</span>
                {{ completeReserveInfo?.phoneNumber }}
              </p>
              <p>
                <span class="daisy-badge daisy-badge-accent"
                  >メールアドレス</span
                >
                {{ completeReserveInfo?.email }}
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
            <p class="grow-0 w-3/12 text-center pt-5 pb-5 text-gray-400">
              備考
            </p>
            <div class="pt-5">
              <p>{{ completeReserveInfo?.remark }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
