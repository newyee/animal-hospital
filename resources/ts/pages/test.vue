<template>
  <div
    class="w-60 bg-white dark:border-[1px] dark:border-gray_border dark:bg-gray_800 rounded-lg shadow-lg"
  >
    <div class="flex justify-center space-x-2 py-3 items-center rounded-lg">
      <button
        type="button"
        class="-my-1.5 flex flex-none items-center justify-center p-1.5"
        @click="toPreviousMonth"
      >
        <span class="sr-only">Previous month</span>
        <!-- Heroicon name: solid/chevron-left -->
        <svg
          class="h-5 w-5 icon"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
          fill="currentColor"
          aria-hidden="true"
        >
          <path
            fill-rule="evenodd"
            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
            clip-rule="evenodd"
          />
        </svg>
      </button>
      <h2 class="font-normal text-gray_800 text-sm dark:text-white font-inter">
        {{ format(currentDate, 'MMMM yyyy') }}
      </h2>
      <button
        type="button"
        class="-my-1.5 -mr-1.5 ml-2 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500"
        @click="toNextMonth"
      >
        <!-- Heroicon name: solid/chevron-right -->
        <svg
          class="h-5 w-5 icon"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
          fill="currentColor"
          aria-hidden="true"
        >
          <path
            fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
            clip-rule="evenodd"
          />
        </svg>
      </button>
    </div>
    <div class="mt-2 grid grid-cols-7 text-center text-sm">
      <div class="text-gray_500 font-inter dark:text-white">日</div>
      <div class="text-gray_500 font-inter dark:text-white">月</div>
      <div class="text-gray_500 font-inter dark:text-white">火</div>
      <div class="text-gray_500 font-inter dark:text-white">水</div>
      <div class="text-gray_500 font-inter dark:text-white">木</div>
      <div class="text-gray_500 font-inter dark:text-white">金</div>
      <div class="text-gray_500 font-inter dark:text-white">土</div>
    </div>
    <div class="mt-2 grid grid-cols-7 text-sm font-inter">
      <button
        v-for="(day, index) in days"
        :key="`day-${index}`"
        type="button"
        class="mx-auto flex h-8 w-8 items-center justify-center rounded-lg"
        :class="{ 'bg-blue_400 justify-center rounded-lg': day.isCurrent }"
        @click="currentDate = day.date"
      >
        <time
          :class="{
            'text-number_calendar': !day.isCurrentMonth,
            'text-gray_800 dark:text-white': day.isCurrentMonth,
          }"
        >
          {{ format(day.date, 'd') }}
        </time>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
  import {
    subDays,
    addDays,
    eachDayOfInterval,
    format,
    startOfMonth,
    compareAsc,
    startOfDay,
    addMonths,
    subMonths,
    getDay,
  } from 'date-fns'
  import { computed, ref } from 'vue'
  // 日本時間の0時
  const currentDate = ref(startOfDay(new Date()))
  console.log('currentDate', currentDate)
  const monthStart = startOfMonth(currentDate.value)
  console.log(getDay(monthStart))

  const days = computed(() => {
    //今月1日を取得
    const monthStart = startOfMonth(currentDate.value)

    //曜日を取得(日曜日は0)
    const dayNumInWeek = getDay(monthStart)

    const calendarStart = subDays(
      monthStart,
      dayNumInWeek !== 0 ? dayNumInWeek - 1 : 6
    )

    //eachDayOfIntervalは指定期間内の各日付を配列に格納して返却します
    return eachDayOfInterval({
      start: calendarStart,
      end: addDays(calendarStart, 41),
    }).map((date) => {
      {
        return {
          isCurrent: compareAsc(currentDate.value, date) === 0,
          isCurrentMonth: date.getMonth() === currentDate.value.getMonth(),
          date: date,
        }
      }
    })
  })

  function toNextMonth() {
    currentDate.value = addMonths(startOfMonth(currentDate.value), 1)
  }

  function toPreviousMonth() {
    currentDate.value = subMonths(startOfMonth(currentDate.value), 1)
  }
</script>
