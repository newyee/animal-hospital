export interface LoginForm {
  email: string
  password: string
}
export interface RegisterForm {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export interface User {
  id?: number
  name?: string
  email?: string
  phoneNumber?: string
  newEmail?: string
}

export interface UpdateUser {
  name: string
  phoneNumber: string
}

export interface Authorisation {
  token: string
  type: 'bearer'
}

export interface AuthorisationUser {
  user: User
  authorisation: Authorisation
}

export interface AuthorisationUser {
  user: User
  authorisation: Authorisation
}

export interface ConfirmRegistration {
  requestMail: string
}

export interface registerVerify {
  code: string
}

export interface ReserveInfo {
  ownerName: string
  ticketNumber: string
  email: string
  phoneNumber: string
  remark: string
  petName: string
  petType: string
}
export interface ReserveData {
  userId: number
  visit: number
  timeZoneId: number
  medicalType: number
  date: string
  ownerName: string
  ticketNumber: string
  email: string
  phoneNumber: string
  remark: string
  petName: string
  petType: string
}

export interface CompleteReserveData {
  visit: number
  formatDate: string
  medicalType: string
  ownerName: string
  email: string
  phoneNumber: string
  petName: string
  petType: string
  remark: string
}

export interface ReservedInfo {
  [value: string]: string
  // [k: string]: string
}

export interface userId {
  userId: number
}

export interface ReservedTime {
  [date: string]: {
    time_zone_ids: number[]
  }
}

export interface UserReservedList {
  medicalType: string
  petName: string
  date: string
}

export interface EmailVerifyCode {
  code: string
}


export interface resendEmail {
  userId: string
}

export interface ChangePassword {
  currentPassword: string
  newPassword: string
  newPasswordConfirmation: string
}

export interface CancelInfo {
  data: {
    date: string
    medicalType: string
  }
  cancelled?: string
}
