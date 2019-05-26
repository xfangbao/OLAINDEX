import axios from '../plugins/axios'

export function authorize(account_type, redirect) {
	return axios.get('/api/drive/authorize/' + account_type + '?redirect=' + redirect)
}
