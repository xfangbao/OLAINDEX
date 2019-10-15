import axios from '../service/axios'

export function getAllConfig(params) {
	return axios.get('/api/settings', params)
}

export function updateConfig(params) {
	return axios.post('/api/settings', params)
}
