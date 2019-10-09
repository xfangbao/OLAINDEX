import axios from '../service/axios'

export function loadAppConfig(params) {
	return axios.get('/api/app/config', params)
}
