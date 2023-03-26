export const times: { id: number; time: string }[] = []

for (let i = 0; i < 12; i++) {
  let startHour, startMinute, endHour, endMinute
  if (i < 6) {
    startHour = 9 + Math.floor(i / 2)
    startMinute = i % 2 === 0 ? '00' : '30'
    endHour = startHour
    endMinute = i % 2 === 0 ? '30' : '00'
  } else {
    startHour = 16 + Math.floor((i - 6) / 2)
    startMinute = (i - 6) % 2 === 0 ? '00' : '30'
    endHour = startHour
    endMinute = (i - 6) % 2 === 0 ? '30' : '00'
  }
  if (endMinute === '00') {
    endHour += 1
  }
  const timeString = `${startHour
    .toString()
    .padStart(2, '0')}:${startMinute} 〜 ${endHour
    .toString()
    .padStart(2, '0')}:${endMinute}`
  times.push({ id: i + 1, time: timeString })
}
