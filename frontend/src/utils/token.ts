class Token {
  private prefix = import.meta.env.VITE_AUTH_TOKEN_PREFIX || '__auth_token_v1'

  constructor() {}

  setToken(token: string) {
    localStorage.setItem(this.prefix, token)
  }

  getToken() {
    return localStorage.getItem(this.prefix)
  }

  removeToken() {
    return localStorage.removeItem(this.prefix)
  }
}

export const token = new Token()
