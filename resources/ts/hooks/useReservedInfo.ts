import { createGlobalState } from '@vueuse/core'
import { reactive } from 'vue'
import { useAxios } from '@vueuse/integrations/useAxios'
import { ReservedTime, UserReservedList } from '@/types'
import axios, { AxiosError } from '@/libs/axios'

const useReservedInfo = createGlobalState(() => {
  const reservedInfoState = reactive<{
    reservedInfo?: UserReservedList
    reservedTime?: ReservedTime
  }>({})

  async function getReservedInfo(data: {
    userId: number
  }): Promise<AxiosError | undefined> {
    const response = await useAxios(
      '/reserved/info',
      { method: 'POST', data },
      axios
    )
    if (response?.data?.value) {
      reservedInfoState.reservedInfo = response?.data?.value
    }
    return response.error.value as AxiosError
  }
  async function getReservedTime(): Promise<AxiosError | undefined> {
    const response = await useAxios('/reserved/time/list', axios)
    if (response?.data?.value) {
      reservedInfoState.reservedTime = response?.data?.value
    }
    return response.error.value as AxiosError
  }
  return {
    reservedInfoState,
    getReservedInfo,
    getReservedTime,
  }
})

export default useReservedInfo
