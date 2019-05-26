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
const toggleFullScreen = () => {
	let doc = window.document
	let docEl = doc.documentElement

	let requestFullScreen =
		docEl.requestFullscreen ||
		docEl.mozRequestFullScreen ||
		docEl.webkitRequestFullScreen ||
		docEl.msRequestFullscreen
	let cancelFullScreen =
		doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen

	if (
		!doc.fullscreenElement &&
		!doc.mozFullScreenElement &&
		!doc.webkitFullscreenElement &&
		!doc.msFullscreenElement
	) {
		requestFullScreen.call(docEl)
	} else {
		cancelFullScreen.call(doc)
	}
}

export default {
	setToken,
	getToken,
	removeToken,
	markTitle,
	toggleFullScreen,
}
