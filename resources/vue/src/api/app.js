import axios from '../service/axios'

export function loadAppConfig() {
	return axios.get('/api/app/config')
}
