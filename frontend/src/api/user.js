import axios from '../plugins/axios'

export function register(params) {
	return axios.post('/api/auth/register', params)
}

export function login(params) {
	return axios.post('/api/auth/login', params)
}

export function logout(params) {
	return axios.post('/api/auth/logout', params)
}

export function refreshToken(params) {
	return axios.post('/api/auth/refresh', params)
}

export function getUserInfo() {
	return axios.get('/api/auth/user')
}
