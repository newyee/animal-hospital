import { createGlobalState } from '@vueuse/core'
import { reactive } from 'vue'
import { useAxios } from '@vueuse/integrations/useAxios'
import {
  ReserveInfo,
  ReserveData,
  CompleteReserveData,
  CancelInfo,
} from '@/types'
import axios, { AxiosError } from '@/libs/axios'
const useReserve = createGlobalState(() => {
  const state = reactive<{
    isVisit?: number
    menu?: number
    formatDate?: string
    date?: string
    timeZoneId?: number
    reserveInfo?: ReserveInfo
    completeReserveInfo?: CompleteReserveData
    displayTime?: string
    cancelInfo?: CancelInfo
    cancelComplete?: CancelInfo
    cancelled: boolean
  }>({
    cancelled: false,
  })

  async function cancelReservationInfo(data: {
    token: string
  }): Promise<AxiosError | undefined> {
    const response = await useAxios<CancelInfo>(
      '/reservation/cancel/info',
      { method: 'POST', data },
      axios
    )
    if (response?.data?.value?.data) {
      state.cancelInfo = response.data.value
    }
    if (response?.data?.value?.cancelled) {
      state.cancelled = response.data.value.cancelled
    }
    return response.error.value as AxiosError
  }

  async function confirm(data: ReserveInfo): Promise<AxiosError | undefined> {
    const response = await useAxios<ReserveInfo>(
      '/reservation/confirm',
      { method: 'POST', data },
      axios
    )
    if (response?.data?.value) {
      const inputData = response.data.value
      state.reserveInfo = inputData
    }
    return response.error.value as AxiosError
  }

  async function decide(data: ReserveData): Promise<AxiosError | undefined> {
    const response = await useAxios<CompleteReserveData>(
      '/reservation/decide',
      { method: 'POST', data },
      axios
    )
    if (response?.data?.value) {
      const inputData = response.data.value
      state.completeReserveInfo = inputData
    }
    return response.error.value as AxiosError
  }

  async function cancelReservation(data: {
    cancellationReason: string
    token: string
  }): Promise<AxiosError | undefined> {
    const response = await useAxios<void>(
      '/reservation/cancel',
      { method: 'POST', data },
      axios
    )
    console.log('response?.data?.value', response?.data?.value)
    if (response?.data?.value) {
      state.cancelComplete = response.data.value
    }
    return response.error.value as AxiosError
  }

  return {
    state,
    confirm,
    decide,
    cancelReservationInfo,
    cancelReservation,
  }
})

export default useReserve
