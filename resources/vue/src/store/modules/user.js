import { login, logout, getUserInfo, refreshToken } from '../../api/user'
import { getToken, setToken, removeToken } from '../../utils/auth'
import Storage from '../../service/store'
export default {
	state: {
		user_id: '',
		name: '',
		token: getToken(),
		status: '',
		is_admin: false,
		account: false,
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
		setIsAdmin(state, is_admin) {
			state.is_admin = is_admin
		},
		setToken(state, token) {
			state.token = token
			setToken(token)
		},
		setAccount(state, account) {
			state.account = account
		},
		clearAll(state) {
			state.token = false
			state.user_id = ''
			state.name = ''
			state.status = false
			state.is_admin = false
			state.account = false
			removeToken()
			Storage.set('user', {})
		},
	},
	actions: {
		// 登录
		async handleLogin({ commit }, data) {
			return await new Promise((resolve, reject) => {
				login(data)
					.then(res => {
						const data = res.data
						commit('setToken', data.token)
						resolve(res)
					})
					.catch(err => {
						reject(err)
					})
			})
		},
		// 退出登录
		async handleLogout({ commit }) {
			return await new Promise((resolve, reject) => {
				logout()
					.then(res => {
						commit('clearAll')
						resolve(res)
					})
					.catch(err => {
						reject(err)
					})
			})
		},
		// 获取用户相关信息
		async getUserInfo({ commit }) {
			return await new Promise((resolve, reject) => {
				try {
					getUserInfo()
						.then(res => {
							const data = res.data
							commit('setUserId', data.id)
							commit('setUserName', data.name)
							commit('setStatus', data.status)
							commit('setIsAdmin', data.is_admin)
							Storage.set('user', data)
							resolve(res)
						})
						.catch(err => {
							reject(err)
						})
				} catch (error) {
					reject(error)
				}
			})
		},
		async refreshToken({ commit }) {
			return await new Promise((resolve, reject) => {
				try {
					refreshToken().then(res => {
						const data = res.data
						commit('setToken', data.token)
						resolve(res)
					})
				} catch (error) {
					reject(error)
				}
			})
		},
	},
}
