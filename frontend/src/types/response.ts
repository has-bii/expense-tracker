export interface ResponseSuccess<T = null> {
  message: string
  success: true
  data: T
}

export interface ResponseError {
  success: false
  message: string
  data: null
}

export type Response<T = null> = {
  message: string
} & (
  | {
      success: true
      data: T
    }
  | {
      success: false
      data: null
    }
)
