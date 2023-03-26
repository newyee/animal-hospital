<script lang="ts" setup>
  import { defineProps, Transition, watch } from 'vue'
  import useReservation from '@/features/reserve/hooks/useReserve'
  import { useRouter } from 'vue-router'
  import { times } from '@/constants/times'
  const props = defineProps<{
    show: boolean
    timeZoneIds: number[]
    date: string
    formatStartDate: string
  }>()
  const { state } = useReservation()
  const timeZoneIdsSet = new Set(props.timeZoneIds)
  const router = useRouter()
  watch(
    () => props.timeZoneIds,
    (newVal) => {
      timeZoneIdsSet.clear()
      newVal.forEach((id) => {
        timeZoneIdsSet.add(id)
      })
      // timeZoneIdsSetが更新されたことを確認する
    },
    { deep: true }
  )

  function reserveDate(timeZoneId: number, timeZone: string) {
    state.timeZoneId = timeZoneId
    state.date = props.date
    state.formatDate = props.formatStartDate
    state.displayTime = timeZone
    router.push('/reservation/info')
  }
</script>

<template>
  <Transition name="modal">
    <div v-if="props.show" class="modal-mask" @click="$emit('close')">
      <div
        class="modal-container w-6/12"
        :style="{ transform: props.show ? 'scale(1)' : 'scale(0.9)' }"
        @click.stop
      >
        <button class="modal-close" @click="$emit('close')">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            class="w-6"
          >
            <title>close</title>
            <path
              d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"
            />
          </svg>
        </button>
        <div class="daisy-card">
          <div class="daisy-card-body">
            <h1 class="daisy-card-title mx-auto">時間帯選択</h1>
            <p class="text-center">
              {{
                formatStartDate
              }}の空き時間です。ご希望の時間帯を選択して下さい
            </p>
            <div class="modal-body flex flex-wrap justify-between">
              <div
                v-for="time in times"
                :key="time.id"
                class="times mb-5"
                :class="{
                  available: !timeZoneIdsSet.has(time.id),
                  unavailable: timeZoneIdsSet.has(time.id),
                }"
                @click="reserveDate(time.id, time.time)"
              >
                <div class="py-1.5">
                  <p class="text-center">{{ time.time }}</p>
                  <span v-if="timeZoneIdsSet.has(time.id)">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 24 24"
                      class="w-7 mx-auto"
                    >
                      <title>close</title>
                      <path
                        d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"
                      />
                    </svg>
                  </span>
                  <span v-else>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 24 24"
                      class="w-7 circle mx-auto"
                    >
                      <title>circle-outline</title>
                      <path
                        d="M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"
                      />
                    </svg>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
  .modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    transition: opacity 0.3s ease;
  }

  .modal-container {
    margin: auto;
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
  }

  .modal-header h3 {
    margin-top: 0;
    color: #42b983;
  }

  .modal-body {
    margin: 20px 0;
  }

  .modal-default-button {
    float: right;
  }

  .modal-enter-from {
    opacity: 0;
  }

  .modal-leave-to {
    opacity: 0;
  }

  .modal-enter-from .modal-container,
  .modal-leave-to .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
  }

  .times {
    width: 30%;
  }

  .circle {
    fill: #00bac7;
  }

  .available {
    border: 1px solid #00bac7;
    cursor: pointer;
  }
  .available:hover {
    background-color: #00bac7;
  }
  .available:hover div p {
    color: white;
  }
  .available:hover div span svg {
    fill: white;
  }
  .unavailable {
    background-color: rgb(241 245 249);
    pointer-events: none;
    fill: #2f2f2f;
    opacity: 0.35;
  }
  .modal-close {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    color: #aaa;
    border: none;
    background: none;
    padding: 0;
    font-size: 1.5rem;
    cursor: pointer;
  }
</style>
