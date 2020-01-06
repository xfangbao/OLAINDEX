import axios from '../service/axios'

export function bind(params) {
	return axios.post('/api/account/bind', params)
}

export function unbind(params) {
	return axios.post('/api/account/unbind', params)
}

export function apply(params) {
	return axios.post('/api/account/apply', params)
}

export function info(params) {
	return axios.get('/api/account/info', params)
}
