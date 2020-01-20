import axios from '../service/axios'

export function login(params) {
	return axios.post('/api/login', params)
}

export function logout(params) {
	return axios.post('/api/logout', params)
}

export function refreshToken(params) {
	return axios.post('/api/refresh', params)
}

export function getUserInfo() {
	return axios.get('/api/user')
}

export function updateProfile(params) {
	return axios.post('/api/profile', params)
}
