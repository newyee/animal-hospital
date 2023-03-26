<script setup lang="ts">
  import { timezoneModal } from '@/components/Modal'
  import useReservedInfo from '@/hooks/useReservedInfo'
  import eachWeekOfInterval from 'date-fns/eachWeekOfInterval'
  import startOfMonth from 'date-fns/startOfMonth'
  import endOfMonth from 'date-fns/endOfMonth'
  import {
    getMonth,
    addDays,
    addMonths,
    subMonths,
    format,
    isBefore,
    isAfter,
    subDays,
  } from 'date-fns'
  import ja from 'date-fns/locale/ja'
  import { computed, onMounted, onUnmounted, ref } from 'vue'
  import { ReservedTime } from '@/types'

  const { reservedInfoState, getReservedTime } = useReservedInfo()

  const today = new Date()

  const state = ref({
    currentDate: new Date(),
    reservedTimeData: {} as ReservedTime,
    modal: false,
    modalFormattedDate: '',
    modalTimeZoneIds: [] as number[],
    modalReserveDate: '',
  })

  const calendars = computed(() => {
    return getCalendar()
  })
  const thisMonth = getMonth(today)
  const nextMonthValue = thisMonth + 2

  function confirmSave(event: { returnValue: string }) {
    event.returnValue = '予約内容が保存されませんが、よろしいですか？'
  }

  onMounted(() => {
    window.addEventListener('beforeunload', confirmSave)
  })
  onUnmounted(() => {
    window.removeEventListener('beforeunload', confirmSave)
  })

  async function getReservedTimeData() {
    await getReservedTime()
    if (reservedInfoState.reservedTime) {
      state.value.reservedTimeData = reservedInfoState.reservedTime
    }
  }
  getReservedTimeData()
  const formatCurrentDate = computed(() => {
    return `${state.value.currentDate.getFullYear()}年${
      state.value.currentDate.getMonth() + 1
    }月`
  })
  const prevMonthDisplay = computed(() => {
    const prevMonth = subMonths(state.value.currentDate, 1).getMonth() + 1
    return `${prevMonth}月`
  })
  const nextMonthDisplay = computed(() => {
    const prevMonth = addMonths(state.value.currentDate, 1).getMonth() + 1
    return `${prevMonth}月`
  })
  function youbi(dayIndex: number) {
    const week = ['日', '月', '火', '水', '木', '金', '土']
    return week[dayIndex]
  }

  function getCalendar() {
    const getSundays = computed(() => {
      return eachWeekOfInterval({
        start: startOfMonth(state.value.currentDate),
        end: endOfMonth(state.value.currentDate),
      })
    })
    const getStartDate = () => {
      return getSundays.value[0]
    }
    let startDate = getStartDate()

    const weekNumber = getSundays.value.length
    const calendars = []
    for (let week = 0; week < weekNumber; week++) {
      const weekRow = []
      for (let day = 0; day < 7; day++) {
        let displayDate
        if (startDate.getMonth() === state.value.currentDate.getMonth()) {
          displayDate = startDate.getDate()
        } else {
          displayDate = ''
        }
        const formatStartDate = format(startDate, 'yyyy-MM-dd')
        const formattedDate = format(startDate, 'MM月dd日(EEE)', {
          locale: ja,
        })

        let slots = -100

        let timeZoneIds: number[] = []

        if (
          state.value.reservedTimeData &&
          formatStartDate in state.value.reservedTimeData
        ) {
          timeZoneIds =
            state.value.reservedTimeData[formatStartDate].time_zone_ids
          slots = 12 - timeZoneIds.length
        }
        weekRow.push({
          day: displayDate,
          month: format(startDate, 'yyyy-MM'),
          date: startDate,
          slots: slots,
          formattedDate: formattedDate,
          timeZoneIds: timeZoneIds,
          formatStartDate: formatStartDate,
        })
        startDate = addDays(startDate, 1)
      }
      calendars.push(weekRow)
    }
    return calendars
  }

  function nextMonth() {
    if (nextMonthValue === getMonth(state.value.currentDate)) {
      return
    }
    state.value.currentDate = addMonths(state.value.currentDate, 1)
  }

  function prevMonth() {
    if (thisMonth === getMonth(state.value.currentDate)) {
      return
    }
    state.value.currentDate = subMonths(state.value.currentDate, 1)
  }
  function currentMonth() {
    return format(state.value.currentDate, 'yyyy-MM')
  }

  function showModal(day: {
    formattedDate: string
    timeZoneIds: number[]
    formatStartDate: string
  }) {
    state.value.modalFormattedDate = day.formattedDate
    state.value.modalTimeZoneIds = []
    state.value.modalTimeZoneIds.push(...day.timeZoneIds)
    state.value.modalReserveDate = day.formatStartDate
    state.value.modal = true
  }

  function closeModal() {
    state.value.modal = false
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
        <li class="daisy-step">情報入力</li>
        <li class="daisy-step">内容確認</li>
      </ul>
      <div class="daisy-divider" />
      <div
        style="min-width: 500px; width: 512px"
        class="overflow-hidden rounded-lg mx-auto mt-8 text-gray-900 font-semibold text-center"
      >
        <div class="flex items-center justify-around px-4 py-6">
          <button
            class="p-2 flex border-2 border-indigo-700 items-center rounded"
            :class="{
              disabled: thisMonth === getMonth(state.currentDate),
            }"
            @click="prevMonth"
          >
            <svg
              class="w-4 h-4 stroke-current w-3"
              fill="none"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="4"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="text-sm">{{ prevMonthDisplay }}</span>
          </button>
          <div class="text-lg">{{ formatCurrentDate }}</div>
          <button
            class="p-2 flex items-center border-2 border-indigo-700 rounded"
            :class="{
              disabled: nextMonthValue === getMonth(state.currentDate),
            }"
            @click="nextMonth"
          >
            <span class="text-sm">{{ nextMonthDisplay }}</span>
            <svg
              class="w-4 h-4 stroke-current w-3"
              fill="none"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="4"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path d="M9 5l7 7-7 7"></path>
            </svg>
          </button>
        </div>
        <div class="calendar border-t">
          <div class="calendar-weekly flex bg-neutral-900 border-l">
            <div
              v-for="n in 7"
              :key="n"
              class="calendar-youbi flex-1 border-r text-gray_500 font-inter dark:text-white"
            >
              {{ youbi(n - 1) }}
            </div>
          </div>
          <div
            v-for="(week, index) in calendars"
            :key="index"
            class="calendar-weekly flex bg-neutral-900 border-l"
          >
            <div
              v-for="(day, index) in week"
              :key="index"
              :class="{
                outside:
                  isAfter(subDays(today, 1), day.date) ||
                  currentMonth() !== day.month ||
                  day.slots === 0,
                currentDay: currentMonth() === day.month,
              }"
              class="calendar-daily flex-1 border-r border-b min-h-full"
              @click="showModal(day)"
            >
              <div class="calendar-day mb-1.5 mt-1">
                {{ day.day }}
              </div>
              <div
                v-if="
                  day.slots <= 2 &&
                  day.slots > 0 &&
                  isBefore(subDays(today, 1), day.date) &&
                  currentMonth() === day.month
                "
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  class="w-5/12 figure mx-auto"
                >
                  <title>triangle-outline</title>
                  <path d="M12,2L1,21H23M12,6L19.53,19H4.47" />
                </svg>
                <p>
                  <span class="text-xs text-slate-400">あと</span>
                  <span class="text-slate-500">{{ day.slots }}</span>
                  <span class="text-xs text-slate-400">枠</span>
                </p>
              </div>
              <div
                v-else-if="
                  (day.slots >= 3 &&
                    isBefore(subDays(today, 1), day.date) &&
                    currentMonth() === day.month) ||
                  (day.slots === -100 &&
                    isBefore(subDays(today, 1), day.date) &&
                    currentMonth() === day.month)
                "
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  class="w-5/12 figure mx-auto"
                >
                  <title>circle-outline</title>
                  <path
                    d="M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"
                  />
                </svg>
                <p v-if="day.slots === -100">
                  <span class="text-xs text-slate-400">あと</span>
                  <span class="text-slate-500">12</span>
                  <span class="text-xs text-slate-400">枠</span>
                </p>
                <p v-else>
                  <span class="text-xs text-slate-400">あと</span>
                  <span class="text-slate-500">{{ day.slots }}</span>
                  <span class="text-xs text-slate-400">枠</span>
                </p>
              </div>
              <div v-else-if="day.slots === 0">
                <span class="w-6/12 opacity-50">満</span>
              </div>
              <div v-else>
                <span class="w-6/12 opacity-50">-</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Teleport to="body">
      <!-- use the modal component, pass in the prop -->
      <timezoneModal
        :show="state.modal"
        :date="state.modalReserveDate"
        :time-zone-ids="state.modalTimeZoneIds"
        :format-start-date="state.modalFormattedDate"
        @close="closeModal()"
      >
      </timezoneModal>
    </Teleport>
  </div>
</template>
<style scoped>
  .calendar-daily {
    min-height: 125px;
  }
  .calendar-daily:hover {
    color: white;
    background: #00bac7;
  }
  .calendar-daily:hover span {
    color: white;
  }
  .calendar-daily:hover svg {
    fill: white;
  }
  .outside {
    background-color: #f7f7f7;
    pointer-events: none;
  }
  .currentDay:hover {
    background-color: #00bac7;
    cursor: pointer;
  }
  .disabled {
    opacity: 0.5;
    cursor: default;
  }
  .figure {
    fill: #00bac7;
  }
</style>
