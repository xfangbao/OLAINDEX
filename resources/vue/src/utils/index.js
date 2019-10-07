import Cookies from 'js-cookie'

const TOKEN_KEY = 'v_access_token'

const setToken = token => {
	Cookies.set(TOKEN_KEY, token)
}

const getToken = () => {
	const token = Cookies.get(TOKEN_KEY)
	if (token) return token
	else return false
}

const removeToken = () => {
	return Cookies.remove(TOKEN_KEY)
}

const markTitle = title => {
	title = title || 'OLAINDEX'
	window.document.title = title
}

export default {
	setToken,
	getToken,
	removeToken,
	markTitle,
}
