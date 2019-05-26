import axios from '../plugins/axios'

export function getDriveInfo(params) {
	return axios.get('/api/drive/info', params)
}
