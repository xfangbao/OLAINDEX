import Cookies from 'js-cookie'

const TOKEN_KEY = 'v_access_token'

export function setToken(token) {
	Cookies.set(TOKEN_KEY, token)
}

export function getToken() {
	const token = Cookies.get(TOKEN_KEY)
	if (token) return token
	else return false
}

export function removeToken() {
	return Cookies.remove(TOKEN_KEY)
}
