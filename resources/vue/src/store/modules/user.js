import { getToken, setToken, removeToken } from '../../utils/auth'
import Storage from '../../service/store'
export default {
	state: {
		user_id: '',
		name: '',
		token: getToken(),
		status: '',
		bind_account: false,
	},
	mutations: {
		setUserId(state, id) {
			state.user_id = id
		},
		setUserName(state, name) {
			state.name = name
		},
		setStatus(state, status) {
			state.status = status
		},
		setToken(state, token) {
			state.token = token
			setToken(token)
		},
		clearAll(state) {
			state.token = ''
			state.user_id = ''
			state.name = ''
			state.status = ''
			removeToken()
			Storage.set('user', {})
		},
	},
	actions: {},
}
